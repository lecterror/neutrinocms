<?php $this->pageTitle = __('Manage files', true); ?>
<h2 class="manage-files-title"><?php __('Manage files'); ?>
	<span class="manage-files-actions">
	[
	<?php echo $html->link(__('Add new file', true), array('controller' => 'attachments', 'action' => 'add')); ?>
	]
	</span>
</h2>
<?php echo $form->create(null, array('url' => array('controller' => 'attachments', 'action' => 'delete'))); ?>
	<?php echo $html->div('file-deletebox'); ?>
	<?php
	if (!empty($files))
	{
		?>
		<table id="files-table">
		<?php echo $html->tableHeaders(array('', __('File name', true))); ?>
		<?php
		foreach ($files as $file)
		{
			echo $html->tableCells
				(
					array($form->checkbox(Inflector::slug($file->name), array('value' => $file->name, 'multiple' => 'checkbox')), $file->name),
					array('class' => 'row-a'),
					array('class' => 'row-b')
				);
		}
		?>
		</table>
		<?php echo $form->submit(__('Delete selected files', true), array('class' => 'button')); ?>
		<?php
	} // if (!empty($files))
	else
	{
		?>
		<div><?php __('No files!'); ?></div>
		<?php
	}
	?>
	</div>
<?php echo $form->end(); ?>