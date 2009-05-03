<?php
$full = (isset($fullDownloadView) && $fullDownloadView);

if ($full)
{
	$this->pageTitle = $download['Download']['name'].' - Download';
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
						'title' => 'Permanent link to '.$download['Download']['name'],
						'class' => 'download-title'
					)
				);

		if ($auth->valid())
		{
			?>
			<span class="download-actions">
				[
				<?php
				echo $html->link
					(
						'Edit',
						array
						(
							'controller' => 'downloads',
							'action' => 'edit',
							$download['Download']['slug']
						),
						array
						(
							'title' => 'Edit '.$download['Download']['name']
						)
					);
				echo ' | ';
				echo $html->link
					(
						'Delete',
						array
						(
							'controller' => 'downloads',
							'action' => 'delete',
							$download['Download']['slug']
						),
						array
						(
							'title' => 'Delete '.$download['Download']['name']
						)
					);
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
				<td class="download-info">File name:</td>
				<td><?php echo $html->div('download-data', $download['Download']['display_file_name']); ?></td>
			</tr>
			<tr>
				<td class="download-info">Added:</td>
				<td><?php echo $html->div('download-data', date(Configure::read('Neutrino.DatetimeDisplayFormat'), strtotime($download['Download']['created']))); ?></td>
			</tr>
			<tr>
				<td class="download-info">Size:</td>
				<td><?php echo $html->div('download-data', $number->toReadableSize($download['Download']['size'])); ?></td>
			</tr>
			<tr>
				<td class="download-info">Category:</td>
				<td><?php echo $html->div('download-data',
					$html->link($download['DownloadCategory']['name'],
						array('controller' => 'download_categories', 'action' => 'view', $download['DownloadCategory']['slug']))); ?></td>
			</tr>
			<tr>
				<td class="download-info">Downloaded:</td>
				<td><?php echo $html->div('download-data', $download['Download']['downloaded'].' time'.($download['Download']['downloaded'] == 1 ? '' : 's')); ?></td>
			</tr>
			<tr>
				<td class="download-info">Description:</td>
				<td><?php echo $html->div('download-data', nl2br($download['Download']['description'])); ?></td>
			</tr>
			<tr>
				<td class="download-info">Rating:</td>
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

				$url = $html->url(
						array
						(
							'controller' => 'downloads',
							'action' => 'rate',
							$download['Download']['slug']
						)
					);

				echo $this->element(
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
			echo $form->create(
				null,
				array(
					'url' => array(
						'controller' => 'downloads',
						'action' => 'get',
						$download['Download']['slug']
						),
					'id' => 'DownloadGetForm' /* honestly, what the hell?? */
					)
				); ?>
		<div id="download-get-file">
		<?php
		if ($full)
		{
			echo $form->submit('Download', array('class' => 'button'));
		}
		else
		{
			echo $html->link('More info...', array('controller' => 'downloads', 'action' => 'view', $download['Download']['slug']));
		}
		?>
		</div>
	<?php if ($full) echo $form->end(); ?>
</div>