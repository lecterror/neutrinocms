<div class="userPermissions editPermissions">
	<h2><?php __('Edit user permissions'); ?></h2>
	<dl>
		<dt><?php __('User'); ?></dt>
		<dd><?php echo $html->link($user['User']['username'], array('action' => 'view', $user['User']['id'])); ?></dd>
		<dt><?php __('Feature'); ?></dt>
		<dd><?php echo $feature['Aco']['alias']; ?></dd>
	</dl>

	<?php
	echo $form->create(false, array('url' => array('action' => $this->action)));
	echo $form->hidden('User.id', array('value' => $user['User']['id']));
	echo $form->hidden('Feature.alias', array('value' => $feature['Aco']['alias']));

	foreach ($permissions as $key => $value):
		$options = array('type' => 'radio', 'options' => array('1' => 'Allow', '-1' => 'Deny', '0' => 'Inherit'));

		if (!isset($isPost) || !$isPost)
		{
			$options = array_merge($options, array('value' => $value));
		}

		echo $form->input('Permissions.'.$key, $options);
	endforeach;
	echo $form->submit();
	echo $form->end();
	?>
</div>