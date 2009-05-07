<?php $this->pageTitle = sprintf(__('Edit article - %s', true), $this->data['Article']['title']); ?>
<h2><?php __('Edit article'); ?></h2>
<?php echo $form->create(null, array('url' => array('controller' => 'articles', 'action' => 'edit', $this->data['Article']['slug']), 'id' => 'article_form')); ?>
	<?php echo $html->div('article-inputbox'); ?>
		<?php echo $html->div('article-edit-actions'); ?>
			<?php
				echo $html->link
					(
						__('View', true),
						array
						(
							'controller' => 'articles',
							'action' => 'view',
							$this->data['Article']['slug']
						),
						array
						(
							'title' => sprintf(__('View %s', true), $this->data['Article']['title'])
						)
					);
				?>&nbsp;|&nbsp;<?php
				echo $html->link
					(
						__('Markdown Syntax', true),
						array
						(
							'controller' => 'neutrino',
							'action' => 'markdown'
						),
						array
						(
							'rel' => 'shadowbox;height=600;width=800',
							'title' => __('NeutrinoCMS help system', true)
						)
					);
			?>
		</div>
		<hr />
		<?php
		echo $form->input
			(
				'Article.article_category_id',
				array
				(
					'label' => __('Category', true),
					'options' => $categories
				)
			);

		echo $form->input('Article.title', array('label' => __('Title', true)));
		echo $form->input('Article.intro', array('type' => 'textarea', 'label' => 'Introduction'));
		echo $form->input('Article.content', array('onkeydown' => 'insertTab(event, this);', 'label' => __('Content', true)));

		echo $form->input
			(
				'Article.content_description',
				array
				(
					'label' => __('Meta content description', true),
					'type' => 'textarea',
					'rows' => 3
				)
			);

		echo $form->input
			(
				'Article.content_keywords',
				array
				(
					'label' => __('Meta content keywords (comma separated)', true),
					'type' => 'textarea',
					'rows' => 3
				)
			);

		echo $form->input
			(
				'Article.isdraft',
				array
				(
					'style' => 'display:inline; margin-top:15px;',
					'label' => array
					(
						'style' => 'display:inline; margin-bottom:15px; margin-left:5px;',
						'text' => __('Draft', true)
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
echo $javascript->codeBlock('Form.focusFirstElement("article_form");');
echo $javascript->codeBlock
	(
		sprintf
		(
			'document.observe
			(
				"dom:loaded",
				function()
				{
					var options = {
						loadingImage:	"%sshadowbox/loading.gif",
		        		keysClose:		["c", 27]
					};
					Shadowbox.init(options);
				}
			);',
			$this->webroot.IMAGES_URL
		)
	);
?>