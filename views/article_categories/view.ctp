<?php $this->pageTitle = sprintf(__('Article category - %s', true), $category['ArticleCategory']['name']); ?>
<?php echo $html->div('article-category'); ?>
	<h2><?php echo $html->link(
			$category['ArticleCategory']['name'], array('controller' => 'article_categories', 'action' => 'view', $category['ArticleCategory']['slug']),
			array(
				'title' => sprintf(__('Permanent link to %s article category', true), $category['ArticleCategory']['name']),
				'class' => 'article-category-title'
			)); ?>
		<?php
		if ($auth->valid())
		{
			?>
			<span class="article-category-actions">
				[
				<?php
				echo $html->link
					(
						__('Add', true),
						array
						(
							'controller' => 'articles',
							'action' => 'add',
							'category' => $category['ArticleCategory']['slug']
						),
						array
						(
							'title' => __('Add a new article', true)
						)
					);
				echo ' | ';
				echo $html->link
					(
						__('Edit', true),
						array
						(
							'controller' => 'article_categories',
							'action' => 'edit',
							$category['ArticleCategory']['slug']
						),
						array
						(
							'title' => sprintf(__('Edit %s', true), $category['ArticleCategory']['name'])
						)
					);
				echo ' | ';
				echo $html->link
					(
						__('Delete', true),
						array
						(
							'controller' => 'article_categories',
							'action' => 'delete',
							$category['ArticleCategory']['slug']
						),
						array
						(
							'title' => sprintf(__('Delete %s', true), $category['ArticleCategory']['name'])
						)
					);
				?>
				]
			</span>
			<?php
		}
		?>
	</h2>
	<p class="article-category-header">
		<small class="article-category-description"><?php echo $category['ArticleCategory']['description'] ?></small>
	</p>

	<?php
	if (count($articles))
	{
		foreach ($articles as $article)
		{
			echo $this->element(
				'article',
					array(
						'article'			=> $article,
						'show_intro'		=> true,
						'show_content'		=> false,
						'show_comments'		=> false,
						'comments_count'	=> count($article['Comment'])
					)
				);
		}
	}
	else
	{
		?>
		<h3 style="text-align:center;"><?php __('No articles!'); ?></h3>
		<?php
		if ($auth->valid())
			echo '<div style="text-align:center;">'.$html->link(__('Write one!', true), array('controller' => 'articles', 'action' => 'add', 'category' => $category['ArticleCategory']['slug'])).'</div>';
		?>
		<?php
	}
	?>
</div>