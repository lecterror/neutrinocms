<?php
$this->pageTitle = sprintf(__('Delete download category - %s', true), $category['DownloadCategory']['name']);
echo $form->create(null, array('url' => array('controller' => 'download_categories', 'action' => 'delete', $category['DownloadCategory']['slug'])));
echo $html->div('download-category-deletebox'); ?>
	<span class="warning-message"><?php __('Warning:'); ?></span> <?php __('deleting a category will delete all related downloads!'); ?>
	<?php
	$text = sprintf
		(
			__('I am sure I want to delete the download category %s', true),
			$html->link
			(
				$category['DownloadCategory']['name'],
				array
				(
					'controller' => 'download_categories',
					'action' => 'view',
					$category['DownloadCategory']['slug']
				)
			)
		);

	echo $form->input
		(
			'DownloadCategory.delete',
			array
			(
				'type'	=> 'checkbox',
				'style' => 'display:inline; margin-top:15px;',
				'label' => array
				(
					'style' => 'display:inline; margin-bottom:15px; margin-left:5px;',
					'text' => $text
				)
			)
		);
	?>
	<?php echo $form->submit(__('Delete', true), array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
	<?php echo $form->submit(__('Cancel', true), array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
</div>
<?php echo $form->end(); ?>