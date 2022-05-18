<?php

namespace BuzzingPixel\Treasury\Factory;

use BuzzingPixel\Treasury\Service\Data\Collection;

class Record
{
	// Record name
	private $recordName;

	// Record class
	private $recordClass;

	// Table name
	private $tableName;

	// Filters array
	private $filters = array();

	// Search array
	private $search = array();

	// Ordering array
	private $ordering = array();

	// Limit
	private $limit;

	// Offset
	private $offset;

	// Filter query map
	private $filterMap = array(
		'==' => 'where',
		'!=' => 'where',
		'<' => 'where',
		'>' => 'where',
		'<=' => 'where',
		'>=' => 'where',
		'IN' => 'where_in',
		'NOT IN' => 'where_not_in'
	);

	/**
	 * Constructor
	 *
	 * $param string $name The name of the record to get
	 */
	public function __construct($name = null)
	{
		$addonInfo = ee('Addon')->get('treasury');

		// Set the record name
		$this->recordName = $name;

		// Get the record class
		$this->recordClass = '\\' . $addonInfo->get('namespace') . '\\Record\\' . $this->recordName;

		// Set the record table name
		$tableNameProperty = new \ReflectionProperty(
			$this->recordClass, '_tableName'
		);
		$this->tableName = $tableNameProperty->getValue();

		if (! $this->tableName) {
			throw new \Exception(
				'This record does not have a table name and cannot be retrieved'
			);
		}
	}

	/**
	 * Get a record
	 *
	 * @param string $name The name of the record to get
	 * @return self
	 */
	public function get($name)
	{
		$this->recordName = $name;
		return $this;
	}

	/**
	 * Filter the record
	 *
	 * @param string $filterOn
	 * @param mixed $condition
	 * @param mixed $value
	 * @return self
	 */
	public function filter($filterOn, $condition, $value = null)
	{
		// Check if $condition is a condition or a value
		if ($value === null) {
			$value = $condition;
			$condition = '==';
		}

		// Throw an error if the condition is not an accepted condition
		if (! isset($this->filterMap[$condition])) {
			throw new \Exception('Conditional parameter not allowed');
		}

		// Add the filter to the filters array
		$this->filters[] = compact(
			'filterOn', 'condition', 'value'
		);

		// Return the instance of the record factory
		return $this;
	}

	/**
	 * Search the record
	 *
	 * @param string $name
	 * @param string $string
	 * @param string $condition
	 */
	public function search($name, $string, $condition = 'and')
	{
		if ($condition !== 'and' && $condition !== 'or') {
			$condition = 'and';
		}

		$this->search[] = array(
			'condition' => $condition,
			'name' => $name,
			'string' => $string
		);
	}

	/**
	 * Order the record
	 *
	 * @param string $by
	 * @param string $sort
	 * @return self
	 */
	public function order($by, $sort = 'DESC')
	{
		// Set the order to the ordering array
		$this->ordering[] = compact(
			'by',
			'sort'
		);

		// Return the instance of the record factory
		return $this;
	}

	/**
	 * Set record limit
	 *
	 * @param int $limit
	 * @return self
	 */
	public function limit($limit)
	{
		// Set the limit
		$this->limit = $limit;

		// Return the instance of the record factory
		return $this;
	}

	/**
	 * Set record offset
	 *
	 * @param int $offset
	 * @return self
	 */
	public function offset($offset)
	{
		// Set the offset
		$this->offset = $offset;

		// Return the instance of the record factory
		return $this;
	}

	/**
	 * Get first result
	 *
	 * @return object An instance of the requested record
	 */
	public function first()
	{
		// Save original limit
		$originalLimit = $this->limit;

		// If we're getting the first result, we know the limit should be 1
		$this->limit = 1;

		// Run the query
		$records = $this->runQuery();

		// Reset the limit
		$this->limit = $originalLimit;

		// If there are no records, return an empty record class
		if (! $records) {
			return new $this->recordClass();
		}

		// Return the first record
		return $records[0];
	}

	/**
	 * Get all results
	 *
	 * @return object Collection
	 */
	public function all()
	{
		// Run the query
		$records = $this->runQuery();

		// Return the collection of records
		return new Collection($records);
	}

	/**
	 * Delete records matching criteriea
	 */
	public function delete()
	{
		// Apply the filters
		$this->applyFilters();

		// Delete applicable items from the database
		ee()->db->delete($this->tableName);
	}

	/**
	 * Get count from database
	 */
	public function count()
	{
		ee()->db->select('COUNT(*) AS count')
			->from($this->tableName);

		// Apply the filters
		$this->applyFilters();

		// Get the result
		$result = ee()->db->get()->row();

		// Return count
		return (int) $result->count;
	}

	/**
	 * Run the query
	 */
	private function runQuery()
	{
		// Start the query
		ee()->db->select('*')
			->from($this->tableName);

		// Apply the filters
		$this->applyFilters();

		// Get the result
		$result = ee()->db->get()->result();

		// Start an array for the records
		$records = array();

		// Get a record of each result
		foreach ($result as $data) {
			// Create a new record class
			$record = new $this->recordClass();

			// Populate it with the data from the result
			foreach ($data as $key => $item) {
				$record->{$key} = $item;
			}

			// Add the record to the array
			$records[] = $record;
		}

		// Return the records array
		return $records;
	}

	/**
	 * Apply filters
	 */
	private function applyFilters()
	{
		// Apply filters
		foreach ($this->filters as $filter) {
			if ($this->filterMap[$filter['condition']] === 'where') {
				if ($filter['condition'] === '==') {
					ee()->db->where($filter['filterOn'], $filter['value']);
				} else {
					ee()->db->where(
						$filter['filterOn'] . ' ' . $filter['condition'],
						$filter['value']
					);
				}
			} else {
				ee()->db->{$this->filterMap[$filter['condition']]}(
					$filter['filterOn'],
					$filter['value']
				);
			}
		}

		// Apply and search
		$likeQuery = '';

		foreach ($this->search as $search) {
			// We have to build the LIKE query manually because fricking
			// CodeIgniter 2 does not have a way to group Where statements
			if ($search['condition'] === 'and') {
				if ($likeQuery) {
					$likeQuery .= ' AND ';
				}
				// ee()->db->like($search['name'], $search['string']);
			} elseif ($search['condition'] === 'or') {
				if ($likeQuery) {
					$likeQuery .= ' OR ';
				}
				// ee()->db->or_like($search['name'], $search['string']);
			}

			$likeQuery .= "`{$search['name']}` LIKE '%{$search['string']}%'";
		}

		if ($likeQuery) {
			ee()->db->where("({$likeQuery})");
		}

		// Apply ordering
		foreach ($this->ordering as $ordering) {
			ee()->db->order_by($ordering['by'], $ordering['sort']);
		}

		// Apply limit
		if ($this->limit) {
			ee()->db->limit($this->limit);
		}

		// Apply offset
		if ($this->offset) {
			ee()->db->offset($this->offset);
		}
	}
}
