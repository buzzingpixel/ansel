<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence;

use ReflectionObject;
use ReflectionProperty;
use RuntimeException;
use Throwable;

use function in_array;

abstract class Record
{
    public int $id;

    abstract public static function tableName(): string;

    /**
     * Ensure all columns are explicitly declared on the record. If we change
     * a column name, we'll get an exception when PDO tries to populate this
     *
     * @param mixed $value
     */
    public function __set(string $name, $value): void
    {
        throw new RuntimeException(
            'Property ' . $name . ' must be declared explicitly',
        );
    }

    public function isNew(): bool
    {
        return ! isset($this->id) || $this->id <= 0;
    }

    /**
     * @param string[] $excludeProps
     *
     * @return scalar[]
     */
    public function asArray(array $excludeProps = []): array
    {
        $ref = new ReflectionObject($this);

        $refProps = $ref->getProperties(ReflectionProperty::IS_PUBLIC);

        $array = [];

        foreach ($refProps as $prop) {
            $name = $prop->getName();

            if (in_array($name, $excludeProps, true)) {
                continue;
            }

            try {
                $array[$name] = $prop->getValue($this);
            } catch (Throwable $e) {
                $array[$name] = null;
            }
        }

        /** @phpstan-ignore-next-line */
        return $array;
    }
}
