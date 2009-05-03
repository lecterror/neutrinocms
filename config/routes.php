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

/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.thtml)...
 */
//	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));

// google sitemap
Router::connect('/sitemap.xml', array('controller' => 'sitemaps', 'action' => 'sitemap'));
// robots.txt
Router::connect('/robots.txt', array('controller' => 'sitemaps', 'action' => 'robots'));

// help routes..
Router::connect('/neutrino/help/markdown.html', array('controller' => 'neutrino', 'action' => 'markdown'));

// make "attachments" appear as "files",
// as it seems, we cannot have a model called File..
Router::connect('/files/:action/*', array('controller' => 'attachments'));

// nicer article and download categories
Router::connect('/articles/categories/:action/*', array('controller' => 'article_categories'));
Router::connect('/downloads/categories/:action/*', array('controller' => 'download_categories'));

// setup && update controller routes
Router::connect('/setup/:action/*', array('controller' => 'setup'));

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