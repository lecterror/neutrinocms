<?php $this->pageTitle = __('Add new file', true); ?>
<h2><?php __('Add new file'); ?></h2>
<?php echo $form->create(null, array('url' => array('controller' => 'attachments', 'action' => 'add'), 'type' => 'file')); ?>
	<?php echo $html->div('upload-inputbox'); ?>
		<?php echo $form->input('Attachment.file', array('type' => 'file', 'label' => __('File', true), 'size' => 130)); ?>
		<br />
		<?php echo $form->submit(__('Upload', true), array('class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>