<h2><?php __('Connect to a database'); ?></h2>
<?php echo $form->create(false, array('url' => array('action' => $this->action, 'connect_database'))); ?>
	<?php echo $html->div('install-inputbox'); ?>
		<p style="text-align:justify;">
			<?php __('Neutrino is unable to connect to your database.'); ?>
			<?php __('Enter the necessary information to create your database configuration file.'); ?>
			<?php __('If the database you provide does not exist, Neutrino will try to create it.'); ?>
		</p>
		<?php
		echo $form->input('DB.Host', array('label' => __('Host address', true)));
		echo $form->input('DB.Username', array('label' => __('Username', true)));
		echo $form->input
			(
				'DB.Password',
				array
				(
					'label' => __('Password', true),
					'type' => 'password',
					'value' => ''
				)
			);
		echo $form->input('DB.Name', array('label' => __('Database name', true)));
		?>
		<br />
		<?php echo $form->submit(__('Continue', true), array('class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>