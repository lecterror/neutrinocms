<?php echo $html->docType(); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php __('NeutrinoCMS help system'); ?></title>
	<?php
	if (isset($javascript))
	{
	    echo $javascript->link
	    	(
		    	array
		    	(
					'lib/prototype',
					'lib/scriptaculous/scriptaculous',
					'lib/scriptaculous/effects',
					'markdown-help'
			    ),
			    true
			);
	}

	echo $html->css('markdown-help');
	?>
</head>
<body>
	<div id="wrap">
		<?php echo $content_for_layout; ?>
	</div>
</body>
</html>