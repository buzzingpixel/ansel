<?php
/** @noinspection PhpIllegalPsrClassPathInspection */

declare(strict_types=1);

use BuzzingPixel\Ansel\Field\Settings\ExpressionEngine\GetFieldSettings;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollectionValidatorContract;
use BuzzingPixel\Ansel\Field\Settings\PopulateFieldSettingsFromDefaults;
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
// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingAnyTypeHint

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

    private PopulateFieldSettingsFromDefaults $populateFieldSettingsFromDefaults;

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

        /** @phpstan-ignore-next-line */
        $this->populateFieldSettingsFromDefaults = $container->get(
            PopulateFieldSettingsFromDefaults::class,
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
     * @param string $name
     *
     * @phpstan-ignore-next-line
     */
    public function accepts_content_type($name): bool
    {
        return in_array(
            $name,
            [
                'blocks/1',
                'channel',
                'grid',
                'low_variables',
                'fluid_field',
            ],
            true,
        );
    }

    /**
     * @param mixed $data
     */
    private function getDisplaySettings($data, string $fieldNameRoot): string
    {
        $fieldSettings = $this->getFieldSettingsCollection(
        /** @phpstan-ignore-next-line */
            $this->postedSettings ?? $data,
        );

        /** @phpstan-ignore-next-line */
        $anselData = $this->postedSettings['ansel']['fieldSettings'] ?? null;

        if ($anselData === null) {
            /** @phpstan-ignore-next-line */
            $anselData = $data['ansel']['fieldSettings'] ?? null;
        }

        $isNew = $anselData === null;

        if ($isNew) {
            $this->populateFieldSettingsFromDefaults->populate(
                $fieldSettings,
            );
        }

        return $this->getFieldSettings->render(
            $fieldNameRoot,
            $fieldSettings,
        )->content();
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
        return [
            'field_options_ansel' => [
                'label' => 'field_options',
                'group' => 'ansel',
                'settings' => [
                    $this->getDisplaySettings(
                        $data,
                        'ansel[fieldSettings]',
                    ),
                ],
            ],
        ];
    }

    /**
     * @param mixed $data
     *
     * @return mixed[]
     */
    public function grid_display_settings($data): array
    {
        return [
            'field_options' => [
                $this->getDisplaySettings(
                    $data,
                    'ansel[fieldSettings]',
                ),
            ],
        ];
    }

    /**
     * @param mixed $data
     *
     * @return mixed[]
     */
    public function var_display_settings($data): array
    {
        return [
            'field_options_ansel' => [
                'label' => 'field_options',
                'group' => 'ansel',
                'settings' => [
                    $this->getDisplaySettings(
                        $data,
                        'variable_settings[ansel][fieldSettings]',
                    ),
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
        // Blocks and low variables ignores the validation result so we have to
        // throw an exception if there are errors
        if (
            $this->content_type() === 'blocks' ||
            $this->content_type() === 'low_variables'
        ) {
            $errors = $this->fieldSettingsValidator->validate(
                $this->getFieldSettingsCollection(
                    $data
                ),
            );
            if (count($errors) > 0) {
                $msg = 'Some settings did not validate<br><br><ul>';
                foreach ($errors as $key => $val) {
                    $msg .= '<li>' . $key . ': ' . $val . '</li>';
                }

                $msg .= '</ul>';
                show_error($msg);
            }
        }

        $this->postedSettings = $data;

        return $data;
    }

    /**
     * This is called before validate settings :eyeroll:
     *
     * @param mixed[] $data
     *
     * @return mixed[]
     */
    public function var_save_settings($data): array
    {
        return $this->save_settings(['ansel' => $data]);
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

    /**
     * @param mixed[] $data
     */
    public function grid_validate_settings(array $data): Result
    {
        return $this->validate_settings($data);
    }

    /**
     * @param mixed[] $data
     *
     * @phpstan-ignore-next-line
     */
    public function display_field($data)
    {
        // TODO: Implement display_field() method.
        // dd('TODO: Implement display_field() method.');
        return 'TODO: Implement display_field() method.';
    }

    /**
     * @param mixed[] $data
     *
     * @phpstan-ignore-next-line
     */
    public function var_display_field($data)
    {
        // TODO: Implement display_field() method.
        // dd('TODO: Implement var_display_field() method.');
        return 'TODO: Implement var_display_field() method.';
    }

    /**
     * @param mixed[] $data
     *
     * @phpstan-ignore-next-line
     */
    public function var_save($data)
    {
        // TODO: Implement display_field() method.
        dd('TODO: Implement var_save() method.');
    }

    /**
     * Prevent Low Vars from doing anything on post save
     */
    public function var_post_save(): void
    {
        return;
    }
}
