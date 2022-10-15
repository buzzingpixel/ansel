<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings;

use function array_filter;
use function array_map;
use function array_values;
use function json_encode;

class FieldSettingCustomFieldCollection
{
    /** @var FieldSettingCustomField[] */
    private array $fields;

    /**
     * @param FieldSettingCustomField[] $fields
     */
    public function __construct(array $fields = [])
    {
        $this->fields = array_values(array_map(
            static function (FieldSettingCustomField $field) {
                return $field;
            },
            $fields,
        ));
    }

    /**
     * @return FieldSettingCustomField[]
     */
    public function asArray(): array
    {
        return $this->fields;
    }

    /**
     * @return scalar[]
     */
    public function asScalarArray(): array
    {
        /** @phpstan-ignore-next-line */
        return array_values(array_map(
            static fn (FieldSettingCustomField $f) => [
                'label' => $f->label(),
                'handle' => $f->handle(),
                'type' => $f->type(),
                'required' => $f->required(),
            ],
            $this->asArray(),
        ));
    }

    public function asJson(): string
    {
        return (string) json_encode($this->asScalarArray());
    }

    public function addField(FieldSettingCustomField $field): void
    {
        $this->fields[] = $field;
    }

    /**
     * @param callable(FieldSettingCustomField $customField): ReturnType $callback
     *
     * @return ReturnType[]
     *
     * @template ReturnType
     */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->asArray());
    }

    /**
     * @param callable(FieldSettingCustomField $customField): bool $callback
     */
    public function filter(
        callable $callback
    ): FieldSettingCustomFieldCollection {
        return new FieldSettingCustomFieldCollection(
            array_values(array_filter(
                $this->asArray(),
                $callback,
            )),
        );
    }

    public function first(): FieldSettingCustomField
    {
        return $this->asArray()[0];
    }

    public function firstOrNull(): ?FieldSettingCustomField
    {
        return $this->asArray()[0] ?? null;
    }

    private function filterByHandle(
        string $handle
    ): FieldSettingCustomFieldCollection {
        return $this->filter(
            static function (
                FieldSettingCustomField $customField
            ) use ($handle) {
                return $customField->handle() === $handle;
            }
        );
    }

    public function getByHandle(string $handle): FieldSettingCustomField
    {
        return $this->filterByHandle($handle)->first();
    }

    public function getByHandleOrNull(string $handle): ?FieldSettingCustomField
    {
        return $this->filterByHandle($handle)->firstOrNull();
    }
}
