<?php $this->pageTitle = 'Delete download - '.$download['Download']['name']; ?>
<?php echo $form->create(null, array('url' => array('controller' => 'downloads', 'action' => 'delete', $download['Download']['slug']))); ?>
	<?php echo $html->div('download-deletebox'); ?>
		<?php
		$text =
			' I am sure I want to delete the download '.
			$html->link($download['Download']['name'], array('controller' => 'downloads', 'action' => 'view', $download['Download']['slug']));

		echo $form->input('Download.delete', array(
			'type'	=> 'checkbox',
			'label' => array('style' => 'display:inline; margin-bottom:15px;', 'text' => $text),
			'style' => 'display:inline; margin-top:15px;')); ?>

		<?php echo $form->submit('Delete', array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
		<?php echo $form->submit('Cancel', array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>