<?php $this->pageTitle = sprintf(__('Delete article category - %s', true), $category['ArticleCategory']['name']); ?>
<?php echo $form->create(null, array('url' => array('controller' => 'article_categories', 'action' => 'delete', $category['ArticleCategory']['slug']))); ?>
	<?php echo $html->div('article-category-deletebox'); ?>
		<span class="warning-message"><?php __('Warning:'); ?></span> <?php __('deleting a category will delete all related articles!'); ?>
		<?php
		$text = sprintf
			(
				__('I am sure I want to delete the article category %s', true),
				$html->link
				(
					$category['ArticleCategory']['name'],
					array
					(
						'controller' => 'article_categories',
						'action' => 'view',
						$category['ArticleCategory']['slug']
					)
				)
			);

		echo $form->input
			(
				'ArticleCategory.delete',
				array
				(
					'type'	=> 'checkbox',
					'label' => array
					(
						'style' => 'display:inline; margin-bottom:15px; margin-left:5px;',
						'text' => $text
					),
					'style' => 'display:inline; margin-top:15px;'
				)
			);
		?>
		<?php echo $form->submit(__('Delete', true), array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
		<?php echo $form->submit(__('Cancel', true), array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>