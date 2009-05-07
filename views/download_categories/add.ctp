<?php $this->pageTitle = __('Add new download category', true); ?>
<h2><?php __('Add new download category'); ?></h2>
<?php
echo $form->create(null, array('url' => array('controller' => 'download_categories', 'action' => 'add')));
	echo $html->div('download-category-inputbox');
		echo $form->input('DownloadCategory.name', array('label' => 'Name'));
		echo $form->input('DownloadCategory.description', array('type' => 'textarea', 'label' => 'Description'));
		echo $form->submit(__('Save', true), array('class' => 'button'));
		?>
	</div>
<?php echo $form->end(); ?>
<?php echo $javascript->codeBlock('Form.focusFirstElement("DownloadCategoryAddForm");'); ?>