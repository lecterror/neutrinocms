<div class="groupPermissions editPermissions">
	<h2><?php __('Edit group permissions'); ?></h2>
	<dl>
		<dt><?php __('Group'); ?></dt>
		<dd><?php echo $html->link($group['Aro']['alias'], array('action' => 'view', $group['Aro']['id'])); ?></dd>
		<dt><?php __('Feature'); ?></dt>
		<dd><?php echo $feature['Aco']['alias']; ?></dd>
	</dl>

	<?php
	echo $form->create(false, array('url' => array('action' => $this->action)));
	echo $form->hidden('Group.id', array('value' => $group['Aro']['id']));
	echo $form->hidden('Feature.alias', array('value' => $feature['Aco']['alias']));

	foreach ($permissions as $key => $value):
		//echo $html->para(null, Inflector::humanize($key));

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