<?php
if (isset($download_menu_items) && (count($download_menu_items) > 0))
{
	?>
	<h1><?php __('Downloads'); ?></h1>
	<ul class="sitemenu">
	<?php
	$items = array();

	foreach ($download_menu_items as $item)
	{
		$items[] = sprintf
			(
				'<li>%s</li>',
				$html->link
				(
					$item['DownloadCategory']['name'],
					array
					(
						'controller' => 'download_categories',
						'action' => 'view',
						$item['DownloadCategory']['slug']
					)
				)
			);
	}

	echo implode('', $items);
	?>
	</ul>
	<?php
}
?>