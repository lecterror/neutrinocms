<?php $this->pageTitle = 'Delete download category - '.$category['DownloadCategory']['name']; ?>
<?php echo $form->create(null, array('url' => array('controller' => 'download_categories', 'action' => 'delete', $category['DownloadCategory']['slug']))); ?>
	<?php echo $html->div('download-category-deletebox'); ?>
		<span class="warning-message">Warning:</span> deleting a category will delete all related downloads!</p>
		<?php
		$text =
			' I am sure I want to delete the download category '.
			$html->link($category['DownloadCategory']['name'], array('controller' => 'download_categories', 'action' => 'view', $category['DownloadCategory']['slug']));

		echo $form->input('DownloadCategory.delete', array(
			'type'	=> 'checkbox',
			'label' => array('style' => 'display:inline; margin-bottom:15px;', 'text' => $text),
			'style' => 'display:inline; margin-top:15px;')); ?>

		<?php echo $form->submit('Delete', array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
		<?php echo $form->submit('Cancel', array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>