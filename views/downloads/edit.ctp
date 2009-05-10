<?php $this->pageTitle = sprintf(__('Edit download - %s', true), $this->data['Download']['name']); ?>
<h2><?php __('Edit download'); ?></h2>
<?php echo $form->create(null, array('url' => array('controller' => 'downloads', 'action' => 'edit', $this->data['Download']['slug']), 'id' => 'download_form')); ?>
	<?php echo $html->div('download-inputbox'); ?>
		<div class="download-edit-actions">
			<?php
			if ($auth->check('downloads', 'view', $this->data['Download']['user_id']))
			{
				echo $html->link
					(
						__('View', true),
						array
						(
							'controller' => 'downloads',
							'action' => 'view',
							$this->data['Download']['slug']
						),
						array
						(
							'title' => sprintf
							(
								__('View %s', true),
								$this->data['Download']['name']
							)
						)
					);
			}
			?>
		</div>
		<hr />
		<?php
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
					'label' => __('Hitcount', true),
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
						'style' => 'display:inline; margin-bottom:15px; marign-left:5px;',
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
<?php
echo $form->end();
echo $javascript->codeBlock('Form.focusFirstElement("download_form");');
?>