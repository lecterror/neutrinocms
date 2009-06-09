<?php echo $html->docType(); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>NeutrinoCMS</title>
	<meta name="Description" content="<?php __('NeutrinoCMS setup utility'); ?>" />
	<meta name="Keywords" content="<?php __('NeutrinoCMS - CMS for developers, mathematicians and scientists'); ?>" />
	<?php echo $html->charset(); ?>
	<link rel="icon" href="<?php echo $this->webroot; ?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo $this->webroot; ?>favicon.ico" type="image/x-icon" />
	<meta name="Template Distribution" content="Global" />
	<meta name="Template Author" content="Erwin Aligam - ealigam@gmail.com" />
	<meta name="Robots" content="noindex,nofollow" />
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
				'install'
			)
		);

	if (isset($javascript))
	{
	    echo $javascript->link
		    (
		    	array
		    	(
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

			<h1 id="logo-text"><?php __('NeutrinoCMS'); ?></h1>
			<h2 id="slogan"><?php __('Personal CMS for developers, mathematicians and scientists'); ?></h2>

			<div id="header-links">
				<p>&nbsp;</p>
			</div>

		</div>

		<!-- content-wrap starts here -->
		<div id="content-wrap">

			<div id="sidebar">
				<div style="width:100%; text-align:center; margin-top:5px; margin-bottom:40px;">
					<br />
					<?php echo $html->image('neutrino.mirrored.png'); ?>
				</div>
			</div>

			<div id="main">

				<div id="flash-wrap">
					<?php
					$session->flash();
					$session->flash('auth');
					?>
				</div>

				<?php echo $content_for_layout; ?>

			</div>

		<!-- content-wrap ends here -->
		</div>

		<!--footer starts here-->
		<div id="footer">

				<div style="float:left; margin-left:30px; margin-top:10px;">
				&copy; 2007 - 3827 <strong>lecterror</strong> |
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
				?>
				</div>
		   		<div style="float:right; margin-right:30px; margin-top:10px;">
			   	</div>

		</div>

<!-- wrap ends here -->
</div>
<?php
if (isset($javascript))
{
	echo $this->element('misc/ie_rubbish');
}
?>
</body>
</html>
