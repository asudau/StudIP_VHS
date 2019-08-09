<?php


class DeleteDoubleEntries extends Migration
{
    public function description () {
        return 'migrate calendar entries ammerland';
    }


    public function up () {
        $sem_id = '7637bfed08c7a2a3649eed149375cbc0'; //ammerland
        //$sem_id = '568fce7262620700103ce1657cabc5e3'; //localhost
        $stmt = DBManager::get()->prepare('SELECT event_id FROM calendar_event '
                . 'LEFT JOIN event_data USING(event_id) '
                . 'WHERE range_id = :range_id AND category_intern = `13`');
        $stmt->execute(array(
            ':range_id' => $sem_id,
        ));

        $ids = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
        foreach ( $ids as $id) {
            $entry = EventData::find($id);
            $entry->delete();

        }

       
    }


    public function down () {

    }


}