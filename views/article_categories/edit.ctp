<?php $this->pageTitle = 'Edit article category - '.$this->data['ArticleCategory']['name']; ?>
<h2>Edit article category</h2>
<?php echo $form->create('ArticleCategory', array('url' => array('controller' => 'article_categories', 'action' => 'edit', $this->data['ArticleCategory']['slug']), 'id' => 'category_form')); ?>
	<?php echo $html->div('article-category-inputbox'); ?>
		<?php echo $form->input('ArticleCategory.name', array('label' => 'Name')); ?>
		<?php echo $form->input('ArticleCategory.description', array('type' => 'textarea', 'label' => 'Description')); ?>
		<?php echo $form->submit('Save and continue editing', array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
		<?php echo $form->submit('Save', array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>
<?php echo $javascript->codeBlock('Form.focusFirstElement("category_form");'); ?>