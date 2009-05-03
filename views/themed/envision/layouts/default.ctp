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
	<title><?php echo $title_for_layout.' - '.Configure::read('Neutrino.SiteTitle'); ?></title>
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

	<?php if (Configure::read()) echo $html->css('debug'); ?>
	<?php echo $html->css(array(
		'content',
		'envision',
		'syntaxhighlighter',
		'starbox',
		'shadowbox')); ?>

	<?php
	if (isset($javascript))
	{
	    echo $javascript->link(
	    	array(
			//	'lib/jquery',
				'lib/prototype',
				'lib/scriptaculous/scriptaculous',
				'lib/scriptaculous/effects',
				'neditor/tabulator',
				'starbox/starbox',
				'shadowbox/shadowbox-prototype',
				'shadowbox/shadowbox',
				'dp.SyntaxHighlighter/Scripts/shCore',
				'dp.SyntaxHighlighter/Scripts/shBrushCpp',
				'dp.SyntaxHighlighter/Scripts/shBrushCSharp',
				'dp.SyntaxHighlighter/Scripts/shBrushCss',
				'dp.SyntaxHighlighter/Scripts/shBrushDelphi',
				'dp.SyntaxHighlighter/Scripts/shBrushJava',
				'dp.SyntaxHighlighter/Scripts/shBrushJScript',
				'dp.SyntaxHighlighter/Scripts/shBrushPhp',
				'dp.SyntaxHighlighter/Scripts/shBrushPython',
				'dp.SyntaxHighlighter/Scripts/shBrushRuby',
				'dp.SyntaxHighlighter/Scripts/shBrushSql',
				'dp.SyntaxHighlighter/Scripts/shBrushVb',
				'dp.SyntaxHighlighter/Scripts/shBrushXml'
		    ),
		    true
		);
	}
	?>

	<?php echo $scripts_for_layout; ?>


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
					<?php echo $html->link('Home', '/', array('title' => 'Go to home page')); ?>&nbsp;|&nbsp;
				   	<?php echo $html->link('Articles RSS', '/articles.rss', array('title' => 'Articles RSS'), null, null, false); ?>&nbsp;|&nbsp;
				   	<?php echo $html->link('Comments RSS', '/comments.rss', array('title' => 'Comments RSS'), null, null, false); ?>
				</p>
			</div>

		</div>

		<!-- content-wrap starts here -->
		<div id="content-wrap">

			<div id="sidebar">
				<?php echo $this->element('searchbox', array('cache' => '+1 week')); ?>
				<?php echo $this->element('sitemenu', array('cache' => '+1 week')); ?>
				<?php echo $this->element('downloadmenu', array('cache' => '+1 week')); ?>
				<h1>Most popular</h1>
				<?php echo $this->element('articles/most_popular', array('limit' => 5, 'cache' => '+1 day')); ?>

				<?php echo $this->element('usermenu'); ?>
				<div style="width:100%; text-align:center; margin-top:40px; margin-bottom:40px;">
				<?php echo $this->element('misc/cakepower', array('cache' => '+1 week')); ?>
				<br />
				<?php echo $this->element('misc/getfirefox', array('cache' => '+1 week')); ?>
				</div>
			</div>

			<div id="main">

				<div id="flash-wrap">
					<?php $session->flash(); ?>
					<?php $session->flash('auth'); ?>
					<?php //$session->flash('email'); ?>
				</div>

				<?php echo $content_for_layout; ?>

			</div>

		<!-- content-wrap ends here -->
		</div>

		<!--footer starts here-->
		<div id="footer">

				<div style="float:left; margin-left:30px; margin-top:10px;">
				<?php echo Configure::read('Neutrino.SiteCopyrightNotice'); ?> |
				Original design by: <a href="http://www.styleshout.com/">styleshout</a> |
				Powered by: <a href="http://dsi.vozibrale.com/">NeutrinoCMS</a>
				</div>
		   		<div style="float:right; margin-right:30px; margin-top:10px;">
				<?php echo $html->link('Home', '/', array('title' => 'Go to home page')); ?>&nbsp;|&nbsp;
			   	<?php echo $html->link('Articles RSS', '/articles.rss', array('title' => 'Articles RSS'), null, null, false); ?>&nbsp;|&nbsp;
			   	<?php echo $html->link('Comments RSS', '/comments.rss', array('title' => 'Comments RSS'), null, null, false); ?>
			   	</div>

		</div>

<!-- wrap ends here -->
</div>
<?php
if (isset($javascript))
{
	echo $javascript->codeBlock('dp.SyntaxHighlighter.HighlightAll("code_snippet");');

	echo $javascript->codeBlock(
	'
		if (Prototype.Browser.IE)
		{
			$("flash-wrap").innerHTML +=
				"<div class=\"message\" style=\"text-align:center;\">" +
				"It seems like you are using Internet Explorer. This is bad.<br />" +
				"IE does not obey the web standards, and many of the features on this site will not work.<br />" +
				"Because Microsoft is doing this on purpose, <u>these features will not be tweaked to accomodate IE<\/u>.<br />" +
				"To view this site properly, please switch to " +
				"<a href=\"http://getfirefox.com/\" title=\"Get Firefox - The Browser, Reloaded.\">Firefox<\/a> " +
				"as soon as possible.<\/div>";
		}
	');
}
?>
<?php
if (!$auth->valid())
{
	echo $google->analyticsTracker();
}
?>
</body>
</html>
