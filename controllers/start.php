<?php
require_once 'app/controllers/news.php';


class StartController extends StudipController {

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

        PageLayout::setTitle(_("Meine Startseite"));
        $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));
    }

    public function index_action()
    {
        
        $actions = new ActionsWidget();
            $actions->setTitle(_('Aktionen'));

            $actions->addLink(
            'Kurs gestalten',
            $this->url_for('/index/settings'),'icons/16/blue/edit.png'); 

            Sidebar::get()->addWidget($actions);
        //$this->render_action($this->style);

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
