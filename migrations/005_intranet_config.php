<?php


class IntranetConfig extends Migration
{
    public function description () {
        return 'add table for intranet_config';
    }


    public function up () {
        $db = DBManager::get();
        $db->exec("CREATE TABLE IF NOT EXISTS `intranet_config` (
          `Institut_id` varchar(32) NOT NULL,
          `template` text,
          PRIMARY KEY (Institut_id)
        ) ");

        SimpleORMap::expireTableScheme();
    }


    public function down () {


        $db = DBManager::get();
        $db->exec("DROP TABLE intranet_config");
        SimpleORMap::expireTableScheme();

    }


}
