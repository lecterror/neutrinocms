<div class="groupPermissions index">
	<h2>
		<?php __('User groups'); ?>
		<span class="group-permissions-actions">
					[
					<?php
						echo $html->link
							(
								__('Add', true),
								array
								(
									'controller' => 'group_permissions',
									'action' => 'add'
								)
							);
					?>
					]
		</span>
	</h2>
	<?php foreach ($aros as $aro): ?>
		<div>
			<?php echo $html->link($aro['Aro']['alias'], array('action' => 'view', $aro['Aro']['id'])); ?>
		</div>
	<?php endforeach; ?>
</div>