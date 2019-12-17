<?php
class AddSystemTabsTables extends Migration {

    public function description () {
        return 'create tables for the SeminarTabs-plugin';
    }

    public function up () {
        $db = DBManager::get();
        $db->exec("CREATE  TABLE IF NOT EXISTS `system_tabs` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `tab` VARCHAR(64) NULL ,
            `title` VARCHAR(255) NULL ,
            PRIMARY KEY (`id`)
        )");
		
		
        SimpleORMap::expireTableScheme();
    }

    public function down () {
		DBManager::get()->exec("DROP TABLE system_tabs");
        SimpleORMap::expireTableScheme();
    }
}
