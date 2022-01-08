<?php

declare(strict_types=1);

namespace BuzzingPixel\Ansel\Migrate;

use BuzzingPixel\Ansel\Shared\CraftQueryBuilderFactory;
use craft\db\Connection as DbConnection;
use Throwable;
use yii\db\Exception;

use function array_map;
use function get_class;

class MigrationsTableCraft implements MigrationsTableContract
{
    private DbConnection $db;

    private CraftQueryBuilderFactory $queryBuilderFactory;

    public function __construct(
        DbConnection $db,
        CraftQueryBuilderFactory $queryBuilderFactory
    ) {
        $this->db                  = $db;
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    /**
     * @inheritDoc
     */
    public function getRunMigrations(): array
    {
        if (! $this->db->tableExists('{{%ansel_migrations}}')) {
            return [];
        }

        $runs = $this->queryBuilderFactory->create()->select('*')
            ->from('{{%ansel_migrations}}')
            ->all();

        return array_map(
            static function ($result): RunMigration {
                return new RunMigration($result['migration']);
            },
            $runs,
        );
    }

    /**
     * @throws Exception
     */
    public function addMigration(MigrationContract $migration): void
    {
        $this->db->createCommand()->insert(
            '{{%ansel_migrations}}',
            ['migration' => get_class($migration)],
            false,
        )->execute();
    }

    public function removeMigration(MigrationContract $migration): void
    {
        try {
            // This doesn't seem to effing work………… thanks Yii
            if (! $this->db->tableExists('{{%ansel_migrations}}')) {
                return;
            }

            $this->db->createCommand()->delete(
                '{{%ansel_migrations}}',
                '`migration` = :migration',
                [':migration' => get_class($migration)],
            )->execute();

            // @codeCoverageIgnoreStart
        } catch (Throwable $e) {
        }
        // @codeCoverageIgnoreEnd
    }
}
