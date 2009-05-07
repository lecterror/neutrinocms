<?php $this->pageTitle = __('Add new download', true); ?>
<h2><?php __('Add new download'); ?></h2>
<?php echo $form->create(null, array('url' => array('controller' => 'downloads', 'action' => 'add'), 'type' => 'file')); ?>
	<?php
	echo $html->div('download-inputbox');
		echo $form->input('Download.download_category_id', array('label' => __('Category', true)));
		echo $form->input('Download.slug', array('label' => __('Slug', true)));
		echo $form->input('Download.name', array('label' => __('Download name', true)));
		echo $form->input('Download.display_file_name', array('label' => __('File name', true)));
		echo $form->input('Download.description', array('label' => __('Description', true)));

		echo $form->input
			(
				'Download.content_description',
				array
				(
					'label' => __('Meta content description', true),
					'type' => 'textarea',
					'rows' => 3
				)
			);

		echo $form->input
			(
				'Download.content_keywords',
				array
				(
					'label' => __('Meta content keywords (comma separated)', true),
					'type' => 'textarea',
					'rows' => 3
				)
			);

		echo $form->input
			(
				'Download.real_file_name',
				array
				(
					'label' => __('File to download', true),
					'options' => $attachments
				)
			);

		echo $form->input
			(
				'Download.downloaded',
				array
				(
					'label' => __('Initial hitcount', true),
					'autocomplete' => 'off'
				)
			);

		echo $form->input
			(
				'Download.published',
				array
				(
					'style' => 'display:inline; margin-top:15px;',
					'label' =>
					array
					(
						'style' => 'display:inline; margin-bottom:15px; margin-left:5px;',
						'text' => __('Published', true)
					)
				)
			);

		$buttonAttr = array('name' => 'data[Submit][type]', 'class' => 'button');

		if (isset($disable) && $disable == true)
		{
			$buttonAttr = array_merge($buttonAttr, array('disabled' => 'disabled'));
		}

		echo $form->submit(__('Save and continue editing', true), $buttonAttr);
		echo $form->submit(__('Save', true), $buttonAttr);
		?>
	</div>
<?php echo $form->end(); ?>