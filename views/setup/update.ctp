<h1>NeutrinoCMS database upgrade</h1>
<div id="neutrino-update">
	<?php echo $form->create(null, array('url' => array('controller' => 'setup', 'action' => 'update_db'))); ?>
		<?php echo $html->div('install-inputbox'); ?>
			<p>
				Click next to upgrade your database from version
				<?php echo Configure::read('Neutrino.CurrentDbVersion'); ?> to
				<?php echo $requiredDbVersion; ?>
			</p>
			<?php echo $form->hidden('Step', array('value' => '0')); ?>
			<?php echo $form->submit('Next', array('class' => 'button')); ?>
		</div>
	<?php echo $form->end(); ?>
</div>