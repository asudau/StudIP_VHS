<?php
require_once 'app/controllers/news.php';


class Intranetverwaltung_IndexController extends StudipController {

    public function __construct($dispatcher)
    {
        parent::__construct($dispatcher);
        $this->plugin = $dispatcher->plugin;
        Navigation::activateItem('admin/intranetverwaltung');
        
    }

    public function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);

        PageLayout::setTitle(_("Intranetverwaltung - Übersicht"));

        // $this->set_layout('layouts/base');
        $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));

    }

    public function index_action($intranet = NULL)
    {
        $this->intranet_inst = Institute::find($intranet);
        $datafield_id_inst = md5('Eigener Intranetbereich');
        $datafield_id_sem = md5('Intranet-Veranstaltung');
        $this->institutes_with_intranet = array();
        $this->inst_config = array();
        
        $institute_fields = DatafieldEntryModel::findBySQL('datafield_id = \'' . $datafield_id_inst . '\' AND content = 1');
        foreach ($institute_fields as $field){
            array_push($this->institutes_with_intranet, Institute::find($field->range_id)); 
            $this->inst_config[$field->range_id] = IntranetConfig::find($field->range_id)->startpage;
        }
        
        $seminar_fields = DatafieldEntryModel::findBySQL('datafield_id = \'' . $datafield_id_sem . '\' AND content = 1');
        $this->sem_for_instid = array();
        foreach ($seminar_fields as $field){
            $course = Course::find($field->range_id);
            $this->sem_for_instid[$course->institut_id][] = $course;
        }
        
       
      

    }
    
    public function settings_action()
    {
       
       
    }
    
    public function set_action() {
        $style = Request::get('style');
        $description = Request::get('description');
        
        $localEntries = DataFieldEntry::getDataFieldEntries(Course::findCurrent()->id);
        $this->style = $localEntries['8a8bf27eebccfb0604e9db6151d228f4'];
        $this->style->setValue($style);
        $this->style->store();
        
        $this->course->beschreibung = $description;
        $this->course->store();
        

        $tab_count = intval(Request::get('tab_num'));
        $tab_position = array();

        $order = explode(',',Request::get('new_order'));
        $position = 1;
        foreach($order as $o){
            $tab_position['tab_position_'. $o] = $position;
            $position++;
        }

        for ($i = 0; $i < $tab_count; $i++){

            $block = new SeminarTab();

            //falls noch kein Eintrag existiert: anlegen
            if (!SeminarTab::findOneBySQL('seminar_id = ? AND tab IN (?) ORDER BY position ASC',
                                     array($this->course->id,Request::get('tab_title_'. $i)))){
                $block->setData(array(
                    'seminar_id' => $this->course->id,
                    'tab'       => Request::get('tab_title_'. $i),
                    'title'       => Request::get('new_tab_title_'. $i),
                    'position'       => $tab_position['tab_position_'. $i],
                    'tn_visible'      => Request::get('visible_'. $i) == 'on' ? true : false
                    ));	

                    $block->store();
            } 

            //falls ein Eintrag existiert: anpassen
            else {
                $block = SeminarTab::findOneBySQL('seminar_id = ? AND tab IN (?) ORDER BY position ASC',
                                     array($this->course->id,Request::get('tab_title_'. $i)));
                $block->setValue('title', Request::get('new_tab_title_'. $i));
                $block->setValue('position', $tab_position['tab_position_'. $i]);
                $block->setValue('tn_visible', Request::get('visible_'. $i) == 'on' ? true : false);
                $block->store();

            }
        }
        
        //PageLayout::Post_Message(new MessageBox('success', 
        $this->redirect($this->url_for('index/settings'));
    }
    
       // customized #url_for for plugins
    public function url_for($to)
    {
        $args = func_get_args();

        # find params
        $params = array();
        if (is_array(end($args))) {
            $params = array_pop($args);
        }

        # urlencode all but the first argument
        $args = array_map('urlencode', $args);
        $args[0] = $to;

        return PluginEngine::getURL($this->dispatcher->plugin, $params, join('/', $args));
    }

    
}
