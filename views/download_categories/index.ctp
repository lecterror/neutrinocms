<h1>
<?php echo $html->link(__('Download categories', true), array('controller' => 'download_categories'), array('title' => __('Permanent link to download categories', true))); ?>
<?php
if ($auth->valid())
{
	?>
	<span style="font-size:x-small">
		[
		<?php
		echo $html->link
			(
				__('Add', true),
				array
				(
					'controller' => 'download_categories',
					'action' => 'add'
				),
				array
				(
					'title' => __('Add a new category', true)
				)
			);
		?>
		]
	</span>
	<?php
}
?>
</h1>

<table>
<?php
echo $html->tableHeaders(array(__('Name', true), __('Description', true)));

foreach ($categories as $category)
{
	?>
	<tr><td style="padding: 0px 10px 10px 0px; text-align:left;"><?php
	echo $html->link
		(
			$category['DownloadCategory']['name'],
			array
			(
				'controller' => 'download_categories',
				'action' => 'view',
				$category['DownloadCategory']['slug']
			)
		);
	?></td><td><?php
	echo $category['DownloadCategory']['description'];
	?></tr><?php
}
?>
</table>
