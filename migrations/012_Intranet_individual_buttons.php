<?php


class IntranetIndividualButtons extends Migration
{
    public function description () {
        return 'add table for individual intranet buttion';
    }


    public function up () {
        $db = DBManager::get();
        $db->exec("CREATE TABLE IF NOT EXISTS `intranet_individual_buttons` (
          `Institut_id` varchar(32) NOT NULL,
          `Button_id` varchar(32) NOT NULL,
          `position` int(11),
          `target` text, //enum('link', 'dialog'),
          `text` varchar(255),
          `tooltip` varchar(255),
          `icon` varchar(255),
          PRIMARY KEY (Button_id)
        ) ");

        $db->exec("CREATE TABLE IF NOT EXISTS `intranet_button_content` (
          `Button_id` varchar(32) NOT NULL,
          `position` int(11),
          `target` varchar(255),
          `icon` varchar(255),
          PRIMARY KEY (Institut_id)
        ) ");

        SimpleORMap::expireTableScheme();
    }


    public function down () {


        $db = DBManager::get();
        $db->exec("DROP TABLE intranet_individual_buttons");
        $db->exec("DROP TABLE intranet_button_content");
        SimpleORMap::expireTableScheme();

    }


}

