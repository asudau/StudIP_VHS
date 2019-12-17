<?php

class IntranetDatesTable extends Migration
{
    public function description () {
        return 'add Table for Dates for Intranet';
    }


    public function up () {
        $db = DBManager::get();
        $db->exec("CREATE TABLE IF NOT EXISTS `intranet_dates` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `user_id` varchar(64) NOT NULL,
          `begin` varchar(16) DEFAULT NULL,
          `end` varchar(16) DEFAULT NULL,
          `notice` mediumtext,
          `type` VARCHAR(32) NOT NULL,
          `chdate` int(11) DEFAULT NULL,
          `mkdate` int(11) DEFAULT NULL,
          PRIMARY KEY (`id`)
        )");
           
        SimpleORMap::expireTableScheme();
    }


    public function down () {
        
        DBManager::get()->exec("DROP TABLE intranet_dates");
        SimpleORMap::expireTableScheme();
    }
    
}
