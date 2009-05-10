<?php
$this->pageTitle = __('Manage files', true);
?>
<h2 class="manage-files-title"><?php __('Manage files'); ?>
<?php
if ($auth->check('attachments', 'add'))
{
	?>
	<span class="manage-files-actions">
	[
	<?php echo $html->link(__('Add new file', true), array('controller' => 'attachments', 'action' => 'add')); ?>
	]
	</span>
<?php
}
?>
</h2>
<?php
$deleteAllowed = ($auth->check('attachments', 'delete'));

if ($deleteAllowed)
{
	echo $form->create(null, array('url' => array('controller' => 'attachments', 'action' => 'delete')));
}

echo $html->div('file-deletebox');

	if (!empty($files))
	{
		?>
		<table id="files-table">
		<?php
		if ($deleteAllowed)
		{
			echo $html->tableHeaders(array('', __('File name', true)));
		}
		else
		{
			echo $html->tableHeaders(array(__('File name', true)));
		}
		?>
		<?php
		foreach ($files as $file)
		{
			if ($deleteAllowed)
			{
				echo $html->tableCells
					(
						array($form->checkbox(Inflector::slug($file->name), array('value' => $file->name, 'multiple' => 'checkbox')), $file->name),
						array('class' => 'row-a'),
						array('class' => 'row-b')
					);
			}
			else
			{
				echo $html->tableCells
					(
						$file->name,
						array('class' => 'row-a'),
						array('class' => 'row-b')
					);
			}
		}
		?>
		</table>
		<?php
		if ($deleteAllowed)
		{
			echo $form->submit(__('Delete selected files', true), array('class' => 'button'));
		}
	} // if (!empty($files))
	else
	{
		?>
		<div><?php __('No files!'); ?></div>
		<?php
	}
	?>
</div>
<?php
if ($deleteAllowed)
{
	echo $form->end();
}
?>