<h2><?php __('Database initialization'); ?></h2>
<?php echo $form->create(false, array('url' => array('action' => $this->action, 'initialize_database'))); ?>
	<?php echo $html->div('install-inputbox', null, array('style' => 'text-align:center;')); ?>
		<p><?php __('This step will create all the tables necessary for Neutrino operation.'); ?></p>
		<?php echo $form->hidden(__('Installation.Step', true), array('value' => '1')); ?>
		<?php echo $form->submit(__('Continue', true), array('class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>