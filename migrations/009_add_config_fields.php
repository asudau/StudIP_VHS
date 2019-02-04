<?php

/**
 * Migration adding a sub_type column to the mooc_blocks table.
 *
 * @author Christian Flothmann <christian.flothmann@uos.de>
 */
class AddConfigFields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function description()
    {
        return 'Adds fields for intranet config';
    }

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $db = DBManager::get();
        $db->exec('ALTER TABLE `intranet_seminar_config` ADD COLUMN news_position INT AFTER news_caption');
        $db->exec('ALTER TABLE `intranet_seminar_config` ADD COLUMN news_sidebar BOOLEAN AFTER news_position');
        $db->exec('ALTER TABLE `intranet_seminar_config` ADD COLUMN files_position INT AFTER files_caption');
        SimpleORMap::expireTableScheme();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $db = DBManager::get();
        $db->exec('ALTER TABLE `intranet_seminar_config` DROP COLUMN news_position');
        $db->exec('ALTER TABLE `intranet_seminar_config` DROP COLUMN news_sidebar');
        $db->exec('ALTER TABLE `intranet_seminar_config` DROP COLUMN files_position');
        SimpleORMap::expireTableScheme();
    }
}