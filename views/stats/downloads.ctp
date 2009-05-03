<?php $this->pageTitle = 'Download statistics'; ?>
<table>
<?php echo $html->tableHeaders(array('Download name', 'Downloads')); ?>
<?php
foreach ($stats as $data)
{
	$link = $html->link(
			$data['Download']['name'],
			array('controller' => 'downloads', 'action' => 'view', $data['Download']['slug'])
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