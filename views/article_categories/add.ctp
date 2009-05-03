<?php $this->pageTitle = 'Add new article category'; ?>
<h2>Add new article category</h2>
<?php echo $form->create('ArticleCategory', array('url' => array('controller' => 'article_categories', 'action' => 'add'), 'id' => 'category_form')); ?>
	<?php echo $html->div('article-category-inputbox'); ?>
		<?php echo $form->input('ArticleCategory.name', array('label' => 'Name')); ?>
		<?php echo $form->input('ArticleCategory.description', array('type' => 'textarea', 'label' => 'Description')); ?>
		<?php echo $form->submit('Save', array('class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>
<?php echo $javascript->codeBlock('Form.focusFirstElement("category_form");'); ?>