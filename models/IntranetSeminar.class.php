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
            if ($this->Institut_id == $course->institut_id){
                $sem_for_instid[] = $course;
            }
        }
        return $sem_for_instid;
    }
    
    private function getDatafieldIdSem(){
        return md5('Intranet-Veranstaltung');
    }
}
