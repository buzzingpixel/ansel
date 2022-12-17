<?php
/** @noinspection PhpIllegalPsrClassPathInspection */

declare(strict_types=1);

use BuzzingPixel\Ansel\Field\Field\EeContentType;
use BuzzingPixel\Ansel\Field\Field\FieldMetaEe;
use BuzzingPixel\Ansel\Field\Field\GetEeField\GetEeFieldAction;
use BuzzingPixel\Ansel\Field\Field\PostDataImageUrlHandler;
use BuzzingPixel\Ansel\Field\Field\PostedFieldData\PostedData;
use BuzzingPixel\Ansel\Field\Field\SaveEeField\SaveFieldAction;
use BuzzingPixel\Ansel\Field\Field\SaveEeField\SavePayload;
use BuzzingPixel\Ansel\Field\Field\Validate\ValidatedFieldError;
use BuzzingPixel\Ansel\Field\Field\Validate\ValidateFieldAction;
use BuzzingPixel\Ansel\Field\Settings\ExpressionEngine\FieldSettingsFromRaw;
use BuzzingPixel\Ansel\Field\Settings\ExpressionEngine\GetDisplaySettings;
use BuzzingPixel\Ansel\Field\Settings\ExpressionEngine\SaveSettings;
use BuzzingPixel\Ansel\Field\Settings\ExpressionEngine\ValidateSettings;
use BuzzingPixel\AnselConfig\ContainerManager;
use ExpressionEngine\Service\Validation\Result;
use Psr\Cache\InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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
     * @var mixed
     */
    public $var_id;

    /**
     * Error Message for Low Variables (so PHPStorm doesn’t show errors)
     *
     * @var mixed
     */
    public $error_msg;

    /** Set field type as tag pair */
    public bool $has_array_data = true;

    /**
     * Required info for EE fieldtype
     *
     * @var string[]
     */
    public array $info = [
        'name' => ANSEL_NAME,
        'version' => ANSEL_VER,
    ];

    private GetEeFieldAction $getFieldAction;

    private ValidateFieldAction $validateFieldAction;

    private SaveFieldAction $saveEeFieldAction;

    private EE_Input $input;

    private PostDataImageUrlHandler $postDataImageUrlHandler;

    private FieldSettingsFromRaw $fieldSettingsFromRaw;

    private GetDisplaySettings $getDisplaySettings;

    private SaveSettings $saveSettings;

    private ValidateSettings $validateSettings;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        parent::__construct();

        $container = (new ContainerManager())->container();

        /** @phpstan-ignore-next-line */
        $this->getFieldAction = $container->get(GetEeFieldAction::class);

        /** @phpstan-ignore-next-line */
        $this->validateFieldAction = $container->get(
            ValidateFieldAction::class
        );

        /** @phpstan-ignore-next-line */
        $this->saveEeFieldAction = $container->get(SaveFieldAction::class);

        /** @phpstan-ignore-next-line */
        $this->input = $container->get(EE_Input::class);

        /** @phpstan-ignore-next-line */
        $this->postDataImageUrlHandler = $container->get(
            PostDataImageUrlHandler::class,
        );

        /** @phpstan-ignore-next-line */
        $this->fieldSettingsFromRaw = $container->get(
            FieldSettingsFromRaw::class,
        );

        /** @phpstan-ignore-next-line */
        $this->getDisplaySettings = $container->get(GetDisplaySettings::class);

        /** @phpstan-ignore-next-line */
        $this->saveSettings = $container->get(SaveSettings::class);

        /** @phpstan-ignore-next-line */
        $this->validateSettings = $container->get(ValidateSettings::class);
    }

    /**
     * @param string $name
     *
     * @phpstan-ignore-next-line
     */
    public function accepts_content_type($name): bool
    {
        return EeContentType::isValid($name);
    }

    /**
     * @param mixed $data
     *
     * @return mixed[]
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws InvalidArgumentException
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
                    $this->getDisplaySettings->get(
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
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws InvalidArgumentException
     *
     * @phpstan-ignore-next-line
     */
    public function grid_display_settings($data): array
    {
        return [
            'field_options' => [
                $this->getDisplaySettings->get(
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
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws InvalidArgumentException
     *
     * @phpstan-ignore-next-line
     */
    public function var_display_settings($data): array
    {
        return [
            'field_options_ansel' => [
                'label' => 'field_options',
                'group' => 'ansel',
                'settings' => [
                    $this->getDisplaySettings->get(
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
        return $this->saveSettings->save(
            $data,
            /** @phpstan-ignore-next-line */
            (string) $this->content_type()
        );
    }

    /**
     * This is called before validate settings :eyeroll:
     *
     * @param mixed[] $data
     *
     * @return mixed[]
     *
     * @noinspection PhpMissingParamTypeInspection
     */
    public function var_save_settings($data): array
    {
        return $this->saveSettings->save(
            $data,
            /** @phpstan-ignore-next-line */
            (string) $this->content_type()
        );
    }

    /**
     * @param mixed[] $data
     *
     * @phpstan-ignore-next-line
     */
    public function validate_settings($data): Result
    {
        return $this->validateSettings->validate($data);
    }

    /**
     * @param mixed[] $data
     */
    public function grid_validate_settings(array $data): Result
    {
        return $this->validateSettings->validate($data);
    }

    /**
     * @param mixed $value
     *
     * @throws ContainerExceptionInterface
     * @throws LoaderError
     * @throws NotFoundExceptionInterface
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function display_field($value): string
    {
        // TODO: License check

        $fieldSettings = $this->fieldSettingsFromRaw->get(
            $this->settings,
            true
        );

        return $this->getFieldAction->render(
            $fieldSettings,
            new FieldMetaEe(
                (int) $this->field_id,
                (string) $this->field_name,
                new EeContentType($this->content_type()),
                // We don't have enough info to get this here (thanks EE) and we
                // don't need it (thankfully)
                0,
                /** @phpstan-ignore-next-line */
                (int) $this->content_id(),
            ),
            $value,
        );
    }

    // public function grid_display_field()

    /**
     * @param mixed[] $data
     *
     * @throws ContainerExceptionInterface
     * @throws LoaderError
     * @throws NotFoundExceptionInterface
     * @throws RuntimeError
     * @throws SyntaxError
     *
     * @noinspection PhpMissingParamTypeInspection
     */
    public function var_display_field($data): string
    {
        return $this->display_field($data);
    }

    public function var_wide(): bool
    {
        return true;
    }

    /**
     * @param mixed $data
     *
     * @return bool|string
     */
    public function validate($data)
    {
        $fieldSettings = $this->fieldSettingsFromRaw->get(
            $this->settings,
            true
        );

        $data = is_array($data) ? $data : [];

        $validatedFieldResult = $this->validateFieldAction->validate(
            $fieldSettings,
            PostedData::fromArray($data)
        );

        if ($validatedFieldResult->hasNoErrors()) {
            return true;
        }

        return implode(
            '<br>',
            $validatedFieldResult->map(static fn (
                ValidatedFieldError $error
            ) => $error->errorMsg())
        );
    }

    /**
     * @param mixed $data
     */
    public function save($data): string
    {
        if (! is_array($data)) {
            return '';
        }

        return (string) json_encode(
            $this->postDataImageUrlHandler->scrub($data)
        );
    }

    /**
     * @param string $data
     *
     * @phpstan-ignore-next-line
     */
    public function post_save($data): void
    {
        $data = json_decode($data, true);

        $data = is_array($data) ? $data : [];

        $fieldSettings = $this->fieldSettingsFromRaw->get(
            $this->settings,
            true
        );

        $this->saveEeFieldAction->save(
            new SavePayload(
                PostedData::fromArray($data),
                $fieldSettings,
                new FieldMetaEe(
                    (int) $this->field_id,
                    (string) $this->field_name,
                    new EeContentType($this->content_type()),
                    (int) $this->input->get_post('channel_id'),
                    /** @phpstan-ignore-next-line */
                    (int) $this->content_id(),
                ),
            ),
        );
    }

    /**
     * @param mixed[] $data
     */
    public function var_save(array $data): void
    {
        // TODO: Implement var_save() method.
        dd('TODO: Implement var_save() method.');
    }

    // public function grid_post_save()

    /**
     * Prevent Low Vars from doing anything on post save
     */
    public function var_post_save(): void
    {
        return;
    }

    // public function delete()

    // public function grid_delete()

    // public function var_delete()

    // public function replace_tag()

    // public function grid_replace_tag()

    // public function var_replace_tag()
}
