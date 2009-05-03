<h2>Step 1: Database initialization</h2>
<?php echo $form->create(null, array('url' => array('controller' => 'setup', 'action' => 'install_step1'))); ?>
	<?php echo $html->div('install-inputbox', null, array('style' => 'text-align:center;')); ?>
		<p>This step will create all the tables necessary for Neutrino operation.</p>
		<?php echo $form->hidden('Step', array('value' => '1')); ?>
		<?php echo $form->submit('Continue', array('class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>