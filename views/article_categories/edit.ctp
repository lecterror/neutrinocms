<?php $this->pageTitle = sprintf(__('Edit article category - %s', true), $this->data['ArticleCategory']['name']); ?>
<h2>Edit article category</h2>
<?php echo $form->create('ArticleCategory', array('url' => array('controller' => 'article_categories', 'action' => 'edit', $this->data['ArticleCategory']['slug']), 'id' => 'category_form')); ?>
	<?php echo $html->div('article-category-inputbox'); ?>
		<?php echo $form->input('ArticleCategory.name', array('label' => __('Name', true))); ?>
		<?php echo $form->input('ArticleCategory.description', array('type' => 'textarea', 'label' => __('Description', true))); ?>
		<?php echo $form->submit(__('Save and continue editing', true), array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
		<?php echo $form->submit(__('Save', true), array('name' => 'data[Submit][type]', 'class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>
<?php echo $javascript->codeBlock('Form.focusFirstElement("category_form");'); ?>