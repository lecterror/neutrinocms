<?php $this->pageTitle = __('Add new article category', true); ?>
<h2><?php __('Add new article category'); ?></h2>
<?php echo $form->create('ArticleCategory', array('url' => array('controller' => 'article_categories', 'action' => 'add'), 'id' => 'category_form')); ?>
	<?php echo $html->div('article-category-inputbox'); ?>
		<?php echo $form->input('ArticleCategory.name', array('label' => __('Name', true))); ?>
		<?php echo $form->input('ArticleCategory.description', array('type' => 'textarea', 'label' => __('Description', true))); ?>
		<?php echo $form->submit(__('Save', true), array('class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>
<?php echo $javascript->codeBlock('Form.focusFirstElement("category_form");'); ?>