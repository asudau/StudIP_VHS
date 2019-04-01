<?php
require_once 'app/controllers/news.php';
require_once 'app/controllers/calendar/single.php';


class IntranetStartController extends StudipController {

    public function __construct($dispatcher)
    {
        parent::__construct($dispatcher);
        $this->plugin = $dispatcher->plugin;
        Navigation::activateItem('start');
        PageLayout::addStylesheet($this->plugin->getPluginURL().'/assets/no_tabs.css');
    }

    public function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);
        
        PageLayout::addStylesheet($this->plugin->getPluginURL().'/assets/intranet_start.css');
        PageLayout::setTitle(_("Meine Startseite"));
    }

    public function index_action($inst_id = null)
    {
        
        $this->calendar_controller = new Calendar_CalendarController();


        if ($GLOBALS['perm']->have_perm('admin')){
            $this->intranets = IntranetConfig::getInstitutesWithIntranet(true);
        } else {
            $this->intranets = IntranetConfig::getIntranetIDsForUser(User::findCurrent());
        }
        if ($inst_id == null){
            $inst_id = $this->intranets[0];
        }

        //get seminars ($inst_id)
        $this->intranet_courses = IntranetConfig::find($inst_id)->getRelatedCourses();        
        foreach($this->intranet_courses as $course){
            $config = IntranetSeminar::find([$course->id, $inst_id]);
            if ($config && $config->show_news){
                if (!$config->news_sidebar){
                    $this->newsTemplates[$course->id] = $this->getNewsTemplateForSeminar($course->id);
                    $this->newsCaptions[$course->id] = $config->news_caption;
                    $this->newsPosition[$course->id] = $config->news_position ? $config->news_position : 0;
                } else {
                    $this->sidebarNewsTemplates[$course->id] = $this->getNewsTemplateForSeminar($course->id);
                    $this->newsCaptions[$course->id] = $config->news_caption;
                }
            }
            //falls Nutzer in einer der Veranstaltungen Admin ist darf er ein bisschen mehr
            if ($GLOBALS['perm']->have_studip_perm('dozent', $course->id)){
                 $this->admin = true;
            }
        }
        asort($this->newsPosition);

        //get permission of currentUser (autor/dozent) //ammerland Spezial
        
//        $sem_id_mitarbeiterinnen = Config::get()->getValue('INTRANET_SEMID_MITARBEITERINNEN');
//        
//        $sem_id_projektbereich = Config::get()->getValue('INTRANET_SEMID_PROJEKTBEREICH');
//        
//        global $perm; 
//        $this->admin = $perm->have_studip_perm('dozent', $sem_id_mitarbeiterinnen);
//        $this->projekt_admin = $perm->have_studip_perm('dozent', $sem_id_projektbereich);
//        

//        $this->edit_link_files = URLHelper::getLink("folder.php?cid=" . $sem_id_projektbereich . "&cmd=tree");
        
        //get news of connected seminars

//        $dispatcher = new StudipDispatcher();
//        $controller = new NewsController($dispatcher);
//        $response = $controller->relay('news/display/' . $sem_id_mitarbeiterinnen);
//        //$response = $controller->relay('news/display/9fc5dd6a84acf0ad76d2de71b473b341'); //localhost
//        $this->internnewstemplate = $GLOBALS['template_factory']->open('shared/string');
//        $this->internnewstemplate->content = $response->body;
//        
//
//        if (StudipNews::CountUnread() > 0) {
//            $navigation = new Navigation('', PluginEngine::getLink($this, array(), 'read_all'));
//            $navigation->setImage(Icon::create('refresh', 'clickable', ["title" => _('Alle als gelesen markieren')]));
//            $icons[] = $navigation;
//        }
//
//        $this->internnewstemplate->icons = $icons;
        
        //get special dates (maybe)
        $this->birthday_dates = IntranetDate::findBySQL("type = 'birthday' AND begin = ?", array(date('d.m.Y', time())));

        //get new and recently visited courses of user
        $statement = DBManager::get()->prepare("SELECT s.Seminar_id, s.Name, ouv.visitdate, ouv.type "
                . "FROM seminare as s "
                . "LEFT JOIN object_user_visits as ouv ON (s.Seminar_id = ouv.object_id) "
                . "WHERE ouv.user_id = :user_id "
                . "AND s.Seminar_id NOT IN (:int_ma, :int_pb) "
                . "AND ouv.type = 'sem' "
                . "AND s.Seminar_id in "
                . "(SELECT su.Seminar_id FROM seminar_user as su WHERE su.user_id = :user_id) ORDER BY ouv.visitdate DESC");

        $statement->execute([':user_id' => $GLOBALS['user']->id, ':int_ma' => '111', ':int_pb' => '222']);
        $this->courses = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        //Get File-Folders of Intern Seminar MitarbeiterInnen (if configured)
        foreach($this->intranet_courses as $course){
            $config = IntranetSeminar::find([$course->id, $inst_id]);
            if ($config && $config->use_files){
                $this->filesCaptions[$course->id] = $config->files_caption;
                $this->filesPosition[$course->id] = $config->files_position ? $config->files_position : 0;

                $sem_folder = $this->get_folders_from_seminar_id($course->id);

                $details = $this->get_folderwithfiles_from_folder_ids($sem_folder);
                $this->folderwithfiles_array[$course->id] = $details['folderwithfiles'];
            }
        }
        asort($this->filesPosition);
        
        //folder auf Unterebene
        $this->parentfolder = $details['parentfolder'];

         //get upcoming courses (studip dates of configured category)
        //$result = EventData::findBySQL("category_intern = '13' AND start > '" . time() . "' ORDER BY start ASC");
        //$this->courses_upcoming = $result;
        
        $this->template = IntranetConfig::find($inst_id)->template;
        $this->render_action($this->template);

    }
    
    public function getNewsTemplateForSeminar($sem_id){
        //get intern news
        $dispatcher = new StudipDispatcher();
        $controller = new NewsController($dispatcher);
        $response = $controller->relay('news/display/' . $sem_id);
        //$response = $controller->relay('news/display/9fc5dd6a84acf0ad76d2de71b473b341'); //localhost
        $this->internnewstemplate = $GLOBALS['template_factory']->open('shared/string');
        $this->internnewstemplate->content = $response->body;
        
        if (StudipNews::CountUnread() > 0) {
            $navigation = new Navigation('', PluginEngine::getLink($this, array(), 'read_all'));
            $navigation->setImage(Icon::create('refresh', 'clickable', ["title" => _('Alle als gelesen markieren')]));
            $icons[] = $navigation;
        }

        $this->internnewstemplate->icons = $icons;
        return $this->internnewstemplate;
    }
    
    public function get_folders_from_seminar_id($sem_id){
        $db = DBManager::get();
        $stmt = $db->prepare("SELECT folder_id, name, range_id, seminar_id FROM folder WHERE seminar_id = :cid");
        $stmt->bindParam(":cid", $sem_id);
        $stmt->execute();
        $folders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $folders;  
    }
    
    public function get_folderwithfiles_from_folder_ids($folders) {
            $folderwithfiles = array();
            $parentfolder = array();

            foreach ($folders as $folder){

                $db = \DBManager::get();
                $stmt = $db->prepare("SELECT * FROM `dokumente` WHERE `range_id` = :range_id
                    ORDER BY `priority`, `name`");

                $stmt->bindParam(":range_id", $folder['folder_id']);
                $stmt->execute();
                $files = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $folderwithfiles[$folder['folder_id']] = $files;
                if(DocumentFolder::find($folder['range_id'])){
                    $parentfolder[$folder['folder_id']] = $folder['range_id'];
                }

            }
            return ['folderwithfiles' => $folderwithfiles, 'parentfolder' => $parentfolder];
    }
    
    
    public function infos_action(){
        
    }
    
    public function feedback_form_action(){
        
    }
    
    public function send_form_action(){
        if (Request::get('message_body')){
            $mailtext = Studip\Markup::purifyHtml(Request::get('message_body'));
            //TODO Kontaktadresse konfigurierbar
            $empfaenger = 'kvhs@ammerland.de';//$contact_mail;//$contact_mail; //Mailadresse
            //$absender   = "asudau@uos.de";
            $betreff    = 'Betreff: ' . Studip\Markup::purifyHtml(Request::get('message_subject'));

            $mail = new StudipMail();
            $success = $mail->addRecipient($empfaenger)
                //->addRecipient('elmar.ludwig@uos.de', 'Elmar Ludwig', 'Cc')
                 ->setReplyToEmail( User::findCurrent()->email)
                 ->setSenderEmail( User::findCurrent()->email)
                 ->setSenderName('Intranet für Dozenten')
                 ->setSubject($betreff)
                 ->setBodyHtml($mailtext)
                 ->setBodyHtml(strip_tags($mailtext))  
                 ->send();
            
        } if ($success){
            $message = MessageBox::success(_('Meldung wurde versendet!'));
            PageLayout::postMessage($message);
        } else {
            $message = MessageBox::error(_('Da ist was schief gegangen, Ihre Mail konnte nicht versendet werden.'));
            PageLayout::postMessage($message);
        }
        $this->response->add_header('X-Dialog-Close', '1');
        $this->redirect('intranet_start/index');
    }
    
    public function feedback_chat_action(){
        //TODO - In Intranet Veranstaltung für Chat wählen
        $this->seminar_id = '2dac34217342bd706ac114d57dd0b3ec';
        if (!$GLOBALS['perm']->have_studip_perm('autor', $this->seminar_id)){
            $this->access = false;
            $message = MessageBox::error(_('Sie haben auf diese Funktion keinen Zugriff'));
            PageLayout::postMessage($message);
        } else $this->access = true;
    }
    
    public function send_feedback_action(){
        if (strlen(Request::get('content')) > 0){
            $this->seminar_id = '2dac34217342bd706ac114d57dd0b3ec';

            $thread = new BlubberPosting();
            $thread['seminar_id'] = $this->seminar_id;
            $thread['context_type'] = 'course';
            $thread['parent_id'] = 0;
            $thread['author_host'] = $_SERVER['REMOTE_ADDR'];
            $thread['user_id'] = $GLOBALS['user']->id;
            //throw new AccessDeniedException("No permission to write posting.");

            $content =  Studip\Markup::purifyHtml(Request::get("content"));

            if (strpos($content, "\n") !== false) {
                $thread['name'] = substr($content, 0, strpos($content, "\n"));
                $thread['description'] = $content;
            } else {
                if (strlen($content) > 255) {
                    $thread['name'] = "";
                } else {
                    $thread['name'] = $content;
                }
                $thread['description'] = $content;
            }
            if ($thread->store()) {
                $message = MessageBox::success(_('Feedback wurde veröffentlicht! <a href=\''. URLHelper::getLink("/plugins.php/blubber/streams/forum?cid=" . $this->seminar_id) .'\'>Direkt zum Chat </a>'));
                PageLayout::postMessage($message);
            } else {
               $message = MessageBox::error(_('Da ist was schief gegangen. Versuchen Sie es  <a href=\''. URLHelper::getLink("/plugins.php/blubber/streams/forum?cid=" . $this->seminar_id) .'\'>hier </a>'));
                PageLayout::postMessage($message);
            }

        } 
        $this->response->add_header('X-Dialog-Close', '1');
        $this->redirect('intranet_start/index');
    }
    
    public function folder_action($folder_id = null)
    {
        $db = DBManager::get();
        $stmt = $db->prepare("SELECT folder_id, name, range_id, seminar_id FROM folder WHERE folder_id = :folder_id");
        $stmt->bindParam(":folder_id", $folder_id);
        $stmt->execute();
        $sem_folder = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $details = $this->get_folderwithfiles_from_folder_ids($sem_folder);
        $this->folderwithfiles = $details['folderwithfiles'];
        $this->parentfolder = $details['parentfolder'];
    }
    
    public function semfolder_action($sem_id = null){
        $this->folder_ids = $this->get_folders_from_seminar_id($sem_id);
        $details = $this->get_folderwithfiles_from_folder_ids($this->folder_ids );
        $this->folderwithfiles = $details['folderwithfiles'];
        $this->parentfolder = $details['parentfolder'];
        $this->render_action('folder');
    }
    
    
    public function files_action($file_id = null)
    {
        
    }

    
    function insertCoursebegin_action($id = ''){
        
          //speichern
        if ($_POST['submit']){
            $this->event = new EventData($id);
            $this->event->author_id = $GLOBALS['user']->id;
            $this->event->start = strtotime($_POST['start_date']);
            $this->event->end = $this->event->start;
            $this->event->summary = studip_utf8decode($_POST['summary']);
            $this->event->description = $_POST['description'];
            $this->event->class = 'PUBLIC';
            $this->event->category_intern = '13';

            $this->event->store();
            
             if (Request::isXhr()) {
                    header('X-Dialog-Close: 1');
                    exit;
             } else $this->redirect($this->url_for('/start'));
        
        //bearbeiten
        } else if ($id){
            
            $this->event = new EventData($id);
        
        // neu anlegen
        } else {
            $this->event = new EventData();
            $this->event->event_id = $this->event->getNewId();
            $this->event->start = time();
            $this->event->summary = 'Kurstitel';
            $this->event->description = 'http://';
        }
        //$this->setProperties($calendar_event, $component);
        //$calendar_event->setRecurrence($component['RRULE']);
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
