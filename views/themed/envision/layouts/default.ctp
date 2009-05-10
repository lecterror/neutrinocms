<?php
if (isset($content_description_for_layout))
	$description = $content_description_for_layout;
else
	$description = Configure::read('Neutrino.SiteDescription');

if (isset($content_keywords_for_layout))
	$keywords = $content_keywords_for_layout;
else
	$keywords = Configure::read('Neutrino.SiteKeywords');

echo $html->docType(); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php echo sprintf('%s - %s', $title_for_layout, Configure::read('Neutrino.SiteTitle')); ?></title>
	<meta name="Description" content="<?php echo $description; ?>" />
	<meta name="Keywords" content="<?php echo $keywords; ?>" />
	<?php echo $google->webmasterToolsVerificationCode(); ?>
	<?php echo $html->charset(); ?>
	<?php echo $feed->links(); ?>
	<link rel="icon" href="<?php echo $this->webroot; ?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo $this->webroot; ?>favicon.ico" type="image/x-icon" />
	<meta name="Template Distribution" content="Global" />
	<meta name="Template Author" content="Erwin Aligam - ealigam@gmail.com" />
	<meta name="Robots" content="index,follow" />
	<?php
	if (Configure::read())
	{
		echo $html->css('debug');
	}

	echo $html->css
		(
			array
			(
				'content',
				'envision',
				'starbox',
				'shadowbox',
				'prettify'
			)
		);

	if (isset($javascript))
	{
	    echo $javascript->link
			(
				array
				(
					'lib/protoaculous',
					'neditor/tabulator',
					'starbox/starbox',
					'shadowbox/shadowbox-prototype',
					'shadowbox/shadowbox',
					'prettify/prettify'
				),
				true
			);
	}

	echo $scripts_for_layout;
	?>
</head>

<body>
	<!-- wrap starts here -->
	<div id="wrap">

		<!--header -->
		<div id="header">
			<h1 id="logo-text"><?php echo Configure::read('Neutrino.SiteTitle'); ?></h1>
			<h2 id="slogan"><?php echo Configure::read('Neutrino.SiteDescription'); ?></h2>
			<div id="header-links">
				<p>
					<?php
					echo $html->link
						(
							__('Home', true),
							'/',
							array
							(
								'title' => __('Go to home page', true)
							)
						);
					?>
					&nbsp;|&nbsp;
				   	<?php
				   	$articlesRss = $html->link
				   		(
				   			__('Articles RSS', true),
				   			'/articles.rss',
				   			array
				   			(
				   				'title' => __('Articles RSS', true)
				   			),
				   			null,
				   			null,
				   			false
				   		);

				   	$commentsRss = $html->link
				   		(
				   			__('Comments RSS', true),
				   			'/comments.rss',
				   			array
				   			(
				   				'title' => __('Comments RSS', true)
				   			),
				   			null,
				   			null,
				   			false
				   		);

				   	echo sprintf('%s&nbsp;|&nbsp;%s', $articlesRss, $commentsRss);
				   	?>
				</p>
			</div>
		</div>

		<!-- content-wrap starts here -->
		<div id="content-wrap">

			<div id="sidebar">
				<?php
				echo $this->element('searchbox', array('cache' => '+1 week'));
				echo $this->element('sitemenu', array('cache' => '+1 week'));
				echo $this->element('downloadmenu', array('cache' => '+1 week'));
				?>
				<h1>Most popular</h1>
				<?php
				echo $this->element('articles/most_popular', array('limit' => 5, 'cache' => '+1 day'));
				echo $this->element('usermenu');
				?>
				<div style="width:100%; text-align:center; margin-top:40px; margin-bottom:40px;">
				<?php echo $this->element('misc/cakepower', array('cache' => '+1 week')); ?>
				<br />
				<?php echo $this->element('misc/getfirefox', array('cache' => '+1 week')); ?>
				</div>
			</div>

			<div id="main">

				<div id="flash-wrap">
					<?php
					$session->flash();
					$session->flash('auth');
					if ($session->check('Message.email'))
					{
						$session->flash('email');
					}
					?>
				</div>

				<?php echo $content_for_layout; ?>

			</div>

		<!-- content-wrap ends here -->
		</div>

		<!--footer starts here-->
		<div id="footer">

				<div style="float:left; margin-left:30px; margin-top:10px;">
				<?php echo Configure::read('Neutrino.SiteCopyrightNotice'); ?> |
				<?php
				echo sprintf
					(
						__('Original design by: %s', true),
						$html->link
						(
							'styleshout',
							'http://www.styleshout.com/'
						)
					);
				?> |
				<?php
				echo sprintf
					(
						__('Powered by: %s', true),
						$html->link
						(
							'NeutrinoCMS',
							'http://dsi.vozibrale.com/'
						)
					);
				?>
				</div>
		   		<div style="float:right; margin-right:30px; margin-top:10px;">
				<?php echo $html->link(__('Home', true), '/', array('title' => __('Go to home page', true))); ?>
				&nbsp;|&nbsp;
	   			<?php echo $html->link(__('Articles RSS', true), '/articles.rss', array('title' => __('Articles RSS', true)), null, null, false); ?>
	   			&nbsp;|&nbsp;
				<?php echo $html->link(__('Comments RSS', true), '/comments.rss', array('title' => __('Comments RSS', true)), null, null, false); ?>
				</div>

		</div>

<!-- wrap ends here -->
</div>
<?php
if (isset($javascript))
{
	echo $javascript->codeBlock('prettyPrint()');
	echo $this->element('misc/ie_rubbish');
}

if (!$auth->isAdmin())
{
	echo $google->analyticsTracker();
}

if (Configure::read())
{
	echo $cakeDebug;
}
?>
</body>
</html>
