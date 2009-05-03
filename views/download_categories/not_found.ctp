<?php $this->pageTitle = 'Download category not found'; ?>
<?php
if ($similar)
{
	$count = count($similar);
	?>
	<p>We have found <?php echo $count; ?> similar categor<?php echo ($count > 1 ? 'ies' : 'y'); ?>:</p>
	<p>
		<table style="width:90%; margin:auto;">
		<?php
		echo $html->tableHeaders(array('Name', 'Description'));

		foreach ($similar as $category)
		{
			?><tr><td style="padding: 0px 10px 10px 0px; text-align:left;"><?php
			echo $html->link($category['DownloadCategory']['name'], array('controller' => 'download_categories', 'action' => 'view', $category['DownloadCategory']['slug']));
			?></td><td><?php
			echo $category['DownloadCategory']['description'];
			?></tr><?php
		}
		?>
		</table>
	</p>
	<?php
}
?>
<?php
if ($auth->valid() && isset($slug))
{
	echo '<div style="text-align:center;">'.$html->link('Create "'.$slug.'"!', array('controller' => 'download_categories', 'action' => 'add', $slug)).'</div>';
}
?>