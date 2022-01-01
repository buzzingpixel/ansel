<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;
use CI_DB_forge;

class E0007AddUploadKeysTable implements MigrationContract
{
    private CI_DB_forge $dbForge;

    private EeQueryBuilderFactory $queryBuilderFactory;

    public function __construct(
        CI_DB_forge $dbForge,
        EeQueryBuilderFactory $queryBuilderFactory
    ) {
        $this->dbForge             = $dbForge;
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    public function for(): string
    {
        return self::EE;
    }

    public function up(): bool
    {
        $this->dbForge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'key' => ['type' => 'TEXT'],
            'created' => [
                'default' => 0,
                'type' => 'INT',
                'unsigned' => true,
            ],
            'expires' => [
                'default' => 0,
                'type' => 'INT',
                'unsigned' => true,
            ],
        ]);

        /** @phpstan-ignore-next-line */
        $this->dbForge->add_key('id', true);

        $this->dbForge->create_table(
            'ansel_upload_keys',
            true
        );

        return true;
    }

    public function down(): bool
    {
        $tableExists = $this->queryBuilderFactory->create()->table_exists(
            'ansel_upload_keys'
        );

        if (! $tableExists) {
            return true;
        }

        $this->dbForge->drop_table('ansel_upload_keys');

        return true;
    }
}
