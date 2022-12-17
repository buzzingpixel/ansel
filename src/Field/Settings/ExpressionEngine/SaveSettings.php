<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings\ExpressionEngine;

use BuzzingPixel\Ansel\Field\Settings\FieldSettingsCollectionValidatorContract;
use BuzzingPixel\Ansel\RequestCache\RequestCachePool;
use BuzzingPixel\Ansel\Shared\EE\EeGlobalFunctions;

use function count;

class SaveSettings
{
    private RequestCachePool $requestCache;

    private EeGlobalFunctions $eeGlobalFunctions;

    private FieldSettingsFromRaw $fieldSettingsFromRaw;

    private FieldSettingsCollectionValidatorContract $fieldSettingsValidator;

    public function __construct(
        RequestCachePool $requestCache,
        EeGlobalFunctions $eeGlobalFunctions,
        FieldSettingsFromRaw $fieldSettingsFromRaw,
        FieldSettingsCollectionValidatorContract $fieldSettingsValidator
    ) {
        $this->requestCache           = $requestCache;
        $this->eeGlobalFunctions      = $eeGlobalFunctions;
        $this->fieldSettingsFromRaw   = $fieldSettingsFromRaw;
        $this->fieldSettingsValidator = $fieldSettingsValidator;
    }

    /**
     * @param mixed[] $data
     *
     * @return mixed[]
     */
    public function save(array $data, string $contentType): array
    {
        // Blocks and low variables ignores the validation result so we have to
        // throw an exception if there are errors
        if (
            $contentType === 'blocks' ||
            $contentType === 'low_variables'
        ) {
            $errors = $this->fieldSettingsValidator->validate(
                $this->fieldSettingsFromRaw->get(
                    $data
                ),
            );

            if (count($errors) > 0) {
                $msg = 'Some settings did not validate<br><br><ul>';

                foreach ($errors as $key => $val) {
                    $msg .= '<li>' . $key . ': ' . $val . '</li>';
                }

                $msg .= '</ul>';

                $this->eeGlobalFunctions->showError($msg);
            }
        }

        $this->requestCache->save($this->requestCache->createItem(
            'anselPostedSettings',
            $data,
        ));

        $anselData = $data['ansel'] ?? [];

        return ['ansel' => $anselData];
    }
}
