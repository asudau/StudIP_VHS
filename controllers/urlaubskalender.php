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
        $this->sem_id = 'b8d02f67fca5aac0efa01fb1782166d1';
        $this->sem_id = '14ddc9353c17a5c8bf2ccfe1e4c82345';
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
        
        $this->mitarbeiter_admin = $perm->have_studip_perm('dozent', $this->sem_id);

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
        $sidebar->addWidget($views);
            
        // Show action to add widget only if not all widgets have already been added.
        $actions = new ActionsWidget();
                        
        $actions->addLink(_('Neuen Urlaubstermin eintragen'),
                    $this->url_for('urlaubskalender/'. (!$this->mitarbeiter_admin ? ('edituser/'.$GLOBALS['user']->id) : 'new_vacation')),
                    //$GLOBALS['ABSOLUTE_URI_STUDIP'] . "dispatch.php/calendar/single/edit/" . $this->sem_id,
                          Icon::create('add', 'clickable'), ["rel" => "get_dialog", "dialog-title" => 'Termin anlegen']);

        $actions->addLink(_('Urlaubstermine bearbeiten'),
                          $this->url_for('urlaubskalender/'. (!$this->mitarbeiter_admin ? ('edituser/'.$GLOBALS['user']->id) : 'edit')),
                          Icon::create('edit', 'clickable'));
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
        //alle Einträge der Tabelle
        //$this->dates = IntranetDate::findBySQL('1=1');
        $this->dates = $this->events_of_type(13);
        
        //für die Darstellung in der Timeline braucht man Integer keys für die Labels
        $this->keys = array();
        $cnt = 0;
        foreach($this->dates as $date){
            if (!array_key_exists($date->summary ,$this->keys)){
                $this->keys[$date->summary] = $cnt;
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
        $this->range_id = $range_id ?: $this->range_id;
        $this->calendar = new SingleCalendar($this->range_id);
        $this->event = $this->calendar->getEvent($event_id);

        if ($this->event->isNew()) {
         //   $this->event = $this->calendar->getNewEvent();
            if (Request::get('isdayevent')) {
                $this->event->setStart(mktime(0, 0, 0, date('n', $this->atime),
                        date('j', $this->atime), date('Y', $this->atime)));
                $this->event->setEnd(mktime(23, 59, 59, date('n', $this->atime),
                        date('j', $this->atime), date('Y', $this->atime)));
            } else {
                $this->event->setStart($this->atime);
                $this->event->setEnd($this->atime + 3600);
            }
            $this->event->setAuthorId($GLOBALS['user']->id);
            $this->event->setEditorId($GLOBALS['user']->id);
            $this->event->setAccessibility('PRIVATE');
//            if (!Request::isXhr()) {
//                PageLayout::setTitle($this->getTitle($this->calendar, _('Neuer Termin')));
//            }
        } else {
            // open read only events and course events not as form
            // show information in dialog instead
            if (!$this->event->havePermission(Event::PERMISSION_WRITABLE)
                    || $this->event instanceof CourseEvent) {
                if (!$this->event instanceof CourseEvent && $this->event->attendees->count() > 1) {
                    if ($this->event->group_status) {
                        $this->redirect($this->url_for('calendar/single/edit_status/' . implode('/',
                            array($this->range_id, $this->event->event_id))));
                    } else {
                        $this->redirect($this->url_for('calendar/single/event/' . implode('/',
                            array($this->range_id, $this->event->event_id))));
                    }
                } else {
                    $this->redirect($this->url_for('calendar/single/event/' . implode('/',
                            array($this->range_id, $this->event->event_id))));
                }
                return null;
            }
//            if (!Request::isXhr()) {
//                PageLayout::setTitle($this->getTitle($this->calendar, _('Termin bearbeiten')));
//            }
        }

        if (Config::get()->CALENDAR_GROUP_ENABLE
                && $this->calendar->getRange() == Calendar::RANGE_USER) {

            if (Config::get()->CALENDAR_GRANT_ALL_INSERT) {
                $search_obj = SQLSearch::get("SELECT DISTINCT auth_user_md5.user_id, "
                    . "{$GLOBALS['_fullname_sql']['full_rev_username']} as fullname, "
                    . "auth_user_md5.perms, auth_user_md5.username "
                    . "FROM auth_user_md5 "
                    . "LEFT JOIN user_info ON (auth_user_md5.user_id = user_info.user_id) "
                    . 'WHERE auth_user_md5.user_id <> ' . DBManager::get()->quote($GLOBALS['user']->id)
                    . ' AND (username LIKE :input OR Vorname LIKE :input '
                    . "OR CONCAT(Vorname,' ',Nachname) LIKE :input "
                    . "OR CONCAT(Nachname,' ',Vorname) LIKE :input "
                    . "OR Nachname LIKE :input OR {$GLOBALS['_fullname_sql']['full_rev']} LIKE :input "
                    . ") ORDER BY fullname ASC",
                    _('Person suchen'), 'user_id');
            } else {
                $search_obj = SQLSearch::get("SELECT DISTINCT auth_user_md5.user_id, "
                    . "{$GLOBALS['_fullname_sql']['full_rev_username']} as fullname, "
                    . "auth_user_md5.perms, auth_user_md5.username "
                    . "FROM calendar_user "
                    . "LEFT JOIN auth_user_md5 ON calendar_user.owner_id = auth_user_md5.user_id "
                    . "LEFT JOIN user_info ON (auth_user_md5.user_id = user_info.user_id) "
                    . 'WHERE calendar_user.user_id = '
                    . DBManager::get()->quote($GLOBALS['user']->id)
                    . ' AND calendar_user.permission > ' . Event::PERMISSION_READABLE
                    . ' AND auth_user_md5.user_id <> ' . DBManager::get()->quote($GLOBALS['user']->id)
                    . ' AND (username LIKE :input OR Vorname LIKE :input '
                    . "OR CONCAT(Vorname,' ',Nachname) LIKE :input "
                    . "OR CONCAT(Nachname,' ',Vorname) LIKE :input "
                    . "OR Nachname LIKE :input OR {$GLOBALS['_fullname_sql']['full_rev']} LIKE :input "
                    . ") ORDER BY fullname ASC",
                    _('Person suchen'), 'user_id');
            }

            // SEMBBS
            // Eintrag von Terminen bereits ab PERMISSION_READABLE
            /*
            $search_obj = new SQLSearch('SELECT DISTINCT auth_user_md5.user_id, '
                . $GLOBALS['_fullname_sql']['full_rev'] . ' as fullname, username, perms '
                . 'FROM calendar_user '
                . 'LEFT JOIN auth_user_md5 ON calendar_user.owner_id = auth_user_md5.user_id '
                . 'LEFT JOIN user_info ON (auth_user_md5.user_id = user_info.user_id) '
                . 'WHERE calendar_user.user_id = '
                . DBManager::get()->quote($GLOBALS['user']->id)
                . ' AND calendar_user.permission >= ' . Event::PERMISSION_READABLE
                . ' AND (username LIKE :input OR Vorname LIKE :input '
                . "OR CONCAT(Vorname,' ',Nachname) LIKE :input "
                . "OR CONCAT(Nachname,' ',Vorname) LIKE :input "
                . 'OR Nachname LIKE :input OR '
                . $GLOBALS['_fullname_sql']['full_rev'] . ' LIKE :input '
                . ') ORDER BY fullname ASC',
                _('Nutzer suchen'), 'user_id');
            // SEMBBS
             *
             */


            $this->quick_search = QuickSearch::get('user_id', $search_obj)
                    ->fireJSFunctionOnSelect('STUDIP.Messages.add_adressee')
                    ->withButton();

      //      $default_selected_user = array($this->calendar->getRangeId());
            $this->mps = MultiPersonSearch::get('add_adressees')
                ->setLinkText(_('Mehrere Teilnehmer hinzufügen'))
       //         ->setDefaultSelectedUser($default_selected_user)
                ->setTitle(_('Mehrere Teilnehmer hinzufügen'))
                ->setExecuteURL($this->url_for($this->base . 'edit'))
                ->setJSFunctionOnSubmit('STUDIP.Messages.add_adressees')
                ->setSearchObject($search_obj);
            $owners = SimpleORMapCollection::createFromArray(
                    CalendarUser::findByUser_id($this->calendar->getRangeId()))
                    ->pluck('owner_id');
            foreach (Calendar::getGroups($GLOBALS['user']->id) as $group) {
                $this->mps->addQuickfilter(
                    $group->name,
                    $group->members->filter(
                        function ($member) use ($owners) {
                            if (in_array($member->user_id, $owners)) {
                                return $member;
                            }
                        })->pluck('user_id')
                );
            }
        }

        $stored = false;
        if (Request::submitted('store')) {
            $stored = $this->storeEventData($this->event, $this->calendar);
        }

        if ($stored !== false) {
            if ($stored === 0) {
                if (Request::isXhr()) {
                    header('X-Dialog-Close: 1');
                    exit;
                } else {
                    PageLayout::postMessage(MessageBox::success(_('Der Termin wurde nicht geändert.')));
                    $this->relocate('calendar/single/' . $this->last_view, array('atime' => $this->atime));
                }
            } else {
                PageLayout::postMessage(MessageBox::success(_('Der Termin wurde gespeichert.')));
                $this->relocate('calendar/single/' . $this->last_view, array('atime' => $this->atime));
            }
        }

//        $this->createSidebar('edit', $this->calendar);
//        $this->createSidebarFilter();
        
        $this->template = $this->date_template_engine->render('calendar/single/edit', ['event' => $this->event,
            'calendar' => $this->calendar,
            'controller' => $this]);
    }


    
    
    /**
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
                          Icon::create('add', 'clickable'), ["rel" => "get_dialog", "dialog-title" => 'Termin anlegen']);
            
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
    
        PageLayout::setTitle(_('Neuen Urlaubstermin eintragen'));
        //$this->id = '568fce7262620700103ce1657cabc5e3';
        global $perm;
        $this->mitarbeiter_admin = $perm->have_studip_perm('tutor', $this->sem_id);

        $sidebar = Sidebar::get();
        $sidebar->setImage($this->plugin->getPluginURL()."/assets/images/luggage-klein.jpg");
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
                          Icon::create('add', 'clickable'), ["rel" => "get_dialog", "dialog-title" => 'Termin anlegen']);

            $actions->addLink(_('Urlaubstermine bearbeiten'),
                              $this->url_for('urlaubskalender/'. (!$this->mitarbeiter_admin ? ('edituser/'.$GLOBALS['user']->id) : 'edit')),
                              Icon::create('edit', 'clickable'));

            $sidebar->addWidget($actions);

        $this->help = _('Sie können nach Name, Vorname oder eMail-Adresse suchen' . $this->id);

        $search_obj = new SQLSearch("SELECT auth_user_md5.user_id, CONCAT(auth_user_md5.nachname, ', ', auth_user_md5.vorname, ' (' , auth_user_md5.email, ')' ) as fullname "
                            . "FROM auth_user_md5 "
                            . "LEFT JOIN user_info ON (auth_user_md5.user_id = user_info.user_id) "
                            . "LEFT JOIN seminar_user ON (auth_user_md5.user_id = seminar_user.user_id) "
                            . "WHERE "
                            . "seminar_user.Seminar_id IN (". $this->id . ") "
                            . "AND (username LIKE :input OR Vorname LIKE :input "
                            . "OR CONCAT(Vorname,' ',Nachname) LIKE :input "
                            . "OR CONCAT(Nachname,' ',Vorname) LIKE :input "
                            . "OR Nachname LIKE :input OR {$GLOBALS['_fullname_sql']['full_rev']} LIKE :input) "
                            . " ORDER BY fullname ASC",
                _('Nutzer suchen'), 'user_id');
        $this->quick_search = QuickSearch::get('user_id', $search_obj);


        $this->render_action('new');
    }

 
    /**
     *  This action adds a holiday entry
     *
     * @return void
     */
    public function new_birthday_action($user_id = '', $id = '')
    {
        PageLayout::setTitle(_('Neuen Geburtstag eintragen'));
        
        if ($id){
            $this->date = EventData::find($id);
        }
        if ($user_id){
            $this->user = User::find($user_id);
        }

        global $perm;
        $this->mitarbeiter_hilfskraft = $perm->have_studip_perm('tutor', $this->sem_id);
        
        $sidebar = Sidebar::get();
        $sidebar->setImage($this->plugin->getPluginURL()."/assets/images/klee_klein.jpg");
        $sidebar->setTitle(_("Geburtstage"));

            
        $views = new ViewsWidget();
        $views->addLink(_('Kalenderansicht'),
                        $this->url_for('urlaubskalender/birthday')); 
        $sidebar->addWidget($views);
            
            // Show action to add widget only if not all widgets have already been added.
            $actions = new ActionsWidget();

            $actions->addLink(_('Neuen Geburtstag eintragen'),
                              $this->url_for('urlaubskalender/new_birthday'),
                              Icon::create('add', 'clickable'))->asDialog('size=medium'); 
            
            $actions->addLink(_('Geburtstag bearbeiten'),
                              $this->url_for('urlaubskalender/'. (!$this->mitarbeiter_hilfskraft ? ('edituser_birthday/'.$GLOBALS['user']->id) : 'edit_birthday')),
                              Icon::create('edit', 'clickable'))->asDialog('size=medium'); 
            
            $sidebar->addWidget($actions);
       
        
        
        $this->help = _('Sie können nach Name, Vorname oder eMail-Adresse suchen');
      
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
                    ->fireJSFunctionOnSelect('birthday_select_user_id')
                    ->withButton();
        
        $this->render_action('new_birthday');
        
    
    }
    
    /**
     *  This action adds a holiday entry
     *
     * @return void
     */
    public function edit_birthday_action($id = '')
    {
        PageLayout::setTitle(_('Geburtstag eintragen'));

         global $perm;
        $this->mitarbeiter_hilfskraft = $perm->have_studip_perm('tutor', $this->sem_id);
        
        $sidebar = Sidebar::get();
        $sidebar->setImage($this->plugin->getPluginURL()."/assets/images/klee_klein.jpg");
        $sidebar->setTitle(_("Geburtstage"));

            
            $views = new ViewsWidget();
        $views->addLink(_('Kalenderansicht'),
                        $this->url_for('urlaubskalender/birthday'));
        $sidebar->addWidget($views);
            
            // Show action to add widget only if not all widgets have already been added.
            $actions = new ActionsWidget();

            $actions->addLink(_('Neuen Geburtstag eintragen'),
                              $this->url_for('urlaubskalender/new_birthday'),
                              Icon::create('add', 'clickable'))->asDialog('size=medium'); 
            
            $actions->addLink(_('Geburtstag bearbeiten'),
                              $this->url_for('urlaubskalender/'. (!$this->mitarbeiter_hilfskraft ? ('edituser_birthday/'.$GLOBALS['user']->id) : 'edit_birthday')),
                              Icon::create('edit', 'clickable'))->asDialog('size=medium'); 

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
    
     /**
     *  This action adds a holiday entry
     *
     * @return void
     */
    public function edituser_action($id = '')
    {
        PageLayout::setTitle(_('Neuen Urlaubstermin eintragen'));

        global $perm;
        $this->mitarbeiter_admin = $perm->have_studip_perm('dozent', $this->sem_id);
        
        $sidebar = Sidebar::get();
        $sidebar->setImage($this->plugin->getPluginURL()."/assets/images/luggage-klein.jpg");
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
                              $this->url_for('urlaubskalender/new'),
                              Icon::create('add', 'clickable'));
            
            $actions->addLink(_('Urlaubstermine bearbeiten'),
                              $this->url_for('urlaubskalender/'. (!$this->mitarbeiter_admin ? ('edituser/'.$GLOBALS['user']->id) : 'edit')),
                              Icon::create('edit', 'clickable'));

            $sidebar->addWidget($actions);
       
        
        
        $this->user_id = $id ? $id : $_POST['user_id'];
        
        if (!$this->user_id){
            $this->render_action('new');
        }
        
        $this->entries = IntranetDate::findBySQL('user_id = ? ORDER BY begin ASC',
                    array($this->user_id));
        
        $this->render_action('edituser');
        
    
    }
    
     public function edituser_birthday_action($id = '')
    {
        PageLayout::setTitle(_('Geburtstag eintragen'));

        global $perm;
        $this->mitarbeiter_hilfskraft = $perm->have_studip_perm('tutor', $this->sem_id);
        
        $sidebar = Sidebar::get();
        $sidebar->setImage($this->plugin->getPluginURL()."/assets/images/klee_klein.jpg");
        $sidebar->setTitle(_("Geburtstage"));

            
            $views = new ViewsWidget();
        $views->addLink(_('Kalenderansicht'),
                        $this->url_for('urlaubskalender/birthday'));
        $sidebar->addWidget($views);
            
            // Show action to add widget only if not all widgets have already been added.
            $actions = new ActionsWidget();

            $actions->addLink(_('Neuen Geburtstag eintragen'),
                              $this->url_for('urlaubskalender/new_birthday'),
                              Icon::create('add', 'clickable'));
            
            $actions->addLink(_('Geburtstag bearbeiten'),
                              $this->url_for('urlaubskalender/'. (!$this->mitarbeiter_hilfskraft ? ('edituser_birthday/'.$GLOBALS['user']->id) : 'edit_birthday')),
                              Icon::create('edit', 'clickable'));

            $sidebar->addWidget($actions);
       
        
        
            $this->user_id = $id ? $id : $_POST['user_id'];
            $this->entries = IntranetDate::findBySQL("user_id = ? AND type = 'birthday' ORDER BY begin ASC",
                    array($this->user_id));
        
        
    
    }

    public function save_birthday_action($id = NULL) {
        
        $date = DateTime::createFromFormat('d.m.Y', Request::get('begin'));
        if($this->entry = EventData::find($id)){
            $entry->author_id = Request::get('user_id');
            $entry->editor_id = Request::get('user_id');
            $entry->start = $date->getTimestamp();
            $entry->end = $date->getTimestamp();
            $entry->month = $date->format('m');
            $entry->day = $date->format('d');
            $entry->summary =  Request::get('notice');
            $entry->store();
            PageLayout::postMessage(MessageBox::success(_('Der Eintrag wurde gespeichert.')));
        
        } else {
            $entry = new EventData();
            $entry->author_id = Request::get('user_id');
            $entry->editor_id = Request::get('user_id');
            $entry->start = $date->getTimestamp();
            $entry->end = $date->getTimestamp();
            $entry->rtype = 'YEARLY';
            $entry->month = $date->format('m');
            $entry->day = $date->format('d');
            $entry->linterval = 1;
            $entry->category_intern = 11;
            $entry->summary =  Request::get('notice');
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
        
        $begin_date = DateTime::createFromFormat('d.m.Y', Request::get('begin'));
        $end_date = DateTime::createFromFormat('d.m.Y', Request::get('end'));
        if($this->entry = EventData::find($id)){
            $entry->author_id = Request::get('user_id');
            $entry->editor_id = Request::get('user_id');
            $entry->start = $begin_date->getTimestamp();
            $entry->end = $end_date->getTimestamp();
            $entry->rtype = 'SINGLE';
            $entry->category_intern = 13;
            $entry->summary =  Request::get('notice');
            $entry->store();
            PageLayout::postMessage(MessageBox::success(_('Der Eintrag wurde gespeichert.')));
        
        } else {
            $entry = new EventData();
            $entry->author_id = Request::get('user_id');
            $entry->editor_id = Request::get('user_id');
            $entry->start = $begin_date->getTimestamp();
            $entry->end = $end_date->getTimestamp();
             $entry->rtype = 'SINGLE';
            $entry->category_intern = 13;
            $entry->summary =  Request::get('notice');
            $entry->store();
            $event = new CalendarEvent();
            $event->range_id = $this->sem_id;
            $event->event_id = $entry->event_id;
            $event->mkdate = time();
            $event->chdate = time();
            $event->store();
            PageLayout::postMessage(MessageBox::success(_('Der Eintrag wurde gespeichert.')));
        }
        
        $this->redirect($this->url_for('/urlaubskalender'));
   
        
    }

    /**
     *  This actions removes a holiday entry
     *
     *
     * @return void
     */
    function delete_action($id)
    {
        if($entry = IntranetDate::find($id)){
            $entry->delete();
            PageLayout::postMessage(MessageBox::success(_('Der Eintrag wurde gelöscht.')));
        }
        
        $this->redirect($this->url_for('/urlaubskalender'));
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
    $strDigits = ( string ) $digits;

    for( $intCrossfoot = $i = 0; $i < strlen ( $strDigits ); $i++ )
    {
      $intCrossfoot += $strDigits{$i};
    }

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
    
    
    return $colors[$intCrossfoot];
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
                        $this->url_for('Geburtstage'))
                    ->setActive(true);
        $sidebar->addWidget($views);
            
        // Show action to add widget only if not all widgets have already been added.
        $actions = new ActionsWidget();

        $actions->addLink(_('Neuen Geburtstag eintragen'),
                          $this->url_for('urlaubskalender/new_birthday'),
                          Icon::create('add', 'clickable'))->asDialog('size=medium'); 

        $actions->addLink(_('Geburtstag bearbeiten'),
                          $this->url_for('urlaubskalender/'. (!$this->mitarbeiter_hilfskraft ? ('edituser_birthday/'.$GLOBALS['user']->id) : 'edit_birthday')),
                          Icon::create('edit', 'clickable'))->asDialog('size=medium'); 
        $sidebar->addWidget($actions);


        //$this->dates = IntranetDate::findBySQL("type = 'birthday'");
        $this->dates = $this->events_of_type(11);

        // Root may set initial positions
        if ($GLOBALS['perm']->have_perm('root')) {

        }

    }
    
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
        
        
        
        //the following sucks
        //$this->calendar = new SingleCalendar($this->sem_id);
        //$this->events = $this->calendar->getEvents()->events->toGroupedArray();
          
        //$this->setProperties($calendar_event, $component);
        //$calendar_event->setRecurrence($component['RRULE']);
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
        $stmt = DBManager::get()->prepare('SELECT * FROM calendar_event '
                . 'INNER JOIN event_data USING(event_id) '
                . 'WHERE range_id = :range_id '
                . 'AND (day = :day AND month = :month) '
                . 'ORDER BY start ASC');
        $stmt->execute(array(
            ':range_id' => $range_id,
            ':day'      => $day,
            ':month'    => $month
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
    
}
