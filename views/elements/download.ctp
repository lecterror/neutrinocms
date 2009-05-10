<?php
$full = (isset($fullDownloadView) && $fullDownloadView);

if ($full)
{
	$this->pageTitle = sprintf(__('%s - Download', true), $download['Download']['name']);
}
?>
<?php echo $html->div('download'); ?>
	<h1>
		<?php
		echo $html->link
			(
				$download['Download']['name'],
				array
				(
					'controller' => 'downloads',
					'action' => 'view',
					$download['Download']['slug']
				),
				array
				(
					'title' => sprintf(__('Permanent link to %s', true), $download['Download']['name']),
					'class' => 'download-title'
				)
			);

		$allowDownloadsEdit = $allowDownloadsDelete = false;

		if ($auth->isValid())
		{
			$allowDownloadsEdit = $auth->check('downloads', 'edit', $download['Download']['user_id']);
			$allowDownloadsDelete = $auth->check('downloads', 'delete', $download['Download']['user_id']);
		}

		if ($allowDownloadsEdit || $allowDownloadsDelete)
		{
			?>
			<span class="download-actions">
				[
				<?php
				$actionLinks = array();

				if ($allowDownloadsEdit)
				{
					$actionLinks[] = $html->link
						(
							__('Edit', true),
							array
							(
								'controller' => 'downloads',
								'action' => 'edit',
								$download['Download']['slug']
							),
							array
							(
								'title' => sprintf(__('Edit %s', true), $download['Download']['name'])
							)
						);
				}

				if ($allowDownloadsDelete)
				{
					$actionLinks[] = $html->link
						(
							__('Delete', true),
							array
							(
								'controller' => 'downloads',
								'action' => 'delete',
								$download['Download']['slug']
							),
							array
							(
								'title' => sprintf(__('Delete %s', true), $download['Download']['name'])
							)
						);
				}

				echo implode(' | ', $actionLinks);
				?>
				]
			</span>
			<?php
		}
		?>
	</h1>
	<table id="download-info-table">
		<?php
		if ($full)
		{
			?>
			<tr>
				<td class="download-info"><?php __('File name:'); ?></td>
				<td><?php echo $html->div('download-data', $download['Download']['display_file_name']); ?></td>
			</tr>
			<tr>
				<td class="download-info"><?php __('Added:'); ?></td>
				<td><?php echo $html->div('download-data', date(Configure::read('Neutrino.DatetimeDisplayFormat'), strtotime($download['Download']['created']))); ?></td>
			</tr>
			<tr>
				<td class="download-info"><?php __('Size:'); ?></td>
				<td><?php echo $html->div('download-data', $number->toReadableSize($download['Download']['size'])); ?></td>
			</tr>
			<tr>
				<td class="download-info"><?php __('Category:'); ?></td>
				<td><?php
					echo $html->div
						(
							'download-data',
							$html->link
							(
								$download['DownloadCategory']['name'],
								array
								(
									'controller' => 'download_categories',
									'action' => 'view',
									$download['DownloadCategory']['slug']
								)
							)
						);
					?></td>
			</tr>
			<tr>
				<td class="download-info"><?php __('Downloaded:'); ?></td>
				<td>
					<?php
					$dlCount = $download['Download']['downloaded'];
					echo $html->div
						(
							'download-data',
							sprintf
							(
							 	__n
							 	(
							 		'%d time',
							 		'%d times',
							 		$dlCount,
							 		true
							 	),
							 	$dlCount
							)
						);
					?>
				</td>
			</tr>
			<tr>
				<td class="download-info"><?php __('Description:'); ?></td>
				<td><?php echo $html->div('download-data', nl2br($download['Download']['description'])); ?></td>
			</tr>
			<tr>
				<td class="download-info"><?php __('Rating:'); ?></td>
				<td>
					<?php
					$voted = false;
					$votedValue = 0;
					$totalVotes = 0;
					$totalRating = 0;

					if (isset($download['Rating']['Summary']))
					{
						$voted = $download['Rating']['Summary']['voted'];
						$votedValue = $download['Rating']['Summary']['rating'];
						$totalVotes = $download['Rating']['Summary']['totalVotes'];
						$totalRating = $download['Rating']['Summary']['totalRating'];
					}

					$url = $html->url
						(
							array
							(
								'controller' => 'downloads',
								'action' => 'rate',
								$download['Download']['slug']
							)
						);

					echo $this->element
						(
							'rating',
							compact('voted', 'votedValue', 'totalVotes', 'totalRating', 'url')
						);
					?>
				</td>
			</tr>
			<?php
		}
		else
		{
			?>
			<tr>
				<td><?php echo $html->div('download-data', nl2br($download['Download']['description'])); ?></td>
			</tr>
			<?php
		}
		?>
	</table>
	<?php
	if ($full)
	{
		echo $form->create
			(
				null,
				array
				(
					'url' => array
					(
						'controller' => 'downloads',
						'action' => 'get',
						$download['Download']['slug']
					),
					'id' => 'DownloadGetForm' /* honestly, what the hell?? */
				)
			);
	}
	?>
	<div id="download-get-file">
		<?php
		if ($full)
		{
			echo $form->submit(__('Download', true), array('class' => 'button'));
		}
		else
		{
			echo $html->link
				(
					__('More info...', true),
					array
					(
						'controller' => 'downloads',
						'action' => 'view',
						$download['Download']['slug']
					)
				);
		}
		?>
	</div>
	<?php
	if ($full)
	{
		echo $form->end();
	}
	?>
</div>