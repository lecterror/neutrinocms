<?php $this->pageTitle = 'Edit download category - '.$this->data['DownloadCategory']['name']; ?>
<h2>Edit download category</h2>
<?php echo $form->create('DownloadCategory', array('url' => array('controller' => 'download_categories', 'action' => 'edit', $this->data['DownloadCategory']['slug']), 'id' => 'category_form')); ?>
	<?php echo $html->div('download-category-inputbox'); ?>
		<?php echo $form->input('DownloadCategory.name', array('label' => 'Name')); ?>
		<?php echo $form->input('DownloadCategory.description', array('type' => 'textarea', 'label' => 'Description')); ?>
		<?php echo $form->submit('Save and continue editing', array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
		<?php echo $form->submit('Save', array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>
<?php echo $javascript->codeBlock('Form.focusFirstElement("category_form");'); ?>