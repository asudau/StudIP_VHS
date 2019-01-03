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
        
        $this->course = Course::findCurrent();
	 	$this->course_id = $this->course->id;
		
		if ($this->course)
		{
            $this->setupStudIPNavigation();	
        }
        
        //setup intranet navigation and forward if just logged in
        //TOTO: auslagern
        $intranets = $this->getIntranetIDsForUser();
        
        if (Navigation::hasItem('/start') && $intranets){
            Navigation::getItem('/start')->setURL(PluginEngine::getLink($this, array(), 'intranet_start/index/') . $intranets[0]);
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
        $datafield =  DataField::findOneBySQL('name = \'Overview style\'');
        $this->datafield_id = $datafield->datafield_id;
        $localEntries = DataFieldEntry::getDataFieldEntries(Course::findCurrent()->id);
        $this->style = $localEntries[$this->datafield_id]->value;

        if($this->style == 'standard'){
            $core_overview = CoreOverview::getTabNavigation($course_id);
            $item = new Navigation(_('Übersicht austauschen'), PluginEngine::getURL($this, array('style' => $this->style), 'seminar'));
            $core_overview['main']->addSubNavigation('switchback', $item);
            return $core_overview;
        }
       
        $navigation = new Navigation(_('Übersicht'));
        $navigation->setImage(Icon::create('seminar', 'info_alt'));
        $navigation->setActiveImage(Icon::create('seminar', 'info'));
        $navigation->setURL(PluginEngine::getURL($this, array('style' => $this->style), 'seminar'));
        
        return array(
            'overview_vhs' => $navigation
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
    
    private function setupStudIPNavigation(){
		
        //falls individuelle Einstellungen zur Reihenfogle vorliegen
		$block = SeminarTab::findOneBySQL('seminar_id = ? ORDER BY position ASC',
                                 array($this->course_id) );
		if($block){
			$this->sortCourseNavigation();
		}

    }
	
    private function sortCourseNavigation(){
	global $perm;
   	$restNavigation = array();
	$newNavigation = Navigation::getItem('/course');
	foreach(Navigation::getItem('/course') as $key => $tab){
		$block = SeminarTab::findOneBySQL('seminar_id = ? AND tab IN (?) ORDER BY position ASC',
                                 array($this->course_id,$key) );
		if($block){
			$tab->setTitle($block->getValue('title'));
			if($block->getValue('tn_visible') == true || $perm->have_studip_perm('dozent', Request::get('cid')) ){
				$subNavigations[$block->getValue('position')][$key] = $tab;
			}
					
		} else { 
		   //keine Info bezüglich Reihenfolge also hinten dran
		   //greift bei neu aktivierten Navigationselementen
		   $restNavigation[$key] = $tab;

		}

		$newNavigation->removeSubNavigation($key);
	}	
	
	ksort($subNavigations);

	foreach($subNavigations as $subNavs){
	    foreach($subNavs as $key => $subNav){
		$newNavigation->addSubNavigation($key, $subNav);
		
	    }
	}
	if(count($restNavigation)>0){
        foreach($restNavigation as $key => $restNav){
            $newNavigation->addSubNavigation($key, $restNav);  
        }
	}

	Navigation::addItem('/course', $newNavigation);
    }
    
}
