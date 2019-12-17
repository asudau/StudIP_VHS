<?php


class IntranetSeminarConfig extends Migration
{
    public function description () {
        return 'add table for intranet_seminar_configuration';
    }


    public function up () {
        $db = DBManager::get();
        $db->exec("CREATE TABLE IF NOT EXISTS `intranet_seminar_config` (
          `seminar_id` varchar(32) NOT NULL,
          `institut_id` varchar(32) NOT NULL,
          `show_news` boolean,
          `news_caption` text,
          `use_files` boolean,
          `files_caption` text,
          `add_instuser_as` varchar(6),
          PRIMARY KEY (seminar_id, institut_id)
        ) ");

        SimpleORMap::expireTableScheme();
    }


    public function down () {


        $db = DBManager::get();
        $db->exec("DROP TABLE intranet_seminar_config");
        SimpleORMap::expireTableScheme();

    }


}
