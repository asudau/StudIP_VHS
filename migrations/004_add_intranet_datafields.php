<?php

/**
 * @author Annelene Sudau <asudaul@uos.de>
 */
class AddIntranetDatafields extends Migration
{

    public function description()
    {
        return 'add datafields for intranet attributes';
    }

    public function up()
    {
        DBManager::get()->exec(
            "INSERT INTO `datafields` (`datafield_id`, `name`, `object_type`,
                `object_class`, `edit_perms`, `view_perms`, `priority`,
                `mkdate`, `chdate`, `type`, `typeparam`, `is_required`, `description`)
            VALUES (md5('Eigener Intranetbereich'), 'Eigener Intranetbereich', 2,
                NULL, 6, 6, '0', NULL, NULL, 1, '', '0', 'Einrichtung um Intranetbereich erweitern')"
        );

        DBManager::get()->exec(
            "INSERT INTO `datafields` (`datafield_id`, `name`, `object_type`,
                `object_class`, `edit_perms`, `view_perms`, `priority`,
                `mkdate`, `chdate`, `type`, `typeparam`, `is_required`, `description`)
            VALUES (md5('Intranet-Veranstaltung'), 'Intranet-Veranstaltung', 1,
                NULL, 6, 6, '0', NULL, NULL, 1, '', '0', 'Veranstaltung zu Intranetbereich hinzufügen')"
        );
    }

    public function down()
    {
        DBManager::get()->exec(
            "DELETE FROM datafields WHERE datafield_id "
                . "IN(md5('Eigener Intranetbereich'), md5('Intranet-Veranstaltung'))"
        );
    }
}
