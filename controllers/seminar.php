<?php
require_once 'app/controllers/news.php';
require_once 'app/controllers/questionnaire.php';
require_once 'app/controllers/course/overview.php';


class SeminarController extends StudipController {

    public function __construct($dispatcher)
    {
        parent::__construct($dispatcher);
        $this->plugin = $dispatcher->plugin;
        if (Navigation::hasItem("/course/overview_vhs")){
            Navigation::activateItem('course/overview_vhs');
        } else if (Navigation::hasItem("/course/main")){
            Navigation::activateItem('course/main');
        }
        
        $datafield =  DataField::findOneBySQL('name = \'Overview style\'');
        $this->datafield_id = $datafield->datafield_id;
    }

    public function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);

        $this->course = Course::findCurrent();
        $this->sem = new Seminar($this->course->id);

        PageLayout::setTitle($this->course->getFullname()." - " ._("Übersicht"));

        // $this->set_layout('layouts/base');
        $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));
        
		//falls innerhalb eines Kurses, Kursnavigation gemäß Konfiguration anpassen
		if ($this->course) 
		{
            $this->setupStudIPNavigation();	
        }
    }

    public function index_action()
    {
        //$course = Course::findCurrent()->id;
        $localEntries = DataFieldEntry::getDataFieldEntries(Course::findCurrent()->id);
        $this->style = $localEntries[$this->datafield_id]->value;
        
        //defaultwert wenn noch nichts gewählt wurde
        if(!$this->style){
            $this->style = 'full';
        }
        
        if ($GLOBALS['perm']->have_studip_perm('tutor', $this->course->id)){
            $actions = new ActionsWidget();
            $actions->setTitle(_('Aktionen'));

            $actions->addLink(
            'Kurs gestalten',
            $this->url_for('seminar/settings'),'icons/16/blue/edit.png'); 

            Sidebar::get()->addWidget($actions);
            
//            if($this->style == 'full'){
//                $sidebar = Sidebar::Get();
//                $navcreate = new LinksWidget();
//                $navcreate->setTitle('Weitere Aktionen');
//                $navcreate->addLink(_('Ankündigung erstellen'),
//                               URLHelper::getLink("dispatch.php/news/edit_news/new/" . $this->course->id),
//                              Icon::create('news+add', 'clickable'), ['rel' => 'get_dialog']); 
//                $sidebar->addWidget($navcreate);
//            }
        }
        
        
       
         
        if (!Navigation::hasItem("/course/main")) {
       
            $navigation = new Navigation(_('Übersicht'));
            $navigation->setImage(Icon::create('seminar', 'info_alt'));
            $navigation->setActiveImage(Icon::create('seminar', 'info'));
            $navigation->setURL(PluginEngine::getURL($this, array('style' => $this->style), 'seminar'));
            Navigation::getItem("/course")->addSubNavigation('main', $navigation);
       /** 
        
        //Keine Übersichtsseite. Anstatt eines Fehler wird der Nutzer zum ersten
        //Reiter der Veranstaltung weiter geleitet. passiert evtl auch schon in seminar_main.php
        if (Navigation::hasItem("/course")) {
            foreach (Navigation::getItem("/course")->getSubNavigation() as $navigation) {
                header('Location: '.URLHelper::getURL($navigation->getURL()));
                die;
            }
        }
        * 
        */
        }
        
        $description = Request::get('description');
        
        PageLayout::addStylesheet($this->plugin->getPluginURL().'/assets/style_' . $this->style . '.css');
         
        ### grid-layout###
        $this->tabs = Navigation::getItem('/course');
       
        ###full_layout###
        // Fetch news
        $dispatcher = new StudipDispatcher();
        $controller = new NewsController($dispatcher);
        $response = $controller->relay('news/display/' . $this->course->id);
        $this->internnewstemplate = $GLOBALS['template_factory']->open('shared/string');
        $this->internnewstemplate->content = $response->body;
        
        if (StudipNews::CountUnread() > 0) {
            $navigation = new Navigation('', PluginEngine::getLink($this, array(), 'read_all'));
            $navigation->setImage(Icon::create('refresh', 'clickable', ["title" => _('Alle als gelesen markieren')]));
            $icons[] = $navigation;
        }

        $this->internnewstemplate->icons = $icons;
        $this->news =  $this->internnewstemplate;
        
        
        ###full_layout###
        // Fetch frageboegen
        $dispatcher = new StudipDispatcher();
        $controller = new QuestionnaireController($dispatcher);
        $response = $controller->relay('questionnaire/widget/' . $this->course->id);
        $this->questionnairetemplate = $GLOBALS['template_factory']->open('shared/string');
        $this->questionnairetemplate->content = $response->body;
        
        if (StudipNews::CountUnread() > 0) {
            $navigation = new Navigation('', PluginEngine::getLink($this, array(), 'read_all'));
            $navigation->setImage(Icon::create('refresh', 'clickable', ["title" => _('Alle als gelesen markieren')]));
            $icons[] = $navigation;
        }

        $this->questionnairetemplate->icons = $icons;
        $this->questionnaires =  $this->questionnairetemplate;
        
        
        
        
        
        if($GLOBALS['auth']->auth['uid'] != 'nobody'){
            // Load evaluations
            $eval_db = new EvaluationDB();
            $this->evaluations = StudipEvaluation::findMany($eval_db->getEvaluationIDs($this->course->id, EVAL_STATE_ACTIVE));
            $show_votes[] = 'active';
            // Check if we got expired
            if (Request::get('show_expired')) {
                $show_votes[] = 'stopvis';
                if ($this->admin) {
                    $this->evaluations = array_merge($this->evaluations, StudipEvaluation::findMany($eval_db->getEvaluationIDs($this->course->id, EVAL_STATE_STOPPED)));
                    $show_votes[] = 'stopinvis';
                }
            }
        }

        // Fetch  votes nur bis 3.1
        /** 
        $this->votes = StudipVote::findBySQL('range_id = ? AND state IN (?) ORDER BY mkdate desc', array($this->course_id,$show_votes));
	
        if ($vote = Request::get('vote')) {
            $vote = new StudipVote($vote);
            if ($vote && $vote->isRunning() && (!$vote->userVoted() || $vote->changeable)) {
                try {
                    $vote->insertVote(Request::getArray('vote_answers'), $GLOBALS['user']->id);
                } catch (Exception $exc) {
                    $GLOBALS['vote_message'][$vote->id] = MessageBox::error($exc->getMessage());
                }
            }
        }
        **/

        $this->description = $this->course->__get('Beschreibung');

        $response = $this->relay("seminar/members");
        $this->members = $response->body;
	
        // Fetch dates
        if (!$this->studygroup_mode) {
            $response = $this->relay("seminar/display/{$this->course_id}/1210000");
            $this->dates = $response->body;
        }
	
        $response = $this->relay("seminar/documents");
        $this->documents = $response->body;
        
        $this->render_action($this->style);

    }
    
    public function not_started_action($course_id)
    {
        $this->coursebegin = Studip_VHS::getCourseBegin(Course::findCurrent()->id)? : Course::findCurrent()->dates[0]->date;
    }
    
    public function settings_action()
    {
        //PageLayout::addScript($this->plugin->getPluginURL().'/assets/scripts/settings_script.js');
        
        $actions = new ActionsWidget();
        $actions->setTitle(_('Aktionen'));

        $actions->addLink(
        'Zurück zur Übersicht',
        URLHelper::getURL('seminar_main.php'));; 

        Sidebar::get()->addWidget($actions);
        
        $course_id = Course::findCurrent()->id;
        $localEntries = DataFieldEntry::getDataFieldEntries(Course::findCurrent()->id);
        $this->tabs = $this->get_tabs();
        
        $this->style = $localEntries[$this->datafield_id]->value;
        
        $this->coursebegin = $this->plugin->getCourseBegin($course_id);
       
    }
    
    public function set_action() {
        $style = Request::get('style');
        $description = Request::get('description');
        
        $localEntries = DataFieldEntry::getDataFieldEntries(Course::findCurrent()->id);
        $this->style = $localEntries[$this->datafield_id];
        $this->style->setValue($style);
        $this->style->store();
        
        $this->course->beschreibung = $description;
        $this->course->store();

        $date = DateTime::createFromFormat('Y-m-d', Request::get('start_date'));
        $this->plugin->setCourseBegin(Course::findCurrent()->id, $date->getTimestamp());

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
        $this->redirect($this->url_for('seminar/settings'));
    }
    
    
    public function members_action() {
        $this->dozenten = $this->sem->getMembers('dozent');
        $this->tutoren = $this->sem->getMembers('tutor');
        $this->autoren = $this->sem->getMembers('autor');
        $this->users = $this->sem->getMembers('user');
    }

    public function documents_action() {
        $this->documents = $this->getSeminarDocuments();

    }  
    
    /**
     * Widget controller to produce the formally known show_votes()
     *
     * @param String $range_id range id of the news to get displayed
     * @return array() Array of votes
     */
    public function display_action($range_id, $timespan = 604800, $start = null) {

        // Fetch time if needed
        $start = $start ? : strtotime('today');
        $context = get_object_type($range_id, array('user', 'sem'));
        if ($context === 'user') {
            $events = new DbCalendarEventList(new SingleCalendar($range_id, Calendar::PERMISSION_READABLE), $start, $start + $timespan, TRUE, Calendar::getBindSeminare($GLOBALS['user']->id), ($GLOBALS['user']->id == $range_id ? array() : array('CLASS' => 'PUBLIC')));

            // Prepare termine
            $this->termine = array();

            while ($termin = $events->nextEvent()) {
                // Adjust title
                if (date("Ymd", $termin->getStart()) == date("Ymd", time()))
                    $termin->title .= _("Heute") . date(", H:i", $termin->getStart());
                else {
                    $termin->title = substr(strftime("%a", $termin->getStart()), 0, 2);
                    $termin->title .= date(". d.m.Y, H:i", $termin->getStart());
                }

                if ($termin->getStart() < $termin->getEnd()) {
                    if (date("Ymd", $termin->getStart()) < date("Ymd", $termin->getEnd())) {
                        $termin->title .= " - " . substr(strftime("%a", $termin->getEnd()), 0, 2);
                        $termin->title .= date(". d.m.Y, H:i", $termin->getEnd());
                    } else {
                        $termin->title .= " - " . date("H:i", $termin->getEnd());
                    }
                }

                if ($termin->getTitle()) {
                    $tmp_titel = htmlReady(mila($termin->getTitle())); //Beschneiden des Titels
                    $termin->title .= ", " . $tmp_titel;
                }

                // Store for view
                $this->termine[] = array(
                    'id' => $termin->id,
                    'chdate' => $termin->chdate,
                    'title' => $termin->title,
                    'description' => $termin->description,
                    'room' => $termin->getLocation(),
                    'seminar_id' => $termin instanceOf SeminarEvent ? $termin->getSeminarId() : '',
                    'info' => $termin instanceOf SeminarEvent ? array() :
                    array(
                        _('Kategorie') => $termin->toStringCategories(),
                        _('Priorität') => $termin->toStringPriority(),
                        _('Sichtbarkeit') => $termin->toStringAccessibility(),
                        $termin->toStringRecurrence())
                );
            }
        }
        if ($context === 'sem') {
            // Fetch normal dates
            $course = Course::find($range_id);
            $dates = $course->getDatesWithExdates()->findBy('end_time',  array($start, $start + $timespan), '><');
            foreach ($dates as $courseDate) {

                // Build info
                $info = array();
                if ($courseDate->dozenten[0]) {
                    $info[_('Durchführende Dozenten')] = join(', ', $courseDate->dozenten->getFullname());
                }
                if ($courseDate->statusgruppen[0]) {
                    $info[_('Beteiligte Gruppen')] = join(', ', $courseDate->statusgruppen->getValue('name'));
                }

                // Store for view
                $this->termine[] = array(
                    'id' => $courseDate->id,
                    'chdate' => $courseDate->chdate,
                    'title' => $courseDate->getFullname() . ($courseDate->topics[0] ? ', '.join(', ', $courseDate->topics->getValue('title') ): ""),
                    'description' => $courseDate instanceOf CourseExDate ? $courseDate->content : '',
                    'topics' => $courseDate->topics->toArray('title description'),
                    'room' => $courseDate->getRoomName(),
                    'info' => $info
                );
            }
        }

        // Check permission to edit
        $this->admin = $range_id == $GLOBALS['user']->id || $GLOBALS['perm']->have_studip_perm('tutor', $range_id);

        // Forge title
        if ($this->termine) {
            $this->title = sprintf(_("Termine für die Zeit vom %s bis zum %s"), strftime("%d. %B %Y", $start), strftime("%d. %B %Y", $start + $timespan));
        } else {
            $this->title = _('Termine');
        }

        // Set range_id
        $this->range_id = $range_id;

        // Check out if we are on a profile
        if ($this->admin) {
            $this->isProfile = $context === 'user';
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
    
    public function sidebar_image($slot_module){
        if ($this->plugin->sidebar_images[$slot_module]){
            return $this->plugin->sidebar_images[$slot_module]; 
        } else return $this->plugin->sidebar_images['generic'];
    }    
    
    private function getSeminarDocuments(){
	// Dokumente 
 	//Get File-Folders of Intern Seminar MitarbeiterInnen
    $db = DBManager::get();
    $stmt = $db->prepare("SELECT folder_id, name FROM folder WHERE seminar_id = :cid");
    $stmt->bindParam(":cid", $this->course->id);
    $stmt->execute();
    $this->folder = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $this->folderwithfiles = array();

    foreach ($this->folder as $folder){

        $db = \DBManager::get();
        $stmt = $db->prepare("SELECT * FROM `dokumente` WHERE `range_id` = :range_id
            ORDER BY `name`");
        $stmt->bindParam(":range_id", $folder['folder_id']);
        $stmt->execute();
        $response = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $files = array();

        foreach ($response as $item) {
            $files[] = $item;
        }
        $this->folderwithfiles[$folder['name']] = $files;

    }
        
	return $documents;
    }
    
    private function get_tabs(){
        //Tabs und zugehörige Einstellung laden
		$position = 100;
		foreach( Navigation::getItem('course') as $key=>$tab){
		    //systemtabs anlegen/abfragen
		    $query = "SELECT title FROM `system_tabs` WHERE tab IN (:key)" ;
		    $statement = DBManager::get()->prepare($query);
		    $statement->execute(array('key' => $key));
        	    $orig_title = $statement->fetchAll(PDO::FETCH_ASSOC);
	
		    //Spezialfall Reiter die nur TN sehen (zB Courseware Fortschrittsübersicht)
		    if($key == 'mooc_courseware'){
			$query2 = "SELECT title FROM `system_tabs` WHERE tab IN (:key)" ;
		    	$statement2 = DBManager::get()->prepare($query2);
		    	$statement2->execute(array('key' => 'mooc_progress'));
        	    	$orig_title2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
		    
			if (!$orig_title2[0]){
				$values2 = array('id' => md5('mooc_progress'), 'tab' => 'mooc_progress', 'title' => 'Fortschrittsübersicht');
				$query2 = "INSERT INTO `system_tabs` (`id`, `tab`, `title`) VALUES (:id, :tab, :title)" ;
				$statement2 = DBManager::get()->prepare($query2);
				$statement2->execute($values2);
				$orig_title2[0]['title'] = "Fortschrittsübersicht";
		        }

			
		    	$block = SeminarTab::findOneBySQL('seminar_id = ? AND tab IN (?) ORDER BY position ASC',
                                 array($this->course_id, 'mooc_progress') );
			if ($block){
		    		$this->tabs[] = array('tab' => $block->getValue('tab'), 
						 'title' => $block->getValue('title'),
					  	 'position' => $block->getValue('position'),
						 'orig_title' => $orig_title2[0]['title'],
						 'visible' => $block->getValue('tn_visible') ? 'checked': ''
						);
			} else {
			      $this->tabs[] = array('tab' => 'mooc_progress',
						 'title' => 'Fortschrittsübersicht', 
						 'position' => $position,
						 'orig_title' => $orig_title2[0]['title'],
						 'visible' => 'checked',
					  );
			}
			$position++;
		    } 
		    //Ende Sonderfall


		    
		    
		    if (!$orig_title[0]){
			$values = array('tab' => $key, 'title' => $tab->getTitle());
			$query = "INSERT INTO `system_tabs` (`tab`, `title`) VALUES (:tab, :title)" ;
			$statement = DBManager::get()->prepare($query);
			$statement->execute($values);
			$orig_title[0]['title'] = $tab->getTitle();
		    }
			
		    if(!in_array($tab->getTitle(), $this->ignore_tabs)){
		    	$block = SeminarTab::findOneBySQL('seminar_id = ? AND tab IN (?) ORDER BY position ASC',
                                 array($this->course_id, $key) );
			if ($block && !in_array($key, $this->ignore_tabs)){
		    		$this->tabs[] = array('tab' => $block->getValue('tab'), 
						 'title' => $block->getValue('title'),
					  	 'position' => $block->getValue('position'),
						 'orig_title' => $orig_title[0]['title'],
						 'visible' => $block->getValue('tn_visible') ? 'checked': ''
						);
			} else if (!in_array($key, $this->ignore_tabs)){
			      $this->tabs[] = array('tab' => $key,
						 'title' => $tab->getTitle(), 
						 'position' => $position,
						 'orig_title' => $orig_title[0]['title'],
						 'visible' => 'checked',
					  );
			}
			$position++;
		    } 
		}
	 return $this->array_sort($this->tabs, 'position', SORT_ASC);

    }
    
    function array_sort($array, $on, $order=SORT_ASC)
    {
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
    }
    
    private function setupStudIPNavigation(){
		   
		$block = SeminarTab::findOneBySQL('seminar_id = ? ORDER BY position ASC',
                                 array($this->course->id) );
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
                                     array($this->course->id,$key) );
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
