<?php


/**
 * @author  <asudau@uos.de>
 *
 * @property int     $seminar_id
 * @property int     $institut_id
 * @property text    $news_caption
 * @property bool    $show_news
 * @property text    $files_caption
 * @property bool    $use_files
 * @property text    $add_instuser_as (autor, tutor, dozent)
 * 
 */
class IntranetSeminar extends SimpleORMap
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

        $this->db_table = 'intranet_seminar_config';
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
