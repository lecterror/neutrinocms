<h2><?php __('Groups'); ?></h2>
<table>
<?php
echo $html->tableHeaders(array(__('Group', true), __('Feature', true)));

foreach ($aros as $aro)
{
	$aroLink = $html->link($aro['Aro']['alias'], array('action' => 'view', 'group' => $aro['Aro']['id']));
	$acoLinks = array();

	foreach ($aro['Aco'] as $aco)
	{
		$acoLinks[] = $html->link
			(
				$aco['alias'],
				array
				(
					'action' => 'edit',
					'group' => $aro['Aro']['id'],
					'feature' => $aco['id']
				)
			);
	}

	echo $html->tableCells(array($aroLink, implode('<br />', $acoLinks)));
}
?>
</table>