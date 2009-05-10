<h2><?php __('Article statistics'); ?></h2>
<?php $this->pageTitle = __('Article statistics', true); ?>
<table>
<?php
echo $html->tableHeaders
	(
		array
		(
			__('Article title', true),
			__('Visits', true),
			__('Visits by RSS', true)
		)
	);

foreach ($stats as $data)
{
	$link = $html->link
		(
			$data['Article']['title'],
			array
			(
				'controller' => 'articles',
				'action' => 'view',
				$data['Article']['slug']
			)
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