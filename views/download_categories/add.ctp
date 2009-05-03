<?php $this->pageTitle = 'Add new download category'; ?>
<h2>Add new download category</h2>
<?php echo $form->create(null, array('url' => array('controller' => 'download_categories', 'action' => 'add'))); ?>
	<?php echo $html->div('download-category-inputbox'); ?>
		<?php echo $form->input('DownloadCategory.name', array('label' => 'Name')); ?>
		<?php echo $form->input('DownloadCategory.description', array('type' => 'textarea', 'label' => 'Description')); ?>
		<?php echo $form->submit('Save', array('class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>
<?php echo $javascript->codeBlock('Form.focusFirstElement("DownloadCategoryAddForm");'); ?>