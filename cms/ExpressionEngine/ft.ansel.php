<?php
/** @noinspection PhpIllegalPsrClassPathInspection */

declare(strict_types=1);

use BuzzingPixel\Ansel\Field\Settings\ExpressionEngine\GetFieldSettings;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollectionValidatorContract;
use BuzzingPixel\AnselConfig\ContainerManager;
use ExpressionEngine\Service\Validation\Result;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/** @noinspection PhpIllegalPsrClassPathInspection */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
// phpcs:disable Squiz.Classes.ClassFileName.NoMatch
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
// phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
// phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint

class Ansel_ft extends EE_Fieldtype
{
    /**
     * Var ID for Low Variables (so PHPStorm doesn’t show errors)
     *
     * @param mixed
     *
     * @phpstan-ignore-next-line
     */
    public $var_id;

    /**
     * Error Message for Low Variables (so PHPStorm doesn’t show errors)
     *
     * @param mixed
     *
     * @phpstan-ignore-next-line
     */
    public $error_msg;

    /** Set field type as tag pair */
    public bool $has_array_data = true;

    /** @var mixed[]|null  */
    private ?array $postedSettings = null;

    /**
     * Required info for EE fieldtype
     *
     * @var string[]
     */
    public array $info = [
        'name' => ANSEL_NAME,
        'version' => ANSEL_VER,
    ];

    private GetFieldSettings $getFieldSettings;

    private FieldSettingsCollectionValidatorContract $fieldSettingsValidator;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        parent::__construct();

        $container = (new ContainerManager())->container();

        /** @phpstan-ignore-next-line */
        $this->getFieldSettings = $container->get(GetFieldSettings::class);

        /** @phpstan-ignore-next-line */
        $this->fieldSettingsValidator = $container->get(
            FieldSettingsCollectionValidatorContract::class,
        );
    }

    /**
     * @param mixed[] $rawEeFieldSettings
     */
    private function getFieldSettingsCollection(
        array $rawEeFieldSettings
    ): FieldSettingsCollection {
        /** @phpstan-ignore-next-line */
        $fieldSettingsArray = $rawEeFieldSettings['ansel']['fieldSettings'] ?? [];

        /** @phpstan-ignore-next-line */
        unset($fieldSettingsArray['placeholder']);

        return FieldSettingsCollection::fromFieldArray(
        /** @phpstan-ignore-next-line */
            $fieldSettingsArray,
        );
    }

    /**
     * @param mixed $data
     */
    public function display_field($data)
    {
        // TODO: Implement display_field() method.
        dd('TODO: Implement display_field() method.');
    }

    /**
     * @param mixed $data
     *
     * @return mixed[]
     *
     * @phpstan-ignore-next-line
     */
    public function display_settings($data): array
    {
        $fieldSettings = $this->getFieldSettingsCollection(
            /** @phpstan-ignore-next-line */
            $this->postedSettings ?? $data,
        );

        return [
            'field_options_ansel' => [
                'label' => 'field_options',
                'group' => 'ansel',
                'settings' => [
                    $this->getFieldSettings->render(
                        $fieldSettings,
                    )->content(),
                ],
            ],
        ];
    }

    /**
     * This is called before validate settings :eyeroll:
     *
     * @param mixed[] $data
     *
     * @return mixed[]
     *
     * @phpstan-ignore-next-line
     */
    public function save_settings($data): array
    {
        $this->postedSettings = $data;

        return $data;
    }

    /**
     * @param mixed[] $data
     *
     * @phpstan-ignore-next-line
     */
    public function validate_settings($data): Result
    {
        $errors = $this->fieldSettingsValidator->validate(
            $this->getFieldSettingsCollection(
                $data
            ),
        );

        $result = new Result();

        foreach ($errors as $errorKey => $error) {
            /** @phpstan-ignore-next-line */
            $result->addFailed($errorKey, $error);
        }

        return $result;
    }
}
