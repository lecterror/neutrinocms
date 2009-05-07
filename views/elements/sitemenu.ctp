<?php
if (isset($article_menu_items) && (count($article_menu_items) > 0))
{
	?>
	<h1><?php __('Articles'); ?></h1>
	<ul class="sitemenu">
	<?php
	foreach ($article_menu_items as $item)
	{
		echo '<li>'.$html->link(
				$item['ArticleCategory']['name'],
				array
				(
					'controller' => 'article_categories',
					'action' => 'view',
					$item['ArticleCategory']['slug']
				)
			).'</li>';
	}
	?>
	</ul>
	<?php
}
?>