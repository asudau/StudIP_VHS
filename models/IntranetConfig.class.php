<?php


/**
 * @author  <asudau@uos.de>
 *
 * @property int     $Institut_id
 * @property text    $startpage
 * @computed related_seminars 
 */
class IntranetConfig extends SimpleORMap
{

    public $errors = array();

    /**
     * Give primary key of record as param to fetch
     * corresponding record from db if available, if not preset primary key
     * with given value. Give null to create new record
     *
     * @param mixed $id primary key of table
     */
    public function __construct($id = null) {

        $this->db_table = 'intranet_config';
        parent::__construct($id);

    }
    
    public function getRelatedCourses(){
        $seminar_fields = DatafieldEntryModel::findBySQL('datafield_id = \'' . $this->getDatafieldIdSem() . '\' AND content = 1');
        $sem_for_instid = array();
        foreach ($seminar_fields as $field){
            $course = Course::find($field->range_id);
            if (in_array($this->Institut_id, Seminar::getInstitutes($course->id))){
                $sem_for_instid[] = $course;
            }
        }
        return $sem_for_instid;
    }
    
    public function addCourseToIntranet($seminar_id){
        if (!DatafieldEntryModel::findBySQL('datafield_id = \'' . $this->getDatafieldIdSem() . '\' AND range_id = \'' . $seminar_id . '\'')){
//            $entry = new DatafieldEntryModel(); //funktioniert nicht..
//            $entry->datafield_id = $this->getDatafieldIdSem();
//            $entry->range_id = $seminar_id;
//            $entry->content = '1';
//            $entry->store();
            $query = "INSERT IGNORE INTO datafields_entries (datafield_id, range_id, content, mkdate, chdate)
                      VALUES (?, ?, ?, ?, ?)";
            $statement = DBManager::get()->prepare($query);
            $statement->execute(array($this->getDatafieldIdSem(), $seminar_id, 1, time(), time()));
        }
        if (!in_array($this->Institut_id, Seminar::getInstitutes($seminar_id))){
            $query = "INSERT INTO seminar_inst (seminar_id, Institut_id)
                          VALUES (?, ?)";
                $statement = DBManager::get()->prepare($query);
                $statement->execute(array($seminar_id, $this->Institut_id));
        }
    }
    
    
    private function getDatafieldIdSem(){
        return md5('Intranet-Veranstaltung');
    }
    
    public static function getInstitutesWithIntranet($id = false){
        $datafield_id_inst = md5('Eigener Intranetbereich');
        //$datafield_id_sem = md5('Intranet-Veranstaltung');
        $institutes_with_intranet = array();
        
        $institute_fields = DatafieldEntryModel::findBySQL('datafield_id = \'' . $datafield_id_inst . '\' AND content = 1');
        foreach ($institute_fields as $field){
            if ($id){
                array_push($institutes_with_intranet, $field->range_id); 
            }
            else array_push($institutes_with_intranet, Institute::find($field->range_id)); 
        }
        return $institutes_with_intranet;
    }
    
    public function getIntranetIDsForUser($user){
        $datafield_id_inst = md5('Eigener Intranetbereich');
        $intranets = array();
        foreach($user->institute_memberships as $membership){
            $entries = DataFieldEntry::getDataFieldEntries($membership->institut_id);
            if ($entries[$datafield_id_inst]->value){
                $intranets[] = $membership->institut_id;
            }
        }
        return $intranets;
    }
    
     public static function addUserToIntranetCourses($user_id, $intranet_id, $status) {
        $courses = IntranetConfig::find($intranet_id)->getRelatedCourses();
        
        //in Veranstaltungen gibt es nichts höheres als Dozenten
        if($status == 'admin'){
            $status = 'dozent';
        }
        
        $query = "INSERT IGNORE INTO seminar_user (Seminar_id, user_id, status, position, gruppe, visible, mkdate)
                      VALUES (?, ?, ?, ?, ?, ?, UNIX_TIMESTAMP())";

        foreach ($courses as $course) {
            $sem_config = IntranetSeminar::find([$course->id, $intranet_id]);
            if ($sem_config && $sem_config->add_instuser_as){
                $status = $sem_config->add_instuser_as;
                $statement = DBManager::get()->prepare($query);
                $statement->execute(array(
                    $course->id,
                    $user_id,
                    $status,
                    0,
                    1,
                    in_array($status, words('tutor dozent')) ? 'yes' : 'unknown',
                ));

                StudipLog::log('SEM_USER_ADD', $course->id, $user_id, $status, 'Wurde in die Veranstaltung eingetragen');
            }
        }
    }
    
    public function CourseMembershipsOfUser($intranet_id, $user_id){
        $courses = IntranetConfig::find($intranet_id)->getRelatedCourses();
        $memberships = 0;
        foreach ($courses as $course){
            $query = "SELECT * FROM seminar_user WHERE Seminar_id LIKE :sem_id AND user_id LIKE :user_id";
            $statement = DBManager::get()->prepare($query);
            $statement->execute(array(':sem_id' => $course->id, ':user_id' => $user_id));
            if ($statement->fetch()[0]){
                $memberships++;
            }
        }
        return $memberships;
    }
    
}
