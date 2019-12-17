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

    protected static function configure($config = array())
    {
        $config['db_table'] = 'intranet_seminar_config';
        parent::configure($config);
    }
    
}
