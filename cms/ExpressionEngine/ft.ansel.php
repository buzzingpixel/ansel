<?php

declare(strict_types=1);

/** @noinspection PhpIllegalPsrClassPathInspection */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
// phpcs:disable Squiz.Classes.ClassFileName.NoMatch
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
// phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
// phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
use BuzzingPixel\Ansel\Field\Settings\ExpressionEngine\GetFieldSettings;
use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollection;
use BuzzingPixel\AnselConfig\ContainerManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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
        return [
            'field_options_ansel' => [
                'label' => 'field_options',
                'group' => 'ansel',
                'settings' => [
                    $this->getFieldSettings->render(
                        new FieldSettingsCollection(),
                    )->content(),
                ],
            ],
        ];
    }
}
