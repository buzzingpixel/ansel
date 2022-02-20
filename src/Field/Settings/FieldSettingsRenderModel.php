<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Settings;

class FieldSettingsRenderModel
{
    private FieldSettingsCollection $fieldSettings;

    private bool $hideSourceSaveInstructions;

    private string $uploadSaveDirExplanation;

    private string $uploadSaveDirHide;

    private string $uploadSaveDirExplainUpload;

    private string $uploadSaveDirExplainSave;

    private string $uploadSaveDirExplainDifferentSources;

    private LocationSelectionCollection $locationSelectionCollection;

    public function __construct(
        FieldSettingsCollection $fieldSettings,
        bool $hideSourceSaveInstructions,
        string $uploadSaveDirExplanation,
        string $uploadSaveDirHide,
        string $uploadSaveDirExplainUpload,
        string $uploadSaveDirExplainSave,
        string $uploadSaveDirExplainDifferentSources,
        LocationSelectionCollection $locationSelectionCollection
    ) {
        $this->hideSourceSaveInstructions           = $hideSourceSaveInstructions;
        $this->uploadSaveDirExplanation             = $uploadSaveDirExplanation;
        $this->uploadSaveDirHide                    = $uploadSaveDirHide;
        $this->uploadSaveDirExplainUpload           = $uploadSaveDirExplainUpload;
        $this->uploadSaveDirExplainSave             = $uploadSaveDirExplainSave;
        $this->uploadSaveDirExplainDifferentSources = $uploadSaveDirExplainDifferentSources;
        $this->fieldSettings                        = $fieldSettings;
        $this->locationSelectionCollection          = $locationSelectionCollection;
    }

    public function fieldSettings(): FieldSettingsCollection
    {
        return $this->fieldSettings;
    }

    public function hideSourceSaveInstructions(): bool
    {
        return $this->hideSourceSaveInstructions;
    }

    public function uploadSaveDirExplanation(): string
    {
        return $this->uploadSaveDirExplanation;
    }

    public function uploadSaveDirHide(): string
    {
        return $this->uploadSaveDirHide;
    }

    public function uploadSaveDirExplainUpload(): string
    {
        return $this->uploadSaveDirExplainUpload;
    }

    public function uploadSaveDirExplainSave(): string
    {
        return $this->uploadSaveDirExplainSave;
    }

    public function uploadSaveDirExplainDifferentSources(): string
    {
        return $this->uploadSaveDirExplainDifferentSources;
    }

    public function locationSelectionCollection(): LocationSelectionCollection
    {
        return $this->locationSelectionCollection;
    }
}
