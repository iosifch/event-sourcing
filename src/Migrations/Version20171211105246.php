<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171211105246 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $table = $schema->createTable('customer_event');
        $table->addColumn('customer_id', Type::INTEGER);
        $table->addColumn('event_name', Type::STRING);
        $table->addColumn('data', Type::TEXT);
        $table->addColumn('date_created', Type::DATETIME, [
            'Default' => 'CURRENT_TIMESTAMP'
        ]);
    }

    public function down(Schema $schema)
    {
        $schema->dropTable('customer_event');
    }
}
