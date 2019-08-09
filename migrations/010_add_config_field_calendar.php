<?php

/**
 * Migration adding a sub_type column to the mooc_blocks table.
 *
 * @author Christian Flothmann <christian.flothmann@uos.de>
 */
class AddConfigFieldCalendar extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function description()
    {
        return 'Adds field for intranet config which seminar to use for intern calendar';
    }

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $db = DBManager::get();
        $db->exec('ALTER TABLE `intranet_config` ADD COLUMN calendar_seminar VARCHAR(32) AFTER seminare');
        SimpleORMap::expireTableScheme();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $db = DBManager::get();
        $db->exec('ALTER TABLE `intranet_config` DROP COLUMN calendar_seminar');
        SimpleORMap::expireTableScheme();
    }
}