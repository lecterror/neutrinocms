<?php $this->pageTitle = $category['DownloadCategory']['name']; ?>
<?php echo $html->div('download-category'); ?>
	<h2><?php
		echo $html->link
			(
				$category['DownloadCategory']['name'],
				array
				(
					'controller' => 'download_categories',
					'action' => 'view',
					$category['DownloadCategory']['slug']
				),
				array
				(
					'title' => sprintf
					(
						__('Permanent link to %s download category', true),
						$category['DownloadCategory']['name']
					),
					'class' => 'download-category-title'
				)
			);

		if ($auth->valid())
		{
			?>
			<span class="download-category-actions">
				[
				<?php
				echo $html->link
					(
						__('Add', true),
						array
						(
							'controller' => 'downloads',
							'action' => 'add',
							'category' => $category['DownloadCategory']['slug']
						),
						array
						(
							'title' => __('Add a new download', true)
						)
					);
				echo ' | ';
				echo $html->link
					(
						__('Edit', true),
						array
						(
							'controller' => 'download_categories',
							'action' => 'edit',
							$category['DownloadCategory']['slug']
						),
						array
						(
							'title' => sprintf
							(
								__('Edit %s', true),
								$category['DownloadCategory']['name']
							)
						)
					);
				echo ' | ';
				echo $html->link
					(
						__('Delete', true),
						array
						(
							'controller' => 'download_categories',
							'action' => 'delete',
							$category['DownloadCategory']['slug']
						),
						array
						(
							'title' => sprintf
							(
								__('Delete %s', true),
								$category['DownloadCategory']['name']
							)
						)
					);
				?>
				]
			</span>
			<?php
		}
		?>
	</h2>
	<p class="download-category-header">
		<small class="download-category-description">
			<?php echo $category['DownloadCategory']['description'] ?>
		</small>
	</p>

	<?php
	if (!empty($downloads))
	{
		foreach ($downloads as $download)
		{
			echo $this->element('download', compact('download'));
		}
	}
	else
	{
		?>
		<h3 style="text-align:center;"><?php __('No downloads!'); ?></h3>
		<?php
		if ($auth->valid())
		{
			$createLink = $html->link
				(
					__('Create one!', true),
					array
					(
						'controller' => 'downloads',
						'action' => 'add',
						'category' => $category['DownloadCategory']['slug']
					)
				);
			echo sprintf('<div style="text-align:center;">%s</div>', $createLink);
		}
	}
	?>
</div>