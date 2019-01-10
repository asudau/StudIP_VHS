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

    public function index_action($intranet_id = NULL)
    {
        $this->intranet_inst = Institute::find($intranet_id);
        $datafield_id_inst = md5('Eigener Intranetbereich');
        $datafield_id_sem = md5('Intranet-Veranstaltung');
        $this->institutes_with_intranet = array();
        $this->inst_config = array();
        
        $institute_fields = DatafieldEntryModel::findBySQL('datafield_id = \'' . $datafield_id_inst . '\' AND content = 1');
        foreach ($institute_fields as $field){
            array_push($this->institutes_with_intranet, Institute::find($field->range_id)); 
            $this->inst_config[$field->range_id] = IntranetConfig::find($field->range_id)->template;
        }
        
        
        
        if ($this->intranet_inst){
            $sidebar = Sidebar::Get();

            $navcreate = new ActionsWidget();
            $navcreate->addLink(_('Veranstaltung hinzufügen'),
                                  $this->url_for('intranetverwaltung/index/add_sem'),
                                  Icon::create('seminar+add', 'clickable'))->asDialog('size=big'); 
            $sidebar->addWidget($navcreate);
            
            $this->intranet_courses = IntranetConfig::find($intranet_id)->getRelatedCourses();
        }
        
       
      

    }
    
    public function add_sem_action(){
        
    }
    
    public function editseminar_action($sem_id, $inst_id){
        $this->entry = IntranetSeminar::find([$sem_id, $inst_id]);
        $this->inst_id = $inst_id;
        $this->sem_id = $sem_id;
        
    }  
    
    public function saveseminar_action($sem_id,  $inst_id){
        $entry = IntranetSeminar::find([$sem_id, $inst_id]);
        if (!$entry){
        $entry = new IntranetSeminar([$sem_id, $inst_id]);
//             $entry->seminar_id = $sem_id;
//             $entry->institut_id = $inst_id;           
        }
        $entry->show_news = Request::get('show_news') ? true : false;
        $entry->news_caption = Request::get('news_caption');
        $entry->use_files = Request::get('use_files') ? true : false;
        $entry->files_caption = Request::get('files_caption');
        $entry->add_instuser_as = Request::get('add_instuser_as');
        $entry->store();
        $this->redirect('intranetverwaltung/index/index/' . $inst_id );
    }
    
    public function settings_action()
    {
       
       
    }
    
    public function set_action($inst_id) {
        $config = IntranetConfig::find($inst_id);
        $template = trim(Request::get('template'));
        
        if (!$config){
            $config = new IntranetConfig($inst_id);
        }
        
        $config->template = $template;
        $config->store();
       
        PageLayout::postMessage(MessageBox::success('Einstellung gespeichert'));
        $this->redirect($this->url_for('intranetverwaltung/index/index/' . $inst_id));
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
