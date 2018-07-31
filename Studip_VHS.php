<?php
require_once 'lib/bootstrap.php';
require_once __DIR__ . '/models/SeminarTab.class.php';

/**
 * Uebersicht_VHS.class.php
 *
 * ...
 *
 * @author  Annelene Sudau <asudau@uos.de>
 * @version 0.1a
 */

class Studip_VHS extends StudIPPlugin implements StandardPlugin, SystemPlugin
{

    public function __construct()
    {
        parent::__construct();
        global $perm;
        
        $this->sidebar_images = array(
            'admin' => 'admin-sidebar.png',
            'forum2' => 'forum-sidebar.png',
            'members' => 'person-sidebar.png',
            'files' => 'files-sidebar.png',
            'schedule' => 'schedule-sidebar.png',
            'wiki' => 'wiki-sidebar.png',
            'calendar' => 'date-sidebar.png',
            'mooc_courseware' => 'group-sidebar.png',
            'members' => 'person-sidebar.png',
            'vipsplugin' => 'checkbox-sidebar.png',
            'modules' => 'plugin-sidebar.png',
            'literature' => 'literature-sidebar.png',
            'generic' => 'generic-sidebar.png',
        );
        
        $intranets = $this->getIntranetIDsForUser();
        $intranets[0] = '97543add4c36b0502bc8dd58a3cf7bd9';
        
        if (Navigation::hasItem('/start') && $intranets){
            Navigation::getItem('/start')->setURL( PluginEngine::getLink($this, array('cid' => ''), 'intranet_start/index/' . $intranets[0]));
        }
        
        if($perm->have_perm('root')){
            $navigation = new Navigation('Intranetverwaltung', PluginEngine::getURL($this, array(), 'intranetverwaltung/index'));
            $navigation->addSubNavigation('index', new Navigation('Übersicht', PluginEngine::getURL($this, array(), 'intranetverwaltung/index')));
            $navigation->addSubNavigation('settings', new Navigation('Einstellungen', PluginEngine::getURL($this, array(), 'settings')));
            Navigation::addItem('/admin/intranetverwaltung', $navigation);
        } 

        $referer = $_SERVER['REQUEST_URI'];
   
        //Intranetnutzer werden statt auf die allgemeine Startseite auf ihre individuelle Startseite weitergeleitet
        if ( $referer!=str_replace("dispatch.php/start","",$referer) &&  $intranets){;
            //$result = $this->getSemStmt($GLOBALS['user']->id);
            header('Location: '. PluginEngine::getLink($this, array(), 'intranet_start/index/') . '97543add4c36b0502bc8dd58a3cf7bd9', false, 303);
            exit();	
        //Nicht-Intranetnutzer werden, wenn sie die Intranet URL verwenden, auf die allgemeine Startseite weitergeleitet
        } 
    }

    public function initialize ()
    {
        PageLayout::addStylesheet($this->getPluginURL().'/assets/settings_sortable.css');
        PageLayout::addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js');
        PageLayout::addScript('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js');
        PageLayout::addScript($this->getPluginURL().'/assets/scripts/settings_sortable.js');
        //PageLayout::addScript($this->getPluginURL().'/assets/scripts/replace_tab_navigation.js');
        
    }

    public function getTabNavigation($course_id)
    {
        $course = Course::findCurrent()->id;
        $localEntries = DataFieldEntry::getDataFieldEntries(Course::findCurrent()->id);
        $this->style = $localEntries['8a8bf27eebccfb0604e9db6151d228f4']->value;

        return array(
            'overview_vhs' => new Navigation(
                'Übersicht',
                PluginEngine::getURL($this, array('style' => $this->style), 'seminar')
            )
        );
    }

    public function getNotificationObjects($course_id, $since, $user_id)
    {
        return array();
    }

    public function getIconNavigation($course_id, $last_visit, $user_id)
    {
        // ...
    }

    public function getInfoTemplate($course_id)
    {
        // ...
    }

    public function perform($unconsumed_path)
    {
        $this->setupAutoload();
        $dispatcher = new Trails_Dispatcher(
            $this->getPluginPath(),
            rtrim(PluginEngine::getLink($this, array(), null), '/'),
            'show'
        );
        $dispatcher->plugin = $this;
        $dispatcher->dispatch($unconsumed_path);
        
        //for current user check prerequisites
        //if all existing excercises done, ajax call of zertifikats-action
        
    }

    private function setupAutoload()
    {
        if (class_exists('StudipAutoloader')) {
            StudipAutoloader::addAutoloadPath(__DIR__ . '/models');
        } else {
            spl_autoload_register(function ($class) {
                include_once __DIR__ . $class . '.php';
            });
        }
    }
	
    public function getIntranetIDsForUser(){
        $user = User::findCurrent();
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
    
}
