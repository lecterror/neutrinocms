<?php
if (isset($download_menu_items) && (count($download_menu_items) > 0))
{
	?>
	<h1>Downloads</h1>
	<ul class="sitemenu">
	<?php
	foreach ($download_menu_items as $item)
	{
		echo '<li>'.$html->link(
				$item['DownloadCategory']['name'],
				array
				(
					'controller' => 'download_categories',
					'action' => 'view',
					$item['DownloadCategory']['slug']
				)
			).'</li>';
	}
	?>
	</ul>
	<?php
}
?>