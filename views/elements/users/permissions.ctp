<?php
if (empty($userPermissions[0]['Aco']))
{
	echo '<p>'.__('No permissions set for the selected user!', true).'</p>';
}
else
{
	$requestUrl = Router::url(array('controller' => 'users', 'action' => 'save_permission'));
	$ajaxTemplate = <<<END
new Ajax.Request
	(
		'%s/feature:%s/aclaction:%s/value:' + this.value,
		{
			onLoading: function() { Element.show('ajaxIndicator'); },
			onSuccess: function(transport) { Element.hide('ajaxIndicator'); $('flash-wrap').update(transport.responseText); }
		}
	)
END;

	foreach ($userPermissions[0]['Aco'] as $permission): ?>
		<div class="userFeaturePermissions">
			<h1><?php echo $permission['alias']; ?></h1>
			<dl>
				<dt>Create</dt>
				<dd><?php
					echo $auth->permissionToInput
						(
							array
							(
								'fieldName' => $permission['alias'].'.create',
								'value' => $permission['Permission']['_create'],
								'attributes' => array
								(
									'onChange' => sprintf($ajaxTemplate, $requestUrl, $permission['alias'], '_create')
								)
							)
						);
				?></dd>
				<dt>Read</dt>
				<dd><?php
					echo $auth->permissionToInput
						(
							array
							(
								'fieldName' => $permission['alias'].'.read',
								'value' => $permission['Permission']['_read']
							)
						);
				?></dd>
				<dt>Read own</dt>
				<dd><?php
					echo $auth->permissionToInput
						(
							array
							(
								'fieldName' => $permission['alias'].'.read_own',
								'value' => $permission['Permission']['_read_own']
							)
						);
				?></dd>
				<dt>Update</dt>
				<dd><?php
					echo $auth->permissionToInput
						(
							array
							(
								'fieldName' => $permission['alias'].'.update',
								'value' => $permission['Permission']['_update']
							)
						);
				?></dd>
				<dt>Update own</dt>
				<dd><?php
					echo $auth->permissionToInput
						(
							array
							(
								'fieldName' => $permission['alias'].'.update_own',
								'value' => $permission['Permission']['_update_own']
							)
						);
				?></dd>
				<dt>Delete</dt>
				<dd><?php
					echo $auth->permissionToInput
						(
							array
							(
								'fieldName' => $permission['alias'].'.delete',
								'value' => $permission['Permission']['_delete']
							)
						);
				?></dd>
				<dt>Delete own</dt>
				<dd><?php
					echo $auth->permissionToInput
						(
							array
							(
								'fieldName' => $permission['alias'].'.delete_own',
								'value' => $permission['Permission']['_delete_own']
							)
						);
				?></dd>
			</dl>
		</div>
	<?php
	endforeach;
}
?>