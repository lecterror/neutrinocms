<?php
foreach ($aroPath as $item)
{
	$html->addCrumb
		(
			$item['Aro']['alias'],
			array('controller' => 'group_permissions', 'action' => 'view', $item['Aro']['id'])
		);
}
?>
<div class="userPermissions view">
	<h2>
		<?php __('User permissions'); ?>
	</h2>
	<div id="userPath">
		<dl>
			<dt><?php __('User path:'); ?></dt>
			<dd><?php echo $html->getCrumbs('&nbsp;&gt;&nbsp;'); ?></dd>
		</dl>
	</div>
	<dl id="featurePermissions">
		<dt><?php __('Set permissions for features:'); ?></dt>
		<?php
		$features = NEUTRINO_CONFIG::features();

		foreach ($features as $feature)
		{
			echo '<dd>';
			echo $html->link($feature, array('action' => 'permissions', 'user' => $user['User']['id'], 'featureAlias' => $feature));
			echo '</dd>';
		}
		?>
	</dl>
</div>