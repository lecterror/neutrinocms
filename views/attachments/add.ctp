<?php $this->pageTitle = 'Add new file'; ?>
<h2>Add new file</h2>
<?php echo $form->create(null, array('url' => array('controller' => 'attachments', 'action' => 'add'), 'type' => 'file')); ?>
	<?php echo $html->div('upload-inputbox'); ?>
		<?php echo $form->input('Attachment.file', array('type' => 'file', 'label' => 'File', 'size' => 73)); ?>
		<?php echo $form->submit('Upload', array('class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>