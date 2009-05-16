<div id="ajaxIndicator" style="display:none;">
	<?php echo $html->image('throbber.gif'); ?>
</div>
<?php
$updateUrl = Router::url(array('controller' => 'user_permissions', 'action' => 'permissions'));
$ajaxString = <<<END
new Ajax.Updater
	(
		'userPermissionsContainer',
		'%s/user:' + this.value,
		{
			onLoading: function() { Element.show('ajaxIndicator'); },
			onComplete: function() { Element.hide('ajaxIndicator'); }
		}
	);
END;

echo $form->select
	(
		'userId',
		$users,
		'-1',
		array
		(
			'onChange' => sprintf($ajaxString, $updateUrl)
		),
		true
	);

echo $ajax->div('userPermissionsContainer');
	__('Select a user from a list to view active permissions');
echo $ajax->divEnd('userPermissionsContainer');
?>
