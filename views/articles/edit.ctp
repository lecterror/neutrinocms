<?php $this->pageTitle = 'Edit article - '.$this->data['Article']['title']; ?>
<h2>Edit article</h2>
<?php echo $form->create(null, array('url' => array('controller' => 'articles', 'action' => 'edit', $this->data['Article']['slug']), 'id' => 'article_form')); ?>
	<?php echo $html->div('article-inputbox'); ?>
		<?php echo $html->div('article-edit-actions'); ?>
			<?php
				echo $html->link
					(
						'View',
						array
						(
							'controller' => 'articles',
							'action' => 'view',
							$this->data['Article']['slug']
						),
						array
						(
							'title' => 'View '.$this->data['Article']['title']
						)
					);
				?>&nbsp;|&nbsp;<?php
				echo $html->link
					(
						'Markdown Syntax',
						array
						(
							'controller' => 'neutrino',
							'action' => 'markdown'
						),
						array
						(
							'rel' => 'shadowbox;height=600;width=800',
							'title' => 'NeutrinoCMS help system'
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
					'label' => 'Category',
					'options' => $categories
				)
			);

		echo $form->input('Article.title', array('label' => 'Title'));

		echo $form->input('Article.intro', array('type' => 'textarea', 'label' => 'Introduction'));
		echo $form->input('Article.content', array('onkeydown' => 'insertTab(event, this);'));

		echo $form->input
			(
				'Article.content_description',
				array
				(
					'label' => 'Meta content description',
					'type' => 'textarea',
					'rows' => 3
				)
			);

		echo $form->input
			(
				'Article.content_keywords',
				array
				(
					'label' => 'Meta content keywords (comma separated)',
					'type' => 'textarea',
					'rows' => 3
				)
			);

		echo $form->input('Article.isdraft', array(
			'label' => array('style' => 'display:inline; margin-bottom:15px;', 'text' => ' Is draft'),
			'style' => 'display:inline; margin-top:15px;'));

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
<?php echo $javascript->codeBlock('Form.focusFirstElement("article_form");'); ?>
<?php
echo $javascript->codeBlock('
	document.observe("dom:loaded",
		function()
		{
			var options = {
				loadingImage:	"'.$this->webroot.IMAGES_URL.'shadowbox/loading.gif",
        		keysClose:		["c", 27]
			};
			Shadowbox.init(options);
		}
	);'); ?>