<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence\Ee;

use BuzzingPixel\Ansel\Field\Field\Persistence\Fetching\FetchParameters;
use BuzzingPixel\Ansel\Field\Field\Persistence\Fetching\Operator;
use BuzzingPixel\Ansel\Field\Field\Persistence\Fetching\OrderParameter;
use BuzzingPixel\Ansel\Field\Field\Persistence\Fetching\Parameter;
use BuzzingPixel\Ansel\Field\Field\Persistence\Fetching\WhereParameter;
use BuzzingPixel\Ansel\Field\Field\Persistence\Record;
use BuzzingPixel\Ansel\Field\Field\Persistence\RecordCollection;
use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;
use Exception;
use ExpressionEngine\Service\Database\Query;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Throwable;

use function array_map;
use function is_array;

class FetchRecords
{
    private EeQueryBuilderFactory $queryBuilderFactory;

    public function __construct(EeQueryBuilderFactory $queryBuilderFactory)
    {
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    /**
     * @param class-string<R> $recordClass
     *
     * @return RecordCollection<R>
     *
     * @throws ReflectionException
     *
     * @template R of Record
     */
    public function fetch(
        FetchParameters $parameters,
        string $recordClass
    ): RecordCollection {
        /** @var class-string<Record> $rClass */
        $rClass = $recordClass;

        $query = $this->queryBuilderFactory->create();

        $query->from($rClass::tableName());

        $parameters->map(
            fn (Parameter $parameter) => $this->mapParameter(
                $query,
                $parameter,
            ),
        );

        if ($parameters->limit() !== null) {
            $query->limit($parameters->limit());
        }

        /** @var RecordCollection<R> $collection */
        $collection = new RecordCollection(array_map(
            static function (array $item) use ($rClass): Record {
                $record = new $rClass();

                foreach ($item as $key => $value) {
                    $record->setProperty($key, $value);
                }

                return $record;
            },
            $query->get()->result_array()
        ));

        return $collection;
    }

    /**
     * @throws Exception
     */
    private function mapParameter(Query $query, Parameter $parameter): void
    {
        $ref = new ReflectionClass($parameter);

        $method = 'map' . $ref->getShortName();

        try {
            /** @phpstan-ignore-next-line */
            $this->{$method}($query, $parameter);
        } catch (Throwable $e) {
            throw new Exception($method . ' not implemented');
        }
    }

    private function mapOrderParameter(
        Query $query,
        OrderParameter $parameter
    ): void {
        $query->order_by(
            $parameter->property(),
            $parameter->value(),
        );
    }

    private function mapWhereParameter(
        Query $query,
        WhereParameter $parameter
    ): void {
        $property = $parameter->property();

        $value = $parameter->value();

        if ($parameter->operator()->equals(Operator::NONE())) {
            throw new RuntimeException('Invalid operator');
        }

        if ($parameter->operator()->equals(Operator::NOT_IN())) {
            if (! is_array($value)) {
                throw new RuntimeException('Value must be array');
            }

            $query->where_not_in($property, $value);

            return;
        }

        if ($parameter->operator()->equals(Operator::IN())) {
            if (! is_array($value)) {
                throw new RuntimeException('Value must be array');
            }

            $query->where_in($property, $value);

            return;
        }

        $query->where(
            $property . ' ' . $parameter->operator()->getValue(),
            $value,
        );
    }
}
