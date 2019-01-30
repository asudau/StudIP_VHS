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
        Navigation::activateItem('admin/intranetverwaltung/index');
        $this->setup_navigation($intranet_id);
        
//        $navcreate = new ActionsWidget();
//        $navcreate->addLink(_('Veranstaltung hinzufügen'),
//                              $this->url_for('intranetverwaltung/index/add_sem'),
//                              Icon::create('seminar+add', 'clickable'))->asDialog('size=big'); 
//        $sidebar->addWidget($navcreate);
        
    }
    
    public function members_action($intranet_id){
        
        $this->setup_navigation($intranet_id);
        Navigation::activateItem('admin/intranetverwaltung/members');
        
        $search_obj = new SQLSearch("SELECT auth_user_md5.user_id, {$GLOBALS['_fullname_sql']['full_rev']} as fullname, username, perms "
                        . "FROM auth_user_md5 "
                        . "LEFT JOIN user_info ON (auth_user_md5.user_id = user_info.user_id) "
                        . "WHERE "
                        . "(username LIKE :input OR Vorname LIKE :input "
                        . "OR CONCAT(Vorname,' ',Nachname) LIKE :input "
                        . "OR CONCAT(Nachname,' ',Vorname) LIKE :input "
                        . "OR Nachname LIKE :input OR {$GLOBALS['_fullname_sql']['full_rev']} LIKE :input) "
                        . "AND visible != 'never' "
                        . " ORDER BY fullname ASC",
                        _("Nutzer suchen"), "user_id");

        $defaultSelectedUser = new SimpleCollection(InstituteMember::findByInstituteAndStatus($intranet_id, words('autor tutor dozent admin')));
        URLHelper::setBaseURL($GLOBALS['ABSOLUTE_URI_STUDIP']);
        $mp = MultiPersonSearch::get("intranet_member_add" . $intranet_id)
        ->setLinkText(_("Mitglieder hinzufügen"))
        ->setDefaultSelectedUser($defaultSelectedUser->pluck('user_id'))
        ->setTitle(_('Personen in die Einrichtung eintragen'))
        ->setExecuteURL($this->url_for('intranetverwaltung/index/add_user/' . $intranet_id . '/autor'))
        ->setSearchObject($search_obj)
//        ->setAdditionalHTML('<p><strong>' . _('Nur bei Zuordnung eines Admins:') .' </strong> <label>Benachrichtigung der <input name="additional[]" value="admins" type="checkbox">' . _('Admins') .'</label>
//                             <label><input name="additional[]" value="dozenten" type="checkbox">' . _('Dozenten') . '</label></p>')
        ->render();
        
        $mp_dozenten = MultiPersonSearch::get("intranet_admin_add" . $intranet_id)
        ->setLinkText(_("Intranet-Admins hinzufügen"))
        ->setDefaultSelectedUser($defaultSelectedUser->pluck('user_id'))
        ->setTitle(_('Personen in die Einrichtung eintragen'))
        ->setExecuteURL($this->url_for('intranetverwaltung/index/add_user/' . $intranet_id . '/admin'))
        ->setSearchObject($search_obj)
        ->render();
        
        $sidebar = Sidebar::Get();
        $edit = new ActionsWidget();
        $edit->setTitle(_('Nutzer verwalten'));
        $edit->addElement(new WidgetElement($mp));
        $edit->addElement(new WidgetElement($mp_dozenten));
        $sidebar->addWidget($edit);
        
        $this->inst = Institute::find($intranet_id);
        $this->members = $this->inst->members;
        
    }
    
    public function add_sem_action(){
        
    }
    
    public function add_user_action($intranet_id, $status){
        $this->mp = MultiPersonSearch::load("intranet_member_add" . $intranet_id);
        //$additionalCheckboxes = $this->mp->getAdditionalOptionArray();

        if (count($this->mp->getAddedUsers()) !== 0) {
            foreach ($this->mp->getAddedUsers() as $u_id) {
                log_event('INST_USER_ADD', $intranet_id ,$u_id, $status);

                // als autor aufnehmen
                $query = "INSERT IGNORE INTO user_inst (user_id, Institut_id, inst_perms)
                          VALUES (?, ?, ?)";
                $statement = DBManager::get()->prepare($query);
                $statement->execute(array($u_id, $intranet_id, $status));

                PageLayout::postMessage(MessageBox::info(sprintf(_("%s wurde in die Einrichtung aufgenommen."), User::find($u_id)->username))); 
                
                IntranetConfig::addUserToIntranetCourses($u_id, $intranet_id, $status);
            }
            
        }
        $this->redirect($this->url_for('/intranetverwaltung/index/members/' . $intranet_id));
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
//          $entry->seminar_id = $sem_id;
//          $entry->institut_id = $inst_id;           
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
    
    private function setup_navigation($intranet_id){
        $this->intranet_inst = Institute::find($intranet_id);
        $this->institutes_with_intranet = IntranetConfig::getInstitutesWithIntranet();
        $this->inst_config = array();
        
        foreach ($this->institutes_with_intranet as $inst){
            $this->inst_config[$inst->id] = IntranetConfig::find($inst->id)->template;
        }
        
        if ($this->intranet_inst){
            
            $navigation = Navigation::getItem('/admin/intranetverwaltung');
            $navigation->addSubNavigation('members', new Navigation('Nutzer verwalten', $this->url_for('intranetverwaltung/index/members/'. $intranet_id)));
            
            if (IntranetConfig::find($intranet_id)){
                $this->intranet_courses = IntranetConfig::find($intranet_id)->getRelatedCourses();
            }
        }
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
