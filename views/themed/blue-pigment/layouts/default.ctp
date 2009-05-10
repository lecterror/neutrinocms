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
	<?php
	echo $google->webmasterToolsVerificationCode();
	echo $html->charset();
	echo $feed->links();
	?>
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
				'blue-pigment',
				'syntaxhighlighter',
				'starbox',
				'shadowbox'
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
					'dp.SyntaxHighlighter/Scripts/shCombined'
		    	),
		  	  true
			);
	}

	echo $scripts_for_layout;
	?>
</head>

<body>
	<!-- header starts here -->
	<div id="header">
		<div id="header-content">
			<h1 id="logo-text"><?php echo $html->link(Configure::read('Neutrino.SiteTitle'), '/'); ?></h1>
			<h2 id="slogan"><?php echo Configure::read('Neutrino.SiteDescription'); ?></h2>
			<div id="header-links">
				<p>
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
	</div>

	<!-- navigation starts here -->
	<div id="nav-wrap">
		<div id="nav">
			<ul>
				<li><?php echo $html->link(__('Home', true), '/', array('title' => __('Go to home page', true))); ?></li>
			</ul>
		</div>
	</div>

	<!-- content-wrap starts here -->
	<div id="content-wrap">
		<div id="content">
			<div id="sidebar">
					<div class="sep"></div>
					<?php echo $this->element('searchbox', array('cache' => '+1 week')); ?>
					<div class="sep"></div>
					<?php echo $this->element('sitemenu', array('cache' => '+1 week')); ?>
					<div class="sep"></div>
					<?php echo $this->element('downloadmenu', array('cache' => '+1 week')); ?>
					<div class="sep"></div>
					<?php echo $this->element('usermenu'); ?>
					<div class="sep"></div>
					<h1><?php __('Articles RSS Feed'); ?></h1>
					<?php echo $this->element('rsslink', array('cache' => '+1 week')); ?>
			</div>
			<div id="main">
				<div id="flash-wrap">
					<?php $session->flash(); ?>
					<?php $session->flash('auth'); ?>
				</div>
				<?php echo $content_for_layout; ?>
				<br />
			</div>
		<!-- content-wrap ends here -->
		</div>
	</div>

	<!-- footer starts here-->
	<div id="footer-wrap">
		<div id="footer-columns">
			<div class="col3">
				<h2><?php __('Most popular'); ?></h2>
				<?php echo $this->element('articles/most_popular', array('limit' => 5, 'cache' => '+1 week')); ?>
			</div>
			<div class="col3-center">
				<h2><?php __('Most commented'); ?></h2>
				<?php echo $this->element('articles/most_commented', array('limit' => 5, 'cache' => '+1 week')); ?>
			</div>
			<div class="col3">
				<h2><?php __('Highest rated'); ?></h2>
				<?php echo $this->element('articles/highest_rated', array('limit' => 5, 'cache' => '+1 week')); ?>
			</div>
		<!-- footer-columns ends -->
		</div>

		<div id="footer-bottom">
			<p>
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
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo $html->link(__('Home', true), '/', array('title' => __('Go to home page', true))); ?>
			&nbsp;|&nbsp;
   			<?php echo $html->link(__('Articles RSS', true), '/articles.rss', array('title' => __('Articles RSS', true)), null, null, false); ?>
   			&nbsp;|&nbsp;
			<?php echo $html->link(__('Comments RSS', true), '/comments.rss', array('title' => __('Comments RSS', true)), null, null, false); ?>
			</p>
		</div>

<!-- footer ends-->
</div>
<?php
if (isset($javascript))
{
	echo $javascript->codeBlock('dp.SyntaxHighlighter.HighlightAll("code_snippet");');
	echo $this->element('misc/ie_rubbish');
}

if (!$auth->isAdmin())
{
	echo $google->analyticsTracker();
}
?>
</body>
</html>
