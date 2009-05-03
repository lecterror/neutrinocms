<?php $this->pageTitle = 'Article statistics'; ?>
<table>
<?php echo $html->tableHeaders(array('Article title', 'Visits', 'Visits by RSS')); ?>
<?php
foreach ($stats as $data)
{
	$link = $html->link(
			$data['Article']['title'],
			array('controller' => 'articles', 'action' => 'view', $data['Article']['slug'])
		);

	echo $html->tableCells
		(
			array
			(
				$link,
				$data['Article']['hitcount'],
				$data['Article']['hitcount_rss']
			),
			array('class' => 'row-a'),
			array('class' => 'row-b')
		);
}
?>
</table>