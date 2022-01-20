<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\License;

use LogicException;

use function in_array;

class LicenseResult
{
    public const STATUS_VALID = 'valid';

    public const STATUS_TRIAL = 'trial';

    public const STATUS_EXPIRED = 'expired';

    public const STATUS_INVALID_TRIAL = 'invalid_trial';

    public const STATUS_INVALID_EXPIRED = 'invalid_expired';

    public const STATUSES = [
        self::STATUS_TRIAL,
        self::STATUS_EXPIRED,
        self::STATUS_VALID,
        self::STATUS_INVALID_TRIAL,
        self::STATUS_INVALID_EXPIRED,
    ];

    public const LOCKOUT_STATUSES = [
        self::STATUS_EXPIRED,
        self::STATUS_INVALID_EXPIRED,
    ];

    private string $status;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(string $status)
    {
        if (
            ! in_array(
                $status,
                self::STATUSES,
                true,
            )
        ) {
            throw new LogicException('$status must be pre-defined');
        }

        $this->status = $status;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function isValid(): bool
    {
        return $this->status() === self::STATUS_VALID;
    }

    public function isTrial(): bool
    {
        return $this->status() === self::STATUS_TRIAL;
    }

    public function isExpired(): bool
    {
        return $this->status() === self::STATUS_EXPIRED;
    }

    public function isTrialWithInvalidLicenseKey(): bool
    {
        return $this->status() === self::STATUS_INVALID_TRIAL;
    }

    public function isExpiredWithInvalidLicenseKey(): bool
    {
        return $this->status() === self::STATUS_INVALID_EXPIRED;
    }

    public function shouldLockOut(): bool
    {
        return in_array(
            $this->status(),
            self::LOCKOUT_STATUSES,
            true,
        );
    }
}
