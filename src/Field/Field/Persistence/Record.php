<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Persistence;

use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionObject;
use ReflectionProperty;
use RuntimeException;
use Throwable;

use function in_array;

abstract class Record
{
    /** @var ReflectionClass<Record> */
    private ReflectionClass $ref;

    public int $id;

    abstract public static function tableName(): string;

    public function __construct()
    {
        $this->ref = new ReflectionClass($this);
    }

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
     * @return array<(float|int|string|bool|null)>
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

    /**
     * @param float|int|string|bool $value
     *
     * @throws ReflectionException
     * @throws Exception
     */
    public function setProperty(string $property, $value): self
    {
        $refProp = $this->ref->getProperty($property);

        /** @phpstan-ignore-next-line */
        $type = $refProp->getType()->getName();

        switch ($type) {
            case 'int':
                $value = (int) $value;
                break;
            case 'string':
                $value = (string) $value;
                break;
            case 'float':
                $value = (float) $value;
                break;
            case 'bool':
                $value = (bool) $value;
                break;
            default:
                throw new Exception(
                    'Type ' . $type . ' not implemented'
                );
        }

        /** @phpstan-ignore-next-line */
        $this->{$property} = $value;

        return $this;
    }
}
