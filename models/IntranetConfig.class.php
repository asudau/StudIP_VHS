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
    
    public static function addUserToIntranetCourses($user_id, $intranet_id, $status){
        $courses = IntranetConfig::find($intranet_id)->getRelatedCourses();
        $query = "INSERT IGNORE INTO seminar_user (Seminar_id, user_id, status, position, gruppe, visible, mkdate)
                      VALUES (?, ?, ?, ?, ?, ?, UNIX_TIMESTAMP())";
        
        foreach($courses as $course){
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
