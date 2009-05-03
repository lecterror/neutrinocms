<?php $this->pageTitle = 'Add new download'; ?>
<h2>Add new download</h2>
<?php echo $form->create(null, array('url' => array('controller' => 'downloads', 'action' => 'add'), 'type' => 'file')); ?>
	<?php echo $html->div('download-inputbox'); ?>
		<?php
		echo $form->input
			(
				'Download.download_category_id',
				array
				(
					'label' => 'Category',
					'options' => $categories
				)
			);
		?>
		<?php echo $form->input('Download.slug', array('label' => 'Slug')); ?>
		<?php echo $form->input('Download.name', array('label' => 'Download name')); ?>
		<?php echo $form->input('Download.display_file_name', array('label' => 'File name')); ?>
		<?php echo $form->input('Download.description', array('label' => 'Description')); ?>
		<?php
		echo $form->input
			(
				'Download.content_description',
				array
				(
					'label' => 'Meta content description',
					'type' => 'textarea',
					'rows' => 3
				)
			);

		echo $form->input
			(
				'Download.content_keywords',
				array
				(
					'label' => 'Meta content keywords (comma separated)',
					'type' => 'textarea',
					'rows' => 3
				)
			);

		echo $form->input
			(
				'Download.real_file_name',
				array
				(
					'label' => 'File to download',
					'options' => $attachments
				)
			);
		?>
		<?php echo $form->input('Download.downloaded', array('label' => 'Initial hitcount', 'autocomplete' => 'off')); ?>
		<?php
		echo $form->input('Download.published', array(
				'label' => array('style' => 'display:inline; margin-bottom:15px;', 'text' => ' Published'),
				'style' => 'display:inline; margin-top:15px;'));
		?>
		<?php
		if (isset($disable) && $disable == true)
		{
			echo $form->submit('Save and continue editing', array('name' => 'data[Submit][type]', 'class' => 'button', 'disabled' => 'disabled'));
			echo $form->submit('Save', array('name' => 'data[Submit][type]', 'class' => 'button', 'disabled' => 'disabled'));
		}
		else
		{
			echo $form->submit('Save and continue editing', array('name' => 'data[Submit][type]', 'class' => 'button'));
			echo $form->submit('Save', array('name' => 'data[Submit][type]', 'class' => 'button'));
		}
		?>
	</div>
<?php echo $form->end(); ?>