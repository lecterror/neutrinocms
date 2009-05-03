<?php $this->pageTitle = 'Delete article - '.$article['Article']['title']; ?>
<?php echo $form->create(null, array('url' => array('controller' => 'articles', 'action' => 'delete', $article['Article']['slug']))); ?>
	<?php echo $html->div('article-deletebox'); ?>
		<?php
		$text =
			' I am sure I want to delete the article '.
			$html->link($article['Article']['title'], array('controller' => 'articles', 'action' => 'view', $article['Article']['slug']));

		echo $form->input('Article.delete', array(
			'type'	=> 'checkbox',
			'label' => array('style' => 'display:inline; margin-bottom:15px;', 'text' => $text),
			'style' => 'display:inline; margin-top:15px;')); ?>

		<?php echo $form->submit('Delete', array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
		<?php echo $form->submit('Cancel', array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>