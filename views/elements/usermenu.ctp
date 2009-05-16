<h1><?php __('Users'); ?></h1>
<ul class="sitemenu">
	<?php
	if (!$auth->isValid())
	{
		echo '<li>'.$html->link(__('Login', true), array('controller' => 'users', 'action' => 'login')).'</li>';
		echo '<li>'.$html->link(__('Register', true), array('controller' => 'users', 'action' => 'register')).'</li>';
	}
	else
	{
		$contentLinks = array();
		$statsLinks = array();
		$userLinks = array();

		if ($auth->check('articles', 'add'))
		{
			$contentLinks[] = '<li>'.$html->link(__('Add new article', true), array('controller' => 'articles', 'action' => 'add')).'</li>';
		}

		if ($auth->check('article_categories', 'add'))
		{
			$contentLinks[] = '<li>'.$html->link(__('Add article category', true), array('controller' => 'article_categories', 'action' => 'add')).'</li>';
		}

		if ($auth->check('downloads', 'add'))
		{
			$contentLinks[] = '<li>'.$html->link(__('Add download', true), array('controller' => 'downloads', 'action' => 'add')).'</li>';
		}

		if ($auth->check('download_categories', 'add'))
		{
			$contentLinks[] = '<li>'.$html->link(__('Add download category', true), array('controller' => 'download_categories', 'action' => 'add')).'</li>';
		}

		if ($auth->check('attachments', 'manage'))
		{
			$contentLinks[] = '<li>'.$html->link(__('Manage files', true), array('controller' => 'attachments', 'action' => 'manage')).'</li>';
		}

		if ($auth->isAdmin())
		{
			$contentLinks[] = '<li>'.$html->link(__('User permissions', true), array('controller' => 'user_permissions', 'action' => 'permissions')).'</li>';
		}

		if ($auth->check('neutrino', 'configure'))
		{
			$contentLinks[] = '<li>'.$html->link(__('Configure', true), array('controller' => 'neutrino', 'action' => 'configure')).'</li>';
		}

		if ($auth->check('stats', 'visits'))
		{
			$statsLinks[] = '<li>'.$html->link(__('Stats / Visits', true), array('controller' => 'stats', 'action' => 'visits')).'</li>';
		}

		if ($auth->check('stats', 'downloads'))
		{
			$statsLinks[] = '<li>'.$html->link(__('Stats / Downloads', true), array('controller' => 'stats', 'action' => 'downloads')).'</li>';
		}

		if ($auth->check('users', 'change_password'))
		{
			$userLinks[] = '<li>'.$html->link(__('Change password', true), array('controller' => 'users', 'action' => 'change_password')).'</li>';
		}

		$userLinks[] = '<li>'.$html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout')).'</li>';

		// @todo: rijesit ovo, odvojit menije...
		echo implode('', $contentLinks);
		echo implode('', $statsLinks);
		echo implode('', $userLinks);
	}
	?>
</ul>