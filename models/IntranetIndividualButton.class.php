<?php


/**
 * @author  <asudau@uos.de>
 *
 * @property string  $Button_id
 * @property string  $Instidute_id
 * @property int     $position
 * @property string  $target
 * @property string  $text
 * @property string  $tooltip
 * @property string  $icon
 */
class IntranetIndividualButton extends \SimpleORMap
{

    public $errors = array();

    /**
     * Give primary key of record as param to fetch
     * corresponding record from db if available, if not preset primary key
     * with given value. Give null to create new record
     *
     * @param mixed $id primary key of table
     */
    protected static function configure($config = array())
    {
        $config['db_table'] = 'intranet_individual_buttons';
        parent::configure($config);
    }

    public function getContent(){
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


}


