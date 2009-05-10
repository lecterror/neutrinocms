<h2><?php __('Download statistics'); ?></h2>
<?php $this->pageTitle = __('Download statistics', true); ?>
<table>
<?php echo $html->tableHeaders(array(__('Download name', true), __('Downloads', true))); ?>
<?php
foreach ($stats as $data)
{
	$link = $html->link
		(
			$data['Download']['name'],
			array
			(
				'controller' => 'downloads',
				'action' => 'view',
				$data['Download']['slug']
			)
		);

	echo $html->tableCells
		(
			array
			(
				$link,
				$data['Download']['downloaded']
			),
			array('class' => 'row-a'),
			array('class' => 'row-b')
		);
}
?>
</table>