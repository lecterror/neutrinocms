<?php echo $html->docType(); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>NeutrinoCMS</title>
	<meta name="Description" content="NeutrinoCMS setup utility" />
	<meta name="Keywords" content="NeutrinoCMS - CMS for developers, mathematicians and scientists" />
	<?php echo $html->charset(); ?>
	<link rel="icon" href="<?php echo $this->webroot; ?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo $this->webroot; ?>favicon.ico" type="image/x-icon" />
	<meta name="Template Distribution" content="Global" />
	<meta name="Template Author" content="Erwin Aligam - ealigam@gmail.com" />
	<meta name="Robots" content="noindex,nofollow" />

	<?php echo $html->css(array('content', 'envision', 'install')); ?>

	<?php
	if (isset($javascript))
	{
	    echo $javascript->link(
	    	array(
			//	'jquery',
				'lib/prototype',
				'lib/scriptaculous/scriptaculous',
				'lib/scriptaculous/effects'
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

			<h1 id="logo-text">NeutrinoCMS</h1>
			<h2 id="slogan">Personal CMS for developers, mathematicians and scientists</h2>

			<div id="header-links">
				<p>&nbsp;</p>
			</div>

		</div>

		<!-- content-wrap starts here -->
		<div id="content-wrap">

			<div id="sidebar">
				<?php
				if ($this->params['action'] != 'error')
				{
					if ($session->read('Neutrino.InstallSteps'))
					{
						echo $this->element('setup/steps', $session->read('Neutrino.InstallSteps'));
					}
				}
				?>

				<div style="width:100%; text-align:center; margin-top:40px; margin-bottom:40px;">
				<?php echo $this->element('misc/cakepower', array('cache' => '1 week')); ?>
				<br />
				<?php echo $this->element('misc/getfirefox', array('cache' => '1 week')); ?>
				</div>
			</div>

			<div id="main">

				<div id="flash-wrap">
					<?php $session->flash(); ?>
					<?php $session->flash('auth'); ?>
				</div>

				<?php echo $content_for_layout; ?>

			</div>

		<!-- content-wrap ends here -->
		</div>

		<!--footer starts here-->
		<div id="footer">

				<div style="float:left; margin-left:30px; margin-top:10px;">
				&copy; 2007 - 3827 <strong>lecterror</strong> |
				Original design by: <a href="http://www.styleshout.com/">styleshout</a>
				</div>
		   		<div style="float:right; margin-right:30px; margin-top:10px;">
			   	</div>

		</div>

<!-- wrap ends here -->
</div>
<?php
if (isset($javascript))
{
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
</body>
</html>
