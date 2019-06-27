<?php


class MigrateOldCalendarEntries extends Migration
{
    public function description () {
        return 'migrate calendar entries ammerland';
    }


    public function up () {
        $sem_id = '7637bfed08c7a2a3649eed149375cbc0'; //ammerland
        //$sem_id = '568fce7262620700103ce1657cabc5e3'; //localhost
        $sql = "SELECT * FROM intranet_dates";
        $statement = DBManager::get()->prepare($sql);
        $statement->execute();
        $dates_data = $statement->fetchAll(PDO::FETCH_ASSOC);
       
        foreach ( $dates_data as $row) {
            if($row['type'] == 'birthday'){
                $entry = new EventData();
                $user = User::find($row['user_id']);
                $entry->author_id = $row['user_id'];
                $entry->editor_id = $row['user_id'];
                $entry->start = strtotime(str_replace('.', '-', $row['begin']));
                $entry->end = strtotime(str_replace('.', '-', $row['end']));
                $entry->rtype = 'YEARLY';
                $entry->month = explode(".", $row['begin'])[1];
                $entry->day = explode(".", $row['begin'])[0];
                $entry->category_intern = 11;
                $entry->summary =  $user->vorname . ' ' . $user->nachname;
                $entry->description = $row['notice'];
                $entry->linterval = '1';
                $entry->store();
                $event = new CalendarEvent();
                $event->range_id = $sem_id;
                $event->event_id = $entry->event_id;
                $event->mkdate = $row['mkdate'];
                $event->chdate = $row['chdate'];
                $event->store();
            } else if($row['type'] == 'urlaub'){
                $entry = new EventData();
                $user = User::find($row['user_id']);
                $entry->author_id = $user->id;
                $entry->editor_id = $user->id;
                $entry->start = strtotime(str_replace('.', '-', $row['begin']));
                $entry->end = strtotime(str_replace('.', '-', $row['end']));
                $entry->rtype = 'SINGLE';
                $entry->category_intern = 13;
                $entry->summary =  $user->vorname . ' ' . $user->nachname;
                $entry->description = $row['notice'];
                $entry->store();
                $event = new CalendarEvent();
                $event->range_id = $sem_id;
                $event->event_id = $entry->event_id;
                $event->mkdate = $row['mkdate'];
                $event->chdate = $row['chdate'];
                $event->store();
            }
        }

       
    }


    public function down () {

    }


}