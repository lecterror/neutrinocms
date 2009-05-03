<?php $this->pageTitle = 'Manage files'; ?>
<h2 class="manage-files-title">Manage files <span class="manage-files-actions">
	[
	<?php echo $html->link('Add new file', array('controller' => 'attachments', 'action' => 'add')); ?>
	]
</span></h2>
<?php echo $form->create(null, array('url' => array('controller' => 'attachments', 'action' => 'delete'))); ?>
	<?php echo $html->div('file-deletebox'); ?>
	<?php
	if (!empty($files))
	{
		?>
		<table id="files-table">
		<?php echo $html->tableHeaders(array('', 'File name')); ?>
		<?php
		foreach ($files as $file)
		{
			echo $html->tableCells(
				array($form->checkbox(Inflector::slug($file->name), array('value' => $file->name, 'multiple' => 'checkbox')), $file->name),
				array('class' => 'row-a'),
				array('class' => 'row-b')
				);
		}
		?>
		</table>
		<?php echo $form->submit('Delete selected files', array('class' => 'button')); ?>
		<?php
	} // if (!empty($files))
	else
	{
		?>
		<div>No files!</div>
		<?php
	}
	?>
	</div>
<?php echo $form->end(); ?>