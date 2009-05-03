<?php $this->pageTitle = $category['DownloadCategory']['name']; ?>
<?php echo $html->div('download-category'); ?>
	<h2><?php echo $html->link(
			$category['DownloadCategory']['name'], array('controller' => 'download_categories', 'action' => 'view', $category['DownloadCategory']['slug']),
			array(
				'title' => 'Permanent link to '.$category['DownloadCategory']['name'].' download category',
				'class' => 'download-category-title'
			)); ?>
		<?php
		if ($auth->valid())
		{
			?>
			<span class="download-category-actions">
				[
				<?php
				echo $html->link('Add', array('controller' => 'downloads', 'action' => 'add', 'category' => $category['DownloadCategory']['slug']), array('title' => 'Add a new download'));
				echo ' | ';
				echo $html->link('Edit', array('controller' => 'download_categories', 'action' => 'edit', $category['DownloadCategory']['slug']), array('title' => 'Edit '.$category['DownloadCategory']['name']));
				echo ' | ';
				echo $html->link('Delete', array('controller' => 'download_categories', 'action' => 'delete', $category['DownloadCategory']['slug']), array('title' => 'Delete '.$category['DownloadCategory']['name']));
				?>
				]
			</span>
			<?php
		}
		?>
	</h2>
	<p class="download-category-header">
		<small class="download-category-description"><?php echo $category['DownloadCategory']['description'] ?></small>
	</p>

	<?php
	if (count($downloads))
	{
		foreach ($downloads as $download)
		{
			echo $this->element('download', compact('download'));
		}
	}
	else
	{
		?>
		<h3 style="text-align:center;">No downloads!</h3>
		<?php
		if ($auth->valid())
			echo '<div style="text-align:center;">'.$html->link('Create one!', array('controller' => 'downloads', 'action' => 'add', 'category' => $category['DownloadCategory']['slug'])).'</div>';
		?>
		<?php
	}
	?>
</div>