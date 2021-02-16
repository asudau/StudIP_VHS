<?php

/**
 * @author Annelene Sudau <asudau@uos.de>
 */
class AddCoursebeginDatafield extends Migration
{

    public function description()
    {
        return 'add datafields for each sem_class for removing course tab-navigation and selecting style of overview-page';
    }

    public function up()
    {
        $db = DBManager::get();
        
        $stm = $db->prepare(
            "INSERT INTO `datafields` (`datafield_id`, `name`, `object_type`,
                `object_class`, `edit_perms`, `view_perms`, `priority`,
                `mkdate`, `chdate`, `type`, `typeparam`, `is_required`, `description`)
            VALUES (md5('course_begin'), 'course begin', 1,
                NULL, 4, 5, '0', NULL, NULL, 3, '', '0', 'Kursstart/Zugriff für Nutzer')"
        );

        $stm->execute();
           
    }

    public function down()
    {
        DBManager::get()->exec(

            "DELETE FROM datafields WHERE datafield_id "
                . "IN(md5('course_begin'))"
        );
    }
}
