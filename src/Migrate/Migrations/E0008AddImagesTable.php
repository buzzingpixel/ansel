<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate\Migrations;

use BuzzingPixel\Ansel\Migrate\MigrationContract;
use BuzzingPixel\Ansel\Shared\EeQueryBuilderFactory;
use CI_DB_forge;

class E0008AddImagesTable implements MigrationContract
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
            'ansel_id' => [
                'default' => '',
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'site_id' => [
                'default' => 1,
                'type' => 'TINYINT',
                'unsigned' => true,
            ],
            'source_id' => [
                'default' => 0,
                'type' => 'INT',
                'unsigned' => true,
            ],
            'content_id' => [
                'default' => 0,
                'type' => 'INT',
                'unsigned' => true,
            ],
            'field_id' => [
                'default' => 0,
                'type' => 'MEDIUMINT',
                'unsigned' => true,
            ],
            'content_type' => [
                'default' => 'channel',
                'null' => false,
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'row_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'default' => 0,
            ],
            'col_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'default' => 0,
            ],
            'file_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'original_location_type' => [
                'default' => 'ee',
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'original_file_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'save_location_type' => [
                'default' => 'ee',
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'save_location_id' => [
                'default' => '',
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'filename' => ['type' => 'TEXT'],
            'extension' => [
                'default' => '',
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'original_extension' => [
                'default' => '',
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'filesize' => [
                'default' => 0,
                'type' => 'INT',
                'unsigned' => true,
            ],
            'original_filesize' => [
                'default' => 0,
                'type' => 'INT',
                'unsigned' => true,
            ],
            'width' => [
                'default' => 0,
                'type' => 'INT',
                'unsigned' => true,
            ],
            'height' => [
                'default' => 0,
                'type' => 'INT',
                'unsigned' => true,
            ],
            'x' => [
                'default' => 0,
                'type' => 'INT',
                'unsigned' => true,
            ],
            'y' => [
                'default' => 0,
                'type' => 'INT',
                'unsigned' => true,
            ],
            'focal_x' => [
                'default' => 0,
                'type' => 'INT',
                'unsigned' => true,
            ],
            'focal_y' => [
                'default' => 0,
                'type' => 'INT',
                'unsigned' => true,
            ],
            'title' => [
                'default' => '',
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'caption' => [
                'default' => '',
                'constraint' => 255,
                'type' => 'VARCHAR',
            ],
            'member_id' => [
                'default' => 0,
                'type' => 'INT',
                'unsigned' => true,
            ],
            'position' => [
                'default' => 1,
                'type' => 'TINYINT',
                'unsigned' => true,
            ],
            'cover' => [
                'constraint' => 1,
                'default' => 0,
                'type' => 'TINYINT',
                'unsigned' => true,
            ],
            'upload_date' => [
                'default' => 0,
                'type' => 'INT',
                'unsigned' => true,
            ],
            'modify_date' => [
                'default' => 0,
                'type' => 'INT',
                'unsigned' => true,
            ],
            'disabled' => [
                'constraint' => 1,
                'default' => 0,
                'type' => 'TINYINT',
                'unsigned' => true,
            ],
        ]);

        /** @phpstan-ignore-next-line */
        $this->dbForge->add_key('id', true);

        $this->dbForge->create_table(
            'ansel_images',
            true
        );

        return true;
    }

    public function down(): bool
    {
        $tableExists = $this->queryBuilderFactory->create()->table_exists(
            'ansel_images'
        );

        if (! $tableExists) {
            return true;
        }

        $this->dbForge->drop_table('ansel_images');

        return true;
    }
}
