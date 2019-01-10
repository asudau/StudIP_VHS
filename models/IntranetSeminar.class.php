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
    
}
