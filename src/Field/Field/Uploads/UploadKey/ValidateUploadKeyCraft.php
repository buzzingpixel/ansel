<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads\UploadKey;

use BuzzingPixel\Ansel\Shared\ClockContract;
use BuzzingPixel\Ansel\Shared\CraftQueryBuilderFactory;
use craft\db\Connection as DbConnection;
use yii\db\Exception;

class ValidateUploadKeyCraft implements ValidateUploadKeyContract
{
    private DbConnection $db;

    private ClockContract $clock;

    private CraftQueryBuilderFactory $queryBuilderFactory;

    public function __construct(
        DbConnection $db,
        ClockContract $clock,
        CraftQueryBuilderFactory $queryBuilderFactory
    ) {
        $this->db                  = $db;
        $this->clock               = $clock;
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    /**
     * @throws Exception
     */
    public function validate(string $key): bool
    {
        $this->garbageCollectExpiredKeys();

        return $this->validateKey($key);
    }

    /**
     * @throws Exception
     */
    private function garbageCollectExpiredKeys(): void
    {
        $this->db->createCommand()->delete(
            '{{%ansel_upload_keys}}',
            'expires < :time',
            [':time' => $this->clock->now()->getTimestamp()],
        )->execute();
    }

    private function validateKey(string $key): bool
    {
        $count = (int) $this->queryBuilderFactory->create()
            ->from('{{%ansel_upload_keys}}')
            ->where('`key` = :key', [':key' => $key])
            ->count();

        return $count > 0;
    }
}
