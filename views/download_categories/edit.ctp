<?php $this->pageTitle = sprintf(__('Edit download category - %s', true), $this->data['DownloadCategory']['name']); ?>
<h2><?php __('Edit download category'); ?></h2>
<?php echo $form->create('DownloadCategory', array('url' => array('controller' => 'download_categories', 'action' => 'edit', $this->data['DownloadCategory']['slug']), 'id' => 'category_form')); ?>
	<?php echo $html->div('download-category-inputbox'); ?>
		<?php echo $form->input('DownloadCategory.name', array('label' => 'Name')); ?>
		<?php echo $form->input('DownloadCategory.description', array('type' => 'textarea', 'label' => 'Description')); ?>
		<?php echo $form->submit(__('Save and continue editing', true), array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
		<?php echo $form->submit(__('Save', true), array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>
<?php echo $javascript->codeBlock('Form.focusFirstElement("category_form");'); ?>