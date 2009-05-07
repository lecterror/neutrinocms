<h1><?php __('NeutrinoCMS database upgrade'); ?></h1>
<div id="neutrino-update">
	<?php echo $form->create(null, array('url' => array('controller' => 'setup', 'action' => 'update_db'))); ?>
		<?php echo $html->div('install-inputbox'); ?>
			<p>
				<?php
				echo sprintf
					(
						__('Click next to upgrade your database from version %s to %s', true),
						Configure::read('Neutrino.CurrentDbVersion'),
						$requiredDbVersion
					);
				?>
			</p>
			<?php echo $form->hidden(__('Step', true), array('value' => '0')); ?>
			<?php echo $form->submit(__('Next', true), array('class' => 'button')); ?>
		</div>
	<?php echo $form->end(); ?>
</div>