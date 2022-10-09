<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\ImageManipulation;

use BuzzingPixel\Ansel\Shared\Php\InternalFunctions;
use Intervention\Image\ImageManager;
use SplFileInfo;

class Manipulator
{
    private ImageManager $imageManager;

    private ManipulatorCrop $crop;

    private ManipulatorUpscale $upscale;

    private ManipulatorResize $resize;

    private InternalFunctions $internalFunctions;

    public function __construct(
        ImageManager $imageManager,
        ManipulatorCrop $crop,
        ManipulatorUpscale $upscale,
        ManipulatorResize $resize,
        InternalFunctions $internalFunctions
    ) {
        $this->imageManager      = $imageManager;
        $this->crop              = $crop;
        $this->upscale           = $upscale;
        $this->resize            = $resize;
        $this->internalFunctions = $internalFunctions;
    }

    /**
     * @param SplFileInfo $source         The source file to manipulate from
     * @param Parameters  $parameters     The parameters for manipulating the
     *                                    image
     * @param string      $targetPath     The directory to place the manipulated
     *                                    image in
     * @param string      $targetBasename The base name (without extension) to
     *                                    name the manipulated image file. The
     *                                    extension will be set automatically
     *                                    based on the image type
     */
    public function runManipulation(
        SplFileInfo $source,
        Parameters $parameters,
        string $targetPath,
        string $targetBasename
    ): SplFileInfo {
        $image = $this->imageManager->make($source->getPathname());

        $image = $this->crop->runCrop(
            $image,
            $parameters
        );

        $image = $this->upscale->runUpscale(
            $image,
            $parameters
        );

        $image = $this->resize->runResize(
            $image,
            $parameters
        );

        $ext = $source->getExtension();

        if ($parameters->getOutputType() !== null) {
            $ext = $parameters->getOutputType()->getValue();
        }

        if ($ext === 'jpeg') {
            $ext = 'jpg';
        }

        $this->internalFunctions->mkdirIfNotExists($targetPath);

        $fullSavePath = $targetPath . '/' . $targetBasename . '.' . $ext;

        $image->save(
            $targetPath . '/' . $targetBasename . '.' . $ext,
            90,
        );

        return new SplFileInfo($fullSavePath);
    }
}
