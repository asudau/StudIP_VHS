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
        //$this->set_layout($GLOBALS['template_factory']->open('layouts/base'));

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
            $this->inst_config[$field->range_id] = IntranetConfig::find($field->range_id)->template;
        }
        
        $seminar_fields = DatafieldEntryModel::findBySQL('datafield_id = \'' . $datafield_id_sem . '\' AND content = 1');
        $this->sem_for_instid = array();
        foreach ($seminar_fields as $field){
            $course = Course::find($field->range_id);
            $this->sem_for_instid[$course->institut_id][] = $course;
        }
        
        if ($this->intranet_inst){
            $sidebar = Sidebar::Get();

            $navcreate = new ActionsWidget();
            $navcreate->addLink(_('Veranstaltung hinzufügen'),
                                  $this->url_for('intranetverwaltung/index/add_sem'),
                                  Icon::create('seminar+add', 'clickable'))->asDialog('size=big'); 
            $sidebar->addWidget($navcreate);
        }
        
       
      

    }
    
    public function add_sem_action(){

    }
    
    
    public function settings_action()
    {
       
       
    }
    
    public function set_action($inst_id) {
        $config = IntranetConfig::find($inst_id);
        $seminare = Request::getArray('seminare');
        $template = Request::get('template');
        
        $config->seminare = $seminare;
        $config->template = $template;
        $config->store();
        
       
        //PageLayout::Post_Message(new MessageBox('success', 
        $this->render_action('index');
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
