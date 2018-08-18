<?php
/* SVN FILE: $Id: routes.php 173 2009-01-31 12:51:40Z rajesh_04ag02 $ */
/**
 * Short description for file.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision: 7820 $
 * @modifiedby    $LastChangedBy: renan.saddam $
 * @lastmodified  $Date: 2008-11-03 23:57:56 +0530 (Mon, 03 Nov 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
   Router::parseExtensions('rss', 'csv', 'json', 'txt', 'xml');

/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
//	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
//  pages/install as home page...
	Router::connect('/', array('controller' => 'products', 'action' => 'index', 'type' => 'home'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/' . Configure::read('Routing.admin') , array(
		'controller' => 'users',
		'action' => 'splashboard',
		'prefix' => Configure::read('Routing.admin'),
		'admin' => 1
	));
	Router::connect('/' . Configure::read('Routing.admin') .'/stats', array(
		'controller' => 'users',
		'action' => 'splashboard',
		'refresh',
		'prefix' => Configure::read('Routing.admin'),
		'admin' => 1
	));
	//Code to show the images uploaded by upload behaviour
	Router::connect('/img/:size/*', array('controller' => 'images', 'action' => 'view'), array('size' => '(?:[a-zA-Z_]*)*'));
	Router::connect('/files/*', array('controller' => 'images', 'action' => 'view', 'size' => 'original'));
	Router::connect('/img/*', array('controller' => 'images', 'action' => 'view', 'size' => 'original'));
	//For photos module
	Router::connect('/products/tag/:tag', array(
		'controller' => 'products',
		'action' => 'index'),
		array('tag' => '[a-zA-Z0-9\-]+')
	);
	Router::connect('/products/user/:user', array(
		'controller' => 'products',
		'action' => 'index'),
		array('user' => '[a-zA-Z0-9\-]+')
	);
	Router::connect('/products/filter/:filter', array(
		'controller' => 'products',
		'action' => 'index'),
		array('filter' => '[a-zA-Z0-9\-]+')
	);
	Router::connect('/users/twitter/connect/*', array(
        'controller' => 'users',
        'action' => 'connect',
        'type' => 'twitter'
    ));	
	Router::connect('/users/facebook/disconnect/*', array(
        'controller' => 'users',
        'action' => 'connect',
        'type' => 'facebook',
		'c_action' => 'disconnect'
    ));
	Router::connect('/users/twitter/disconnect/*', array(
        'controller' => 'users',
        'action' => 'connect',
        'type' => 'twitter',
		'c_action' => 'disconnect'
    ));
	Router::connect('/2:slug', array(
        'controller' => 'products',
        'action' => 'v',
        'view_type' => ConstViewType::NormalView
    ) , array(
        'slug' => '[a-zA-Z0-9\-]+'
    ));
    Router::connect('/e2:slug', array(
        'controller' => 'products',
        'action' => 'v',
		'view_type' => ConstViewType::EmbedView
    ) , array(
        'slug' => '[a-zA-Z0-9\-]+'
    ));
    Router::connect('/cron/:action/*', array(
        'controller' => 'crons',
    ));
	Router::connect('/contact', array('controller' => 'contacts', 'action' => 'add'));
	Router::connect('/sitemap', array('controller' => 'devs', 'action' => 'sitemap'));
	Router::connect('/robots', array('controller' => 'devs', 'action' => 'robots'));
?>