<?php echo $html->docType(); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo Configure::read('Neutrino.SiteTitle').' - '.$title_for_layout; ?></title>
	<meta name="Description" content="<?php echo Configure::read('Neutrino.SiteDescription'); ?>" />
	<?php echo $html->charset(); ?>
	<?php echo $articleFeed->renderLink(); ?>
	<link rel="icon" href="<?php echo $this->webroot; ?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo $this->webroot; ?>favicon.ico" type="image/x-icon" />
	<?php echo $html->css('default'); ?>
	<?php echo $html->css('syntaxhighlighter'); ?>
	<?php
	if (isset($javascript))
	{
	    echo $javascript->link(
	    	array(
		    	'jquery',
		    	'prototype',
		    	'neditor/tabulator',
		    	'dp.SyntaxHighlighter/Scripts/shCore',
		    	'dp.SyntaxHighlighter/Scripts/shBrushPhp'
		    )
		);
	}
	?>
	<?php echo $scripts_for_layout; ?>
</head>
<body>
	<?php echo $html->div(null, null, array('id' => 'page')); ?>
		<?php echo $html->div(null, null, array('id' => 'header')); ?>
			<?php echo $html->div(null, null, array('id' => 'headerimg')); ?>
				<h1><?php echo $html->link(Configure::read('Neutrino.SiteTitle'), '/'); ?></h1>
				<?php echo $html->div('description'); ?>
					<?php echo Configure::read('Neutrino.SiteDescription'); ?>
				</div>
			</div>
		</div>
		<hr />
		<?php echo $html->div('narrowcolumn', null, array('id' => 'content')); ?>
			<?php echo $content_for_layout; ?>
			<div class="navigation">
				<div class="alignleft"></div>
				<div class="alignright"></div>
			</div>
		</div>

		<?php echo $html->div(null, null, array('id' => 'sidebar')); ?>
			<?php echo $this->element('sitemenu'); ?>
			<?php echo $this->element('usermenu'); ?>
			<?php echo $this->element('searchbox'); ?>
			<div style="width:100%; text-align:center; margin-top:40px;">
			<?php echo $html->link(
						$html->image(
							'cake.power.gif',
							array(
								'alt'		=> __("CakePHP: the rapid development php framework", true),
								'border'	=> "0",
								)
							),
						'http://www.cakephp.org/',
						array('target' => '_new'), null, false
					);
			?>
			<br />
			<?php echo $html->link(
						$html->image(
							'firefox_80x15.png',
							array(
								'alt'		=> __('Get Firefox', true),
								'border'	=> 0,
								'width'		=> 80,
								'height'	=> 15
								)
							),
						'http://getfirefox.com/',
						array('target' => '_new', 'title' => 'Get Firefox - The Browser, Reloaded.'), null, false
					);
			?>
			</div>
		</div>
		<hr />
		<?php echo $html->div(null, null, array('id' => 'footer')); ?>
			<p>
			Copyright &copy; lecterror 2007-3827</p>
		</div>
		<?php
		if (isset($javascript))
			echo $javascript->codeBlock('dp.SyntaxHighlighter.HighlightAll("code_snippet");');
		?>
	</div>
</body>
</html>
