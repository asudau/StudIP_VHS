<?php

/**
 * Migration adding a sub_type column to the mooc_blocks table.
 *
 * @author Christian Flothmann <christian.flothmann@uos.de>
 */
class AddConfigFieldStartButtonSide extends Migration
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
        $db->exec("ALTER TABLE `intranet_individual_buttons` ADD COLUMN content_side enum('left', 'right') AFTER Button_id");
        $db->exec("UPDATE `intranet_individual_buttons` SET content_side = 'left' WHERE 1");
        $db->exec("ALTER TABLE `intranet_individual_buttons` ADD COLUMN icon_link varchar(255) AFTER icon");
        SimpleORMap::expireTableScheme();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $db = DBManager::get();
        $db->exec('ALTER TABLE `intranet_config` DROP COLUMN content_side');
        $db->exec('ALTER TABLE `intranet_config` DROP COLUMN icon_link');
        SimpleORMap::expireTableScheme();
    }
}