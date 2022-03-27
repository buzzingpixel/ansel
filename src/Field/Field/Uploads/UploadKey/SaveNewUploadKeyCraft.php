<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Field\Field\Uploads\UploadKey;

use craft\db\Connection as DbConnection;
use yii\db\Exception;

class SaveNewUploadKeyCraft implements SaveNewUploadKeyContract
{
    private DbConnection $db;

    public function __construct(DbConnection $db)
    {
        $this->db = $db;
    }

    /**
     * @throws Exception
     */
    public function save(UploadKeyRecord $record): void
    {
        $this->db->createCommand()->insert(
            '{{%ansel_upload_keys}}',
            [
                'key' => $record->key,
                'expires' => $record->expires,
            ],
        )->execute();
    }
}
