<?php
/* SVN FILE: $Id: routes.php 4410 2007-02-02 13:31:21Z phpnut $ */
/**
 * Short description for file.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2007, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2007, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.app.config
 * @since			CakePHP(tm) v 0.2.9
 * @version			$Revision: 4410 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2007-02-02 07:31:21 -0600 (Fri, 02 Feb 2007) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */

Router::parseExtensions('rss'); // for RSS automagic..

// google sitemap
Router::connect('/sitemap.xml', array('controller' => 'sitemaps', 'action' => 'sitemap'));
// robots.txt
Router::connect('/robots.txt', array('controller' => 'sitemaps', 'action' => 'robots'));

// help routes..
Router::connect('/neutrino/help/markdown.html', array('controller' => 'neutrino', 'action' => 'markdown'));

// make "attachments" appear as "files",
// obviously, we cannot have a model called File..
Router::connect('/files/:action/*', array('controller' => 'attachments'));

// user and group permissions
Router::connect('/groups/permissions/:action/*', array('controller' => 'group_permissions'));
Router::connect('/users/permissions/:action/*', array('controller' => 'user_permissions'));

// nicer article and download categories
Router::connect('/articles/categories/:action/*', array('controller' => 'article_categories'));
Router::connect('/downloads/categories/:action/*', array('controller' => 'download_categories'));
Router::connect('/statistics/:action/*', array('controller' => 'stats'));

// install & update routes
Router::connect('/system/install/start', array('controller' => 'setup', 'action' => 'install'));
Router::connect('/system/install/step/1', array('controller' => 'setup', 'action' => 'install_step1'));
Router::connect('/system/install/step/2', array('controller' => 'setup', 'action' => 'install_step2'));
Router::connect('/system/install/step/3', array('controller' => 'setup', 'action' => 'install_step3'));

// setup && update controller routes
Router::connect('/system/:action/*', array('controller' => 'setup'));

// connect home
Router::connect('/', array('controller' => 'articles', 'action' => 'home'));

/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
/**
 * Then we connect url '/test' to our test controller. This is helpfull in
 * developement.
 */
	Router::connect('/tests', array('controller' => 'tests', 'action' => 'index'));

?>