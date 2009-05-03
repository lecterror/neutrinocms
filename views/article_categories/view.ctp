<?php $this->pageTitle = 'Article category - '.$category['ArticleCategory']['name']; ?>
<?php echo $html->div('article-category'); ?>
	<h2><?php echo $html->link(
			$category['ArticleCategory']['name'], array('controller' => 'article_categories', 'action' => 'view', $category['ArticleCategory']['slug']),
			array(
				'title' => 'Permanent link to '.$category['ArticleCategory']['name'].' article category',
				'class' => 'article-category-title'
			)); ?>
		<?php
		if ($auth->valid())
		{
			?>
			<span class="article-category-actions">
				[
				<?php
				echo $html->link('Add', array('controller' => 'articles', 'action' => 'add', 'category' => $category['ArticleCategory']['slug']), array('title' => 'Add a new article'));
				echo ' | ';
				echo $html->link('Edit', array('controller' => 'article_categories', 'action' => 'edit', $category['ArticleCategory']['slug']), array('title' => 'Edit '.$category['ArticleCategory']['name']));
				echo ' | ';
				echo $html->link('Delete', array('controller' => 'article_categories', 'action' => 'delete', $category['ArticleCategory']['slug']), array('title' => 'Delete '.$category['ArticleCategory']['name']));
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
		<h3 style="text-align:center;">No articles!</h3>
		<?php
		if ($auth->valid())
			echo '<div style="text-align:center;">'.$html->link('Write one!', array('controller' => 'articles', 'action' => 'add', 'category' => $category['ArticleCategory']['slug'])).'</div>';
		?>
		<?php
	}
	?>
</div>