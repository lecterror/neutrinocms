<?php $this->pageTitle = __('Download category not found', true); ?>
<?php
if ($similar)
{
	$count = count($similar);
	echo $html->para
		(
			null,
			sprintf
			(
				__n
				(
					'We have found %s similar category:',
					'We have found %s similar categories:',
					$count,
					true
				),
				$count
			)
		);
	?>
	<p>
		<table style="width:90%; margin:auto;">
		<?php
		echo $html->tableHeaders(array(__('Name', true), __('Description', true)));

		foreach ($similar as $category)
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
	</p>
	<?php
}
?>
<?php
if ($auth->valid() && isset($slug))
{
	$createLink = $html->link
		(
			sprintf(__('Create "%s"!', true), $slug),
			array
			(
				'controller' => 'download_categories',
				'action' => 'add',
				$slug
			)
		);
	echo sprintf('<div style="text-align:center;">%s</div>', $createLink);
}
?>