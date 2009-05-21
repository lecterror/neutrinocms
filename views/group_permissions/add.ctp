<div class="groupPermissions form">
	<h2><?php __('Add group'); ?></h2>
<?php
echo $form->create(false, array('url' => array('action' => 'add')));
echo $html->div('groupPermissions-inputbox');

	$parentIdOptions = array('options' => $parents);

	if (isset($defaultParent))
	{
		$parentIdOptions['selected'] = $defaultParent;
	}

	echo $form->input('Aro.parent_id', $parentIdOptions);
	echo $form->input('Aro.alias', array('label' => __('Group name', true)));

	if (isset($aliasError) && !empty($aliasError))
	{
		// what kind of bleeding arse is this?
		// I can't use $form->error with no model??
		// FECK! ARSE! DRINK! GIRLS!
		// A PAIR OF FECKIN' WOMENS KNICKERS!
		$form->validationErrors['Aro']['alias'] = $aliasError;
		echo $form->error('Aro.alias');
	}

	echo $form->submit(__('Add', true));
echo '</div>';
echo $form->end();
?>
</div>
