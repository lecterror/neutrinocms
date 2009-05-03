<h1>
<?php echo $html->link('Download categories', array('controller' => 'download_categories'), array('title' => 'Permanent link to download categories')); ?>
<?php
if ($auth->valid())
{
	?>
	<span style="font-size:x-small">
		[
		<?php
		echo $html->link('Add', array('controller' => 'download_categories', 'action' => 'add'), array('title' => 'Add a new category'));
		?>
		]
	</span>
	<?php
}
?>
</h1>

<table>
<?php
echo $html->tableHeaders(array('Name', 'Description'));

foreach ($categories as $category)
{
	?><tr><td style="padding: 0px 10px 10px 0px; text-align:left;"><?php
	echo $html->link($category['DownloadCategory']['name'], array('controller' => 'download_categories', 'action' => 'view', $category['DownloadCategory']['slug']));
	?></td><td><?php
	echo $category['DownloadCategory']['description'];
	?></tr><?php
}
?>
</table>
