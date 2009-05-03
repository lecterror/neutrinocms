<?php $this->pageTitle = 'Delete article category - '.$category['ArticleCategory']['name']; ?>
<?php echo $form->create(null, array('url' => array('controller' => 'article_categories', 'action' => 'delete', $category['ArticleCategory']['slug']))); ?>
	<?php echo $html->div('article-category-deletebox'); ?>
		<span class="warning-message">Warning:</span> deleting a category will delete all related articles!</p>
		<?php
		$text =
			' I am sure I want to delete the article category '.
			$html->link($category['ArticleCategory']['name'], array('controller' => 'article_categories', 'action' => 'view', $category['ArticleCategory']['slug']));

		echo $form->input('ArticleCategory.delete', array(
			'type'	=> 'checkbox',
			'label' => array('style' => 'display:inline; margin-bottom:15px;', 'text' => $text),
			'style' => 'display:inline; margin-top:15px;')); ?>

		<?php echo $form->submit('Delete', array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
		<?php echo $form->submit('Cancel', array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>