<h1><?php __('Admin'); ?></h1>
<ul class="sitemenu">
	<?php
	if (!$auth->valid())
	{
		echo '<li>'.$html->link(__('Login', true), array('controller' => 'users', 'action' => 'login')).'</li>';
	}
	else
	{
		echo '<li>'.$html->link(__('Add new article', true), array('controller' => 'articles', 'action' => 'add')).'</li>';
		echo '<li>'.$html->link(__('Add article category', true), array('controller' => 'article_categories', 'action' => 'add')).'</li>';
		echo '<li>'.$html->link(__('Add download', true), array('controller' => 'downloads', 'action' => 'add')).'</li>';
		echo '<li>'.$html->link(__('Add download category', true), array('controller' => 'download_categories', 'action' => 'add')).'</li>';
		echo '<li>'.$html->link(__('Manage files', true), array('controller' => 'attachments', 'action' => 'manage')).'</li>';
		echo '<li>'.$html->link(__('Configure', true), array('controller' => 'neutrino', 'action' => 'configure')).'</li>';
		echo '<li class="menu-separator">&nbsp;</li>';
		echo '<li>'.$html->link(__('Stats / Visits', true), array('controller' => 'stats', 'action' => 'visits')).'</li>';
		echo '<li>'.$html->link(__('Stats / Downloads', true), array('controller' => 'stats', 'action' => 'downloads')).'</li>';
		echo '<li>'.$html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout')).'</li>';
	}
	?>
</ul>