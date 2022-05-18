<?php

namespace BuzzingPixel\Treasury\Controller;

use BuzzingPixel\Treasury\Model\FilesTagParams;
use BuzzingPixel\Treasury\Service\Data\VarsPrepare;
use BuzzingPixel\Treasury\Service\TagNoResults;

class FilesTag
{
	/**
	 * Tag params
	 */
	private $tagParams;

	/**
	 * Tag data
	 */
	private $tagData;

	/**
	 * Constructor
	 *
	 * @param array $tagParams
	 * @param string $tagData
	 */
	public function __construct($tagParams, $tagData)
	{
		// Make sure incoming tag params are an array
		if (gettype($tagParams) !== 'array') {
			$tagParams = array();
		}

		if (gettype($tagData) === 'string') {
			$tagData = trim($tagData);
		}

		$this->tagParams = new FilesTagParams($tagParams);
		$this->tagData = $tagData;
	}

	/**
	 * Render the tag
	 */
	public function render()
	{
		// Get files API
		$filesAPI = ee('treasury:FilesAPI');

		// Loop through filters
		foreach (FilesTagParams::$filters as $key => $defaultOperator) {
			// Get the value
			$val = $this->tagParams->{$key};

			// Filter if value exists
			if ($val) {
				// Get the operator
				$op = $this->tagParams->{"{$key}_operator"} ?: $defaultOperator;

				// Check if this can be an array filter
				if (gettype($val) === 'array' && ($op !== 'IN' && $op !== 'NOT IN')) {
					$val = $val[0];
				}

				// Set the filter
				$filesAPI->filter($key, $op, $val);
			}
		}

		// Set limiting
		if ($this->tagParams->limit) {
			$filesAPI->limit($this->tagParams->limit);
		}

		// Set offset
		if ($this->tagParams->offset) {
			$filesAPI->offset($this->tagParams->offset);
		}

		// Set ordering
		if ($this->tagParams->order && $this->tagParams->sort) {
			$filesAPI->order($this->tagParams->order, $this->tagParams->sort);
		} elseif ($this->tagParams->order) {
			$filesAPI->order($this->tagParams->order);
		}

		// Check if tagdata is false (not tag pair)
		if (! $this->tagData) {
			$files = $filesAPI->getFirst();

			// If there are results
			if ($files->file_url) {
				return $files->file_url;
			}

			// Return no results
			return false;
		}

		// Get the files model collection
		$files = $filesAPI->getFiles();

		// Check for results
		if (count($files) < 1) {
			// Return the no results processing for if no_results tag
			return TagNoResults::process(
				$this->tagData,
				$this->tagParams->namespace
			);
		}

		// Return the parsed tag pair contents
		return ee()->TMPL->parse_variables(
			$this->tagData,
			VarsPrepare::process($files, $this->tagParams->namespace)
		);
	}
}
