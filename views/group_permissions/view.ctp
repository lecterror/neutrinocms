<?php
foreach ($aroPath as $item)
{
	$html->addCrumb
		(
			$item['Aro']['alias'],
			array
			(
				'group' => $item['Aro']['id']
			)
		);
}


?>
<div class="groupPermissions view">
	<h2><?php __('Group permissions'); ?></h2>
	<div id="groupPath">
		<dl>
			<dt><?php __('Group path:'); ?></dt>
			<dd><?php echo $html->getCrumbs('&nbsp;&gt;&nbsp;'); ?></dd>
		</dl>
	</div>
	<dl id="subgroups">
		<dt><?php __('Subgroups:'); ?></dt>
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
					echo $html->link($childNode['Aro']['alias'], array('group' => $childNode['Aro']['id']));
					echo '</dd>';
				}
			}
		}
		?>
	</dl>
	<dl id="users">
		<dt><?php __('Users:'); ?></dt>
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
							'user' => $user['User']['id']
						)
					);
				echo '</dd>';
			}
		}
		?>
	</dl>
	<dl id="featurePermissions">
		<dt><?php __('Feature permissions:'); ?></dt>
		<?php
		if (empty($users))
		{
			echo '<dd>';
			__('None');
			echo '</dd>';
		}
		else
		{
			foreach ($aro['Aco'] as $aco)
			{
				echo '<dd>';
				echo $html->link
					(
						$aco['alias'],
						array
						(
							'action' => 'edit',
							'group' => $aro['Aro']['id'],
							'feature' => $aco['id']
						)
					);
				echo '</dd>';
			}
		}
		?>
	</dl>
	<div class="actions">
		<ul>
			<li><?php echo $html->link(__('Add a subgroup', true), array('action' => 'add_group', 'parent' => $aro['Aro']['id'])); ?></li>
			<li><?php echo $html->link(__('Add feature permissions', true), array('action' => 'add_feature', 'parent' => $aro['Aro']['id'])); ?></li>
		</ul>
	</div>
</div>