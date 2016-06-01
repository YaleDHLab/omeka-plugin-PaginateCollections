<?php

/**
 * Plugin to paginage results on the show action of the collections controller.
 */

class PaginatePlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
     * @var array Hooks for the plugin.
     */

    protected $_hooks = array('define_routes');

    protected $_filters = array('public_navigation_main');

    public function hookDefineRoutes($args)
    {
      
        $router = $args['router'];

        $paginate_route = new Zend_Controller_Router_Route(
                'collections/show/:id',
                array('module' => 'paginate',
                'controller' => 'paginate',
                'action' => 'show'));
        $router -> addRoute('paginate_show', $paginate_route);	
    }  



    public function searchForm($args, $view) {
      return $view ->partial('paginate/show.php', array('query' => ''));
    }


    public function filterPublicNavigationMain($navArray)
    {
      $navArray["Show Paginate"] = array(
        "label" => __("Show Paginate"),
        "uri" => url("paginate")
      );
      return $navArray;
    }


}

