<?php echo $html->docType(/*'xhtml-trans'*/); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $title_for_layout; ?></title>
	<?php echo $html->charset(); ?>
	<link rel="icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
	<?php echo $html->css('install'); ?>
	<?php echo $scripts_for_layout; ?>
</head>
<body>
	<?php echo $html->div(null, null, array('id' => 'page')); ?>
		<?php echo $html->div(null, null, array('id' => 'header')); ?>
			<?php echo $html->div(null, null, array('id' => 'headerimg')); ?>
				<h1><?php echo $html->link('NeutrinoCMS', 'http://dsi.vozibrale.com/'); ?></h1>
				<?php echo $html->div('description'); ?>
					<?php echo 'A simple CMS for developers'; ?>
				</div>
			</div>
		</div>
		<hr />
		<?php echo $html->div('narrowcolumn', null, array('id' => 'content')); ?>
			<h2 class="title">SETUP</h2>
			<?php echo $content_for_layout; ?>
		</div>
		<?php echo $html->div(null, null, array('id' => 'footer')); ?>
			<p>
			Copyright &copy; lecterror 2007-3827</p>
		</div>

	</div>
</body>
</html>
