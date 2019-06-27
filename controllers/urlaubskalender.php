<?php
/*
 * start.php - start page controller
 *
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * @author   André Klaßen <klassen@elan-ev.de>
 * @author   Nadine Werner <nadine.werner@uni-osnabrueck.de>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GPL version 2
 * @category Stud.IP
 * @since    3.1
 */
require_once 'app/controllers/news.php';
//require_once 'app/controllers/calendar/single.php';
require_once 'lib/webservices/api/studip_user.php';
require_once 'app/models/calendar/SingleCalendar.php';
require_once 'lib/models/EventData.class.php';

class UrlaubskalenderController extends StudipController
{
    
    public function __construct($dispatcher)
    {
        parent::__construct($dispatcher);
        $this->plugin = $dispatcher->plugin;
        
        PageLayout::addStylesheet($this->plugin->getPluginUrl() . '/assets/dhtmlxscheduler.css');
        PageLayout::addScript($this->plugin->getPluginURL().'/assets/scripts/dhtmlxscheduler.js');
        PageLayout::addScript($this->plugin->getPluginURL().'/assets/scripts/locale_de.js');
        PageLayout::addScript($this->plugin->getPluginURL().'/assets/scripts/dhtmlxscheduler_timeline.js');
        //ID der Veranstaltung welche als Grundlage für den Kalender verwendet werden soll
//        $this->sem_id = 'b8d02f67fca5aac0efa01fb1782166d1';
//        $this->sem_id = '14ddc9353c17a5c8bf2ccfe1e4c82345';
        $this->sem_id = IntranetConfig::find(Institute::findCurrent()->id)->calendar_seminar;
        //falls keine instituts id verfügbar ist, über nutzer->intranets->zugehörige veranstaltung die sem_id holen wenn möglich
        $this->intranets = IntranetConfig::getIntranetIDsForUser(User::findCurrent());
        $i = 0;
        while (!$this->sem_id && $i < sizeof($this->intranets) ){
            $this->sem_id = IntranetConfig::find($this->intranets[$i])->calendar_seminar;
            $i++;
        }
        $this->mitarbeiter_admin = $GLOBALS['perm']->have_studip_perm('dozent', $this->sem_id);
    }
    
    
    /**
     * Callback function being called before an action is executed.
     */
    function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);

        if (Request::isXhr()) {
            $this->set_layout(null);
            $this->set_content_type('text/html;Charset=windows-1252');
        }

        Navigation::activateItem('/start');
        PageLayout::setTabNavigation(NULL); // disable display of tabs
        PageLayout::setHelpKeyword("Basis.Startseite"); // set keyword for new help
        PageLayout::setTitle(_('Interner Kalender'));
        
        $this->atime = Request::int('atime', time());
        $this->settings = $GLOBALS['user']->cfg->CALENDAR_SETTINGS;
        if (!is_array($this->settings)) {
            $this->settings = Calendar::getDefaultUserSettings();
        }
        
        $this->date_template_engine = new Flexi_TemplateFactory($GLOBALS['STUDIP_BASE_PATH'] . '/app/views');
        
    }

    /**
     * Entry point of the controller that displays the start page of Stud.IP
     *
     * @param string $action
     * @param string $widgetId
     *
     * @return void
     */
    function index_action()
    {
        global $perm;
        $date = time();
        if(Request::option('jmp_date')){
            $date = Request::option('jmp_date');
        }
        $this->date = date('Y-m-d',$date);
        PageLayout::setTitle(_('Interner Urlaubskalender'));
        
        $sidebar = Sidebar::get();
        $sidebar->setImage($this->plugin->getPluginURL()."/assets/images/luggage-klein.jpg");
        $sidebar->setTitle(_("Urlaubskalender"));
    
        $views = new ViewsWidget();
        $views->addLink(_('Kalenderansicht'),
                        $this->url_for('urlaubskalender'))
                    ->setActive(true);
        $views->addLink(_('Zeitstrahl-Ansicht'),
                        $this->url_for('urlaubskalender/timeline'));
//        $views->addLink(_('Wochenensicht gesamt'),
//                        $GLOBALS['ABSOLUTE_URI_STUDIP'] . "dispatch.php/calendar/single/week?cid=" . $this->sem_id);
        if ($this->mitarbeiter_admin){
                $views->addLink(_('Urlaubstermine bearbeiten'),
                          $this->url_for('urlaubskalender/'. (!$this->mitarbeiter_admin ? ('edituser/'.$GLOBALS['user']->id) : 'edit')));
        }
        $sidebar->addWidget($views);
            
        // Show action to add widget only if not all widgets have already been added.
        $actions = new ActionsWidget();
                        
        $actions->addLink(_('Neuen Urlaubstermin eintragen'),
                    $this->url_for('urlaubskalender/'. (!$this->mitarbeiter_admin ? ('edituser/'.$GLOBALS['user']->id) : 'new_vacation')),
                    //$GLOBALS['ABSOLUTE_URI_STUDIP'] . "dispatch.php/calendar/single/edit/" . $this->sem_id,
                    Icon::create('add', 'clickable'))->asDialog(['size=small']);

        $sidebar->addWidget($actions);
        
        $tmpl_factory = $this->get_template_factory();

        $filters = new OptionsWidget();
        $filters->setTitle('Auswahl');

        $tmpl = $tmpl_factory->open('urlaubskalender/_jump_to.php');
        $tmpl->atime = time();
        $tmpl->action_url = $this->url_for('urlaubskalender');
        $filters->addElement(new WidgetElement($tmpl->render()));
        
        Sidebar::get()->addWidget($filters);
        
        $this->dates = $this->events_of_type(13);
        //$this->dates = IntranetDate::findBySQL("type = 'urlaub'");

        // Root may set initial positions
        if ($GLOBALS['perm']->have_perm('root')) {

        }

    }
    
    
    /**
     * Entry point of the controller that displays the start page of Stud.IP
     *
     * @param string $action
     * @param string $widgetId
     *
     * @return void
     */
    function timeline_action($action = false, $widgetId = null)
    {
        $sidebar = Sidebar::get();
        $sidebar->setImage($this->plugin->getPluginURL()."/assets/images/luggage-klein.jpg");
        $sidebar->setTitle(_("Urlaubskalender"));
        $views = new ViewsWidget();
        $views->addLink(_('Kalenderansicht'),
                        $this->url_for('urlaubskalender'));
        $views->addLink(_('Zeitstrahl-Ansicht'),
                        $this->url_for('urlaubskalender/timeline'))->setActive(true);
        if ($this->mitarbeiter_admin){
            $views->addLink(_('Urlaubstermine bearbeiten'),
                          $this->url_for('urlaubskalender/'. (!$this->mitarbeiter_admin ? ('edituser/'.$GLOBALS['user']->id) : 'edit')));
        }
        $sidebar->addWidget($views);
        $this->dates = $this->events_of_type(13);
        
        //für die Darstellung in der Timeline braucht man Integer keys für die Labels
        $this->keys = array();
        $cnt = 0;
        foreach($this->dates as $date){
            if (!array_key_exists($date->author_id ,$this->keys)){
                $this->keys[$date->author_id] = $cnt;
                $cnt++;
            }
        }

        // Root may set initial positions
        if ($GLOBALS['perm']->have_perm('root')) {

        }

    }

     function insertDate_action($id = ''){
        
         if (Request::submitted('submit')){
                $this->event = new EventData();
                var_dump('sopecihern');
                $this->event = new EventData($id);
                $this->event->author_id = $GLOBALS['user']->id;
                $this->event->start = strtotime($_POST['start_date']);
                $this->event->end = $this->event->start;
                $this->event->summary = studip_utf8decode($_POST['summary']);
                $this->event->description = $_POST['description'];
                $this->event->class = 'PUBLIC';
                $this->event->category_intern = '13';

                $this->event->store();
             }
//             if (Request::isXhr()) {
//                    header('X-Dialog-Close: 1');
//                    exit;
//             } else $this->redirect($this->url_for('/intranet_start'));
        
        //bearbeiten
        else if ($id){
            $this->event = new EventData($id);
             var_dump('laden');
            
        
        // neu anlegen
        } else {
            $this->event = new EventData();
            $this->event->start = time();
            $this->event->summary = 'Kurstitel';
            $this->event->description = 'http://';
        }
        //$this->setProperties($calendar_event, $component);
        //$calendar_event->setRecurrence($component['RRULE']);
    }
    
    public function week_action($range_id = null)
    {
        $this->range_id = $range_id ?: $this->range_id;
        $timestamp = mktime(12, 0, 0, date('n', $this->atime),
                date('j', $this->atime), date('Y', $this->atime));
        $monday = $timestamp - 86400 * (strftime('%u', $timestamp) - 1);
        $day_count = $this->settings['type_week'] == 'SHORT' ? 5 : 7;
        for ($i = 0; $i < $day_count; $i++) {
            
            $this->calendars[$i] =
                    SingleCalendar::getDayCalendar($this->range_id,//$this->range_id,
                            $monday + $i * 86400, null, $this->restrictions);
        }
        
        //PageLayout::setTitle($this->getTitle($this->calendars[0],  _('Wochenansicht')));

        $this->last_view = 'week';
        
        $this->date_template_engine->render('calendar/single/week', ['calendars' => $this->calendars,
            'controller' => $this]); //new Calendar_SingleController()]);
        //$this->createSidebar('week', $this->calendars[0]);
        //$this->createSidebarFilter();
    }
    
    
    public function edit_action($range_id = null, $event_id = null)
    {
        $sidebar = Sidebar::get();
        $sidebar->setImage($this->plugin->getPluginURL()."/assets/images/luggage-klein.jpg");
        $sidebar->setTitle(_("Urlaubskalender"));
        $views = new ViewsWidget();
        $views->addLink(_('Kalenderansicht'),
                        $this->url_for('urlaubskalender'));
        $views->addLink(_('Zeitstrahl-Ansicht'),
                        $this->url_for('urlaubskalender/timeline'));
        if ($this->mitarbeiter_admin){
            $views->addLink(_('Urlaubstermine bearbeiten'),
                          $this->url_for('urlaubskalender/'. (!$this->mitarbeiter_admin ? ('edituser/'.$GLOBALS['user']->id) : 'edit')))->setActive(true);
        }
        $sidebar->addWidget($views);
        $this->dates = $this->events_of_type(13);

       
    }

    /**
     *  TODO: raus, wird nicht mehr benutzt
     *  This action adds a holiday entry
     *
     * @return void
     */
    public function myedit_action($id = '')
    {
        PageLayout::setTitle(_('Neuen Urlaubstermin eintragen'));

        global $perm;
        $this->mitarbeiter_admin = $perm->have_studip_perm('tutor', $this->sem_id);
        
        $sidebar = Sidebar::get();
        $sidebar->setImage($this->plugin->getPluginURL()."/assets/assets/images/luggage-klein.jpg");
        $sidebar->setTitle(_("Urlaubskalender"));

            
            $views = new ViewsWidget();
        $views->addLink(_('Kalenderansicht'),
                        $this->url_for('urlaubskalender'));
        $views->addLink(_('Zeitstrahl-Ansicht'),
                        $this->url_for('urlaubskalender/timeline'));
        $sidebar->addWidget($views);
            
            // Show action to add widget only if not all widgets have already been added.
            $actions = new ActionsWidget();

            $actions->addLink(_('Neuen Urlaubstermin eintragen'),
                            $this->url_for('urlaubskalender/'. (!$this->mitarbeiter_admin ? ('edituser/'.$GLOBALS['user']->id) : 'new_vacation')),  
                            //$GLOBALS['ABSOLUTE_URI_STUDIP'] . "dispatch.php/calendar/single/edit/" . $this->sem_id,
                          Icon::create('add', 'clickable'), ['data-dialog']);
            
            $actions->addLink(_('Urlaubstermine bearbeiten'),
                              $this->url_for('urlaubskalender/'. (!$this->mitarbeiter_admin ? ('edituser/'.$GLOBALS['user']->id) : 'new')),
                              Icon::create('edit', 'clickable'));

            $sidebar->addWidget($actions);
        
        
        $this->help = _('Sie können nach Name, Vorname oder eMail-Adresse suchen');

        $search_obj = new SQLSearch("SELECT auth_user_md5.user_id, CONCAT(auth_user_md5.nachname, ', ', auth_user_md5.vorname, ' (' , auth_user_md5.email, ')' ) as fullname "
                            . "FROM auth_user_md5 "
                            . "LEFT JOIN user_info ON (auth_user_md5.user_id = user_info.user_id) "
                            . "LEFT JOIN seminar_user ON (auth_user_md5.user_id = seminar_user.user_id) "
                            . "WHERE "
                            . "seminar_user.Seminar_id LIKE '". $this->id . "' "
                            . "AND (username LIKE :input OR Vorname LIKE :input "
                            . "OR CONCAT(Vorname,' ',Nachname) LIKE :input "
                            . "OR CONCAT(Nachname,' ',Vorname) LIKE :input "
                            . "OR Nachname LIKE :input OR {$GLOBALS['_fullname_sql']['full_rev']} LIKE :input) "
                            . " ORDER BY fullname ASC",
                _('Nutzer suchen'), 'user_id');
        $this->quick_search = QuickSearch::get('user_id', $search_obj);   
        
    
    }
    
    //vacation
    public function new_vacation_action($id = '')
    {
    
        //$this->id = '568fce7262620700103ce1657cabc5e3';
        global $perm;
        $this->mitarbeiter_admin = $perm->have_studip_perm('tutor', $this->sem_id);
        
        $user_id = Request::get('user_id');
        
        if($id){
            $this->entry = EventData::find($id);
            $this->user = User::find($this->entry->author_id);
        } else if ($user_id){
            $this->user = User::find($user_id);
        }
      
        $this->help = _('Sie können nach Name, Vorname oder eMail-Adresse suchen' . $this->id);

        $search_obj = new SQLSearch("SELECT auth_user_md5.user_id, CONCAT(auth_user_md5.nachname, ', ', auth_user_md5.vorname, ' (' , auth_user_md5.email, ')' ) as fullname "
                            . "FROM auth_user_md5 "
                            . "LEFT JOIN user_info ON (auth_user_md5.user_id = user_info.user_id) "
                            . "LEFT JOIN seminar_user ON (auth_user_md5.user_id = seminar_user.user_id) "
                            . "WHERE "
                            . "seminar_user.Seminar_id LIKE '". $this->sem_id . "' "
                            . "AND (username LIKE :input OR Vorname LIKE :input "
                            . "OR CONCAT(Vorname,' ',Nachname) LIKE :input "
                            . "OR CONCAT(Nachname,' ',Vorname) LIKE :input "
                            . "OR Nachname LIKE :input OR {$GLOBALS['_fullname_sql']['full_rev']} LIKE :input) "
                            . " ORDER BY fullname ASC",
                _('Nutzer suchen'), 'user_id');
        $this->quick_search = QuickSearch::get('user_id', $search_obj)
                 ->setInputStyle("width: 240px")
                 ->defaultValue( $user_id, $this->user->username);


        $this->render_action('new');
    }

 
    /**
     *  This action adds a holiday entry
     *
     * @return void
     */
    public function new_birthday_action($id = '')
    {
        PageLayout::setTitle(_('Neuen Geburtstag eintragen'));
        $user_id = Request::get('user_id');
        
        if($id){
            $this->date = EventData::find($id);
            $this->user = User::find($this->date->author_id);
        } else if ($user_id){
            $this->user = User::find($user_id);
            //gibt es zu diesem nutzer schon einen termin vom typ geburtstag?
            $this->date = EventData::findOneBySQL('author_id = ? AND category_intern = 11', [$user_id]);
            //TODO: sollte eigentlich auch zur selben veranstaltung gehören
        }

        global $perm;
        $this->mitarbeiter_hilfskraft = $perm->have_studip_perm('tutor', $this->sem_id);

        $this->help = _('Sie können nach Name, Vorname oder eMail-Adresse suchen');
        
        //da hier nur MA eingeatragen werden können die in der zugehörigen VA sind sollte der Zentrale Kalender 
        //nur in VA mit Auto-eintrag aktivierbar sein
        $search_obj = new SQLSearch("SELECT auth_user_md5.user_id, CONCAT(auth_user_md5.nachname, ', ', auth_user_md5.vorname, ' (' , auth_user_md5.email, ')' ) as fullname "
                            . "FROM auth_user_md5 "
                            . "LEFT JOIN user_info ON (auth_user_md5.user_id = user_info.user_id) "
                            . "LEFT JOIN seminar_user ON (auth_user_md5.user_id = seminar_user.user_id) "
                            . "WHERE "
                            . "seminar_user.Seminar_id LIKE '". $this->sem_id . "' "
                            . "AND (username LIKE :input OR Vorname LIKE :input "
                            . "OR CONCAT(Vorname,' ',Nachname) LIKE :input "
                            . "OR CONCAT(Nachname,' ',Vorname) LIKE :input "
                            . "OR Nachname LIKE :input OR {$GLOBALS['_fullname_sql']['full_rev']} LIKE :input) "
                            . " ORDER BY fullname ASC",
                _('Nutzer suchen'), 'user_id');
        //$this->quick_search = QuickSearch::get('user_id', $search_obj);
        $this->quick_search = QuickSearch::get('user_id', $search_obj)
                        ->setInputStyle("width: 240px")
                        //->fireJSFunctionOnSelect('doktoranden_select')
                        ->defaultValue( $user_id, $this->user->username)
                        ->withButton();

        $this->render_action('new_birthday');
        
    
    }
    
 

    public function save_birthday_action($id = NULL) {
        $date_id = Request::get('date_id');
        $date = DateTime::createFromFormat('d.m.', Request::get('birthday'));
        $user = User::find(Request::get('user_id'));
        if($entry = EventData::find($date_id)){
            $entry->start = $date->getTimestamp();
            $entry->end = $date->getTimestamp();
            $entry->month = $date->format('m');
            $entry->day = $date->format('d');
            $entry->store();
            PageLayout::postMessage(MessageBox::success(_('Der Eintrag wurde gespeichert.')));
        
        } else if ($user) {
            $entry = new EventData();
            $entry->author_id = $user->id;
            $entry->editor_id = $user->id;
            $entry->start = $date->getTimestamp();
            $entry->end = $date->getTimestamp();
            $entry->rtype = 'YEARLY';
            $entry->month = $date->format('m');
            $entry->day = $date->format('d');
            $entry->category_intern = 11;
            $entry->summary =  $user->vorname . ' ' . $user->nachname;
            $entry->store();
            $event = new CalendarEvent();
            $event->range_id = $this->sem_id;
            $event->event_id = $entry->event_id;
            $event->mkdate = time();
            $event->chdate = time();
            $event->store();
            PageLayout::postMessage(MessageBox::success(_('Der Eintrag wurde gespeichert.')));
        }
        
        $this->redirect($this->url_for('/urlaubskalender/birthday'));

    }
    
    public function save_vacation_action($id = NULL) {
        $id = Request::get('event_id');
        $begin_date = DateTime::createFromFormat('d.m.Y', Request::get('begin'));
        $end_date = DateTime::createFromFormat('d.m.Y', Request::get('end'));
        $user = User::find(Request::get('user_id'));
        if($entry = EventData::find($id)){
            $entry->start = $begin_date->getTimestamp();
            $entry->end = $end_date->getTimestamp();
            $entry->store();
            PageLayout::postMessage(MessageBox::success(_('Der Eintrag wurde gespeichert.')));
        
        } else if ($user){
            $entry = new EventData();
            $entry->author_id = $user->id;
            $entry->editor_id = $user->id;
            $entry->start = $begin_date->getTimestamp();
            $entry->end = $end_date->getTimestamp();
            $entry->rtype = 'SINGLE';
            $entry->category_intern = 13;
            $entry->summary =  $user->vorname . ' ' . $user->nachname;
            $entry->description =  Request::get('notice');
            $entry->store();
            $event = new CalendarEvent();
            $event->range_id = $this->sem_id;
            $event->event_id = $entry->event_id;
            $event->mkdate = time();
            $event->chdate = time();
            $event->store();
            PageLayout::postMessage(MessageBox::success(_('Der Eintrag wurde gespeichert.')));
        }
        
        $this->redirect($this->url_for('/urlaubskalender/edit'));
   
        
    }

    /**
     * TODO umschreiben
     *  This actions removes a holiday entry
     *
     *
     * @return void
     */
    function delete_action($id)
    {
        if($entry = EventData::find($id)){
            $event = CalendarEvent::findOneByEvent_id($id);
            $event->delete();
            $entry->delete();
            PageLayout::postMessage(MessageBox::success(_('Der Eintrag wurde gelöscht.')));
        }
        
        $this->redirect($this->url_for('/urlaubskalender/edit'));
    }
    
    function delete_birthday_action($id)
    {
        if($entry = EventData::find($id)){
            $event = CalendarEvent::findOneByEvent_id($id);
            $event->delete();
            $entry->delete();
            PageLayout::postMessage(MessageBox::success(_('Der Eintrag wurde gelöscht.')));
        }
        
        $this->redirect($this->url_for('/urlaubskalender/birthday'));
    }


    
     function url_for($to)
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
    
    function color_by_crossfoot ( $digits )
  {
    // Typcast falls Integer uebergeben
    $strDigits = ( string ) hexdec(substr(md5($digits),0,8));

    $colors = array('1' => '#1e90ff',
                    '2' => '#008000',
                    '3' => '#b22222',
                    '4' => '#9370db',
                    '5' => '#008b8b',
                    '6' => '#6495ed',
                    '7' => '#d2691e',
                    '8' => '#2f4f4f',
                    '9' => '#4b0082',
                    '0' => '#778899',
        );
    
    
    return $colors[$strDigits%10];
  } 
  
  function birthday_action()
    {
        global $perm;

        $this->mitarbeiter_hilfskraft = $perm->have_studip_perm('tutor', $this->sem_id);

        $sidebar = Sidebar::get();
        $sidebar->setImage($this->plugin->getPluginURL()."/assets/images/klee_klein.jpg");
        $sidebar->setTitle(_("Geburtstage"));

            
        $views = new ViewsWidget();
        $views->addLink(_('Kalenderansicht'),
                        $this->url_for('urlaubskalender/birthday'))
                    ->setActive(true);
        $sidebar->addWidget($views);
            
        // Show action to add widget only if not all widgets have already been added.
        $actions = new ActionsWidget();

        $actions->addLink(_('Neuen Geburtstag eintragen'),
                          $this->url_for('urlaubskalender/new_birthday'),
                          Icon::create('add', 'clickable'))->asDialog('size=auto'); 

        $sidebar->addWidget($actions);


        //$this->dates = IntranetDate::findBySQL("type = 'birthday'");
        $this->dates = $this->events_of_type(11);

        // Root may set initial positions
        if ($GLOBALS['perm']->have_perm('root')) {

        }

    }
    
    //TODO wird das benutzt?
    function events_action($type = 'all'){
        
        $this->events[] = $this->events_of_type();
        
        
        
        //the following sucks
        //$this->calendar = new SingleCalendar($this->sem_id);
        //$this->events = $this->calendar->getEvents()->events->toGroupedArray();
          
        //$this->setProperties($calendar_event, $component);
        //$calendar_event->setRecurrence($component['RRULE']);
    }
    
    
    private function events_of_type($type = 'all', $begin_time = 1111111111){
        
        $calendar_events = self::getEventsByInterval($this->sem_id, $begin_time, 3333333333);
            
        if ($type == 'all'){
            foreach ($calendar_events as $calendar_event){
                $events[] = EventData::findOneByEvent_id($calendar_event['event_id']);
            }
        } else {
            foreach ($calendar_events as $calendar_event){
                $data = EventData::findOneByEvent_id($calendar_event['event_id']);
                if ($data->category_intern == $type){
                    $events[] = $data;
                }
            }
        }
        return $events;
        
    }
    
    public static function getEventsByInterval($range_id, $start, $end)
    {
        $stmt = DBManager::get()->prepare('SELECT * FROM calendar_event '
                . 'INNER JOIN event_data USING(event_id) '
                . 'WHERE range_id = :range_id '
                . 'AND ((start BETWEEN :start AND :end) OR '
                . "(start <= :end AND (expire + end - start) >= :start AND rtype != 'SINGLE') "
                . 'OR (:start BETWEEN start AND end)) '
                . 'ORDER BY start ASC');
        $stmt->execute(array(
            ':range_id' => $range_id,
            ':start'    => $start,
            ':end'      => $end
        ));
        $i = 0;
        $event_collection = new SimpleORMapCollection();
        $event_collection->setClassName('Event');
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $event_collection[$i] = new CalendarEvent();
            $event_collection[$i]->setData($row);
            $event_collection[$i]->setNew(false);
            $event = new EventData();
            $event->setData($row);
            $event->setNew(false);
            $event_collection[$i]->event = $event;
            $i++;
        }
        return $event_collection;
    }
  
    public static function getEventsByDayAndMonth($range_id, $day, $month)
    {
        $stmt = DBManager::get()->prepare('SELECT event_id FROM calendar_event '
                . 'LEFT JOIN event_data USING(event_id) '
                . 'WHERE range_id = :range_id '
                . 'AND (day = :day AND month = :month) '
                . 'ORDER BY start ASC');
        $stmt->execute(array(
            ':range_id' => $range_id,
            ':day'      => $day,
            ':month'    => $month
        ));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
