<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads\Response;

use BuzzingPixel\Ansel\Shared\ImageMimeTypes;
use BuzzingPixel\Ansel\Shared\Images\ImageSizeChecker;
use BuzzingPixel\Ansel\Translations\TranslatorContract;
use Exception;
use League\MimeTypeDetection\FinfoMimeTypeDetector;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\UploadedFile;
use Throwable;

class ValidateRequest
{
    private ImageMimeTypes $imageMimeTypes;

    private TranslatorContract $translator;

    private ImageSizeChecker $imageSizeChecker;

    private FinfoMimeTypeDetector $mimeTypeDetector;

    private ValidateUploadKeyFromRequest $validateUploadKey;

    public function __construct(
        ImageMimeTypes $imageMimeTypes,
        TranslatorContract $translator,
        ImageSizeChecker $imageSizeChecker,
        FinfoMimeTypeDetector $mimeTypeDetector,
        ValidateUploadKeyFromRequest $validateUploadKey
    ) {
        $this->imageMimeTypes    = $imageMimeTypes;
        $this->translator        = $translator;
        $this->imageSizeChecker  = $imageSizeChecker;
        $this->mimeTypeDetector  = $mimeTypeDetector;
        $this->validateUploadKey = $validateUploadKey;
    }

    public function validate(
        ServerRequestInterface $request
    ): ValidationResponse {
        try {
            return $this->internalValidate($request);
        } catch (Throwable $exception) {
            return new ValidationResponse(
                false,
                $this->translator->getLine(
                    'invalid_upload_request',
                ),
            );
        }
    }

    /**
     * @throws Exception
     */
    public function internalValidate(
        ServerRequestInterface $request
    ): ValidationResponse {
        if (! $this->validateUploadKey->validate($request)) {
            return new ValidationResponse(
                false,
                $this->translator->getLine(
                    'invalid_upload_request',
                ),
            );
        }

        $uploadedImage = $request->getUploadedFiles()['image'] ?? null;

        if (! ($uploadedImage instanceof UploadedFile)) {
            return new ValidationResponse(false);
        }

        $mimeType = (string) $this->mimeTypeDetector->detectMimeTypeFromBuffer(
            $uploadedImage->getStream()->getContents()
        );

        $isValidImageMime = $this->imageMimeTypes->mimeStringIsValidImage(
            $mimeType,
        );

        if (! $isValidImageMime) {
            return new ValidationResponse(
                false,
                $this->translator->getLine(
                    'invalid_file_type',
                ) . ': ' . $mimeType,
            );
        }

        $body = (array) $request->getParsedBody();

        $minWidth = (int) ($body['minWidth'] ?? 0);

        $minHeight = (int) ($body['minHeight'] ?? 0);

        $imageSize = $this->imageSizeChecker->getImageSize(
            $uploadedImage->getFilePath(),
        );

        if (
            $imageSize->width() < $minWidth &&
            $imageSize->height() < $minHeight
        ) {
            return new ValidationResponse(
                false,
                $this->translator->getLineWithReplacements(
                    'min_image_dimensions_not_met_width_and_height',
                    [
                        '{{minWidth}}' => (string) $minWidth,
                        '{{minHeight}}' => (string) $minHeight,
                    ],
                ),
            );
        }

        if ($imageSize->width() < $minWidth) {
            return new ValidationResponse(
                false,
                $this->translator->getLineWithReplacements(
                    'min_image_dimensions_not_met_width_only',
                    ['{{minWidth}}' => (string) $minWidth],
                ),
            );
        }

        if ($imageSize->height() < $minHeight) {
            return new ValidationResponse(
                false,
                $this->translator->getLineWithReplacements(
                    'min_image_dimensions_not_met_height_only',
                    ['{{minHeight}}' => (string) $minHeight],
                ),
            );
        }

        return new ValidationResponse(
            true,
            '',
            $uploadedImage,
        );
    }
}
