<?php
foreach ($aroPath as $item)
{
	$html->addCrumb
		(
			$item['Aro']['alias'],
			array($item['Aro']['id'])
		);
}
?>
<div class="groupPermissions view">
	<h2>
		<?php __('Group permissions'); ?>
		<span class="group-permission-actions">
			[
			<?php echo $html->link(__('Add a subgroup', true), array('action' => 'add', 'parent' => $aro['Aro']['id'])); ?>
			]
		</span>
	</h2>
	<div id="groupPath">
		<dl>
			<dt><?php __('Group path:'); ?></dt>
			<dd><?php echo $html->getCrumbs('&nbsp;&gt;&nbsp;'); ?></dd>
		</dl>
	</div>
	<dl id="subgroups">
		<dt><?php __('Immediate subgroups:'); ?></dt>
		<?php
		if (empty($aroChildren))
		{
			echo '<dd>';
			__('None');
			echo '</dd>';
		}
		else
		{
			foreach ($aroChildren as $childNode)
			{
				if (empty($childNode['Aro']['foreign_key']))
				{
					echo '<dd>';
					echo $html->link($childNode['Aro']['alias'], array($childNode['Aro']['id']));
					echo '</dd>';
				}
			}
		}
		?>
	</dl>
	<dl id="users">
		<dt><?php __('Immediate user members:'); ?></dt>
		<?php
		if (empty($users))
		{
			echo '<dd>';
			__('None');
			echo '</dd>';
		}
		else
		{
			foreach ($users as $user)
			{
				echo '<dd>';
				echo $html->link
					(
						sprintf
						(
							'%s (%s %s)',
							$user['User']['username'],
							$user['User']['first_name'],
							$user['User']['last_name']
						),
						array
						(
							'controller' => 'user_permissions',
							'action' => 'view',
							$user['User']['id']
						)
					);
				echo '</dd>';
			}
		}
		?>
	</dl>
	<dl id="featurePermissions">
		<dt><?php __('Set permissions for features:'); ?></dt>
		<?php
		$features = NEUTRINO_CONFIG::features();

		foreach ($features as $feature)
		{
			echo '<dd>';
			echo $html->link($feature, array('action' => 'permissions', 'group' => $aro['Aro']['id'], 'featureAlias' => $feature));
			echo '</dd>';
		}
		?>
	</dl>
</div>