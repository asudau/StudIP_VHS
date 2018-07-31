<?php
require_once 'app/controllers/news.php';


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
        $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));
    }

    public function index_action($inst_id)
    {

        $this->startpage = IntranetConfig::find($inst_id)->startpage;
        $this->courses = User::findCurrent()->course_memberships;
        
        $this->newsTemplates = array();
 
        foreach(IntranetConfig::find($inst_id)->getRelatedCourses() as $course){
            $this->newsTemplates[] = array('template' => $this->getNewsTemplateForSeminar($course->id));
        }
        

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
