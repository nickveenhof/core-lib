<?php

/*
 * @package     Mautic
 * @copyright   2019 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\Migrations;

use Doctrine\DBAL\Migrations\SkipMigrationException;
use Doctrine\DBAL\Schema\Schema;
use Mautic\CoreBundle\Doctrine\AbstractMauticMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20191017140848 extends AbstractMauticMigration
{
    /**
     * @param Schema $schema
     *
     * @throws SkipMigrationException
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    public function preUp(Schema $schema)
    {
        $smsStatsTable = $schema->getTable(MAUTIC_TABLE_PREFIX.'sms_message_stats');
        if ($smsStatsTable->hasColumn('is_failed')) {
            throw new SkipMigrationException('Schema includes this migration');
        }
    }

    /**
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    public function up(Schema $schema)
    {
        $smsStatsTable = $schema->getTable(MAUTIC_TABLE_PREFIX.'sms_message_stats');
        if (!$smsStatsTable->hasColumn('is_failed')) {
            $this->addSql('ALTER TABLE '.$this->prefix.'sms_message_stats ADD is_failed TINYINT(1) DEFAULT NULL');
            $this->addSql("UPDATE {$this->prefix}sms_message_stats SET is_failed = '0'");
            $this->addSql("CREATE INDEX {$this->prefix}stat_sms_failed_search ON {$this->prefix}sms_message_stats (is_failed)");
        }
    }
}
