<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Validate;

use function array_filter;
use function array_map;
use function array_values;
use function count;

class ValidatedFieldResult
{
    /** @var ValidatedFieldError[] */
    private array $errors;

    /**
     * @param ValidatedFieldError[] $errors
     */
    public function __construct(array $errors = [])
    {
        $this->errors = array_values(array_map(
            static fn (ValidatedFieldError $error) => $error,
            $errors,
        ));
    }

    /**
     * @return ValidatedFieldError[]
     */
    public function errors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function hasNoErrors(): bool
    {
        return ! $this->hasErrors();
    }

    /**
     * @return mixed[]
     */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->errors);
    }

    public function addError(ValidatedFieldError $error): self
    {
        $this->errors[] = $error;

        return $this;
    }

    public function addErrorMessage(string $message): self
    {
        return $this->addError(new ValidatedFieldError($message));
    }

    public function hasErrorMessage(string $message): bool
    {
        $items = array_values(array_filter(
            $this->errors,
            static function ($loopMessage) use ($message) {
                return $loopMessage->errorMsg() === $message;
            }
        ));

        return count($items) > 0;
    }
}
