<h1><?php __('NeutrinoCMS database upgrade'); ?></h1>
<div id="neutrino-update">
	<?php echo $form->create(false, array('url' => array('controller' => 'update', 'action' => 'update', 'intro'))); ?>
		<?php echo $html->div('install-inputbox'); ?>
			<p>
				<?php __('It is recommended that you backup your database before proceeding with the update.'); ?>
				<?php
				echo sprintf
					(
						__('Click next to upgrade your database from migration %d to %d.', true),
						$currentDbVersion,
						$requiredDbVersion
					);
				?>
			</p>
			<?php echo $form->hidden('Dummy.field', array('value' => '0')); ?>
			<?php echo $form->submit(__('Next', true), array('class' => 'button')); ?>
		</div>
	<?php echo $form->end(); ?>
</div>
