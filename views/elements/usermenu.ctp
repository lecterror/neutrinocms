<h1>Admin</h1>
<ul class="sitemenu">
	<?php
	if (!$auth->valid())
	{
		echo '<li>'.$html->link('Login', array('controller' => 'users', 'action' => 'login')).'</li>';
	}
	else
	{
		echo '<li>'.$html->link('Add new article', array('controller' => 'articles', 'action' => 'add')).'</li>';
		echo '<li>'.$html->link('Add article category', array('controller' => 'article_categories', 'action' => 'add')).'</li>';
		echo '<li>'.$html->link('Add download', array('controller' => 'downloads', 'action' => 'add')).'</li>';
		echo '<li>'.$html->link('Add download category', array('controller' => 'download_categories', 'action' => 'add')).'</li>';
		echo '<li>'.$html->link('Manage files', array('controller' => 'attachments', 'action' => 'manage')).'</li>';
		echo '<li>'.$html->link('Configure', array('controller' => 'neutrino', 'action' => 'configure')).'</li>';
		echo '<li class="menu-separator">&nbsp;</li>';
		echo '<li>'.$html->link('Stats / Visits', array('controller' => 'stats', 'action' => 'visits')).'</li>';
		echo '<li>'.$html->link('Stats / Downloads', array('controller' => 'stats', 'action' => 'downloads')).'</li>';
		echo '<li>'.$html->link('Logout', array('controller' => 'users', 'action' => 'logout')).'</li>';
	}
	?>
</ul>