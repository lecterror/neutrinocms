<div class="groupPermissions addGroup">
	<h2><?php __('Add group'); ?></h2>
<?php
echo $form->create
	(
		false,
		array
		(
			'url' => array
			(
				'action' => 'add_group',
				'parent' => $this->passedArgs['parent']
			)
		)
	);

echo $form->hidden('Aro.parent_id', array('value' => $this->passedArgs['parent']));
echo $form->input('Aro.alias');
echo $form->submit(__('Add', true));
echo $form->end();
?>
</div>