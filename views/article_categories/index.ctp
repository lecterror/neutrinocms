<h1>
<?php echo $html->link(__('Article categories', true), array('controller' => 'article_categories'), array('title' => __('Permanent link to Article categories', true))); ?>
<?php
if ($auth->check('article_categories', 'add'))
{
	?>
	<span style="font-size:x-small">
		[
		<?php
		echo $html->link(__('Add', true), array('controller' => 'article_categories', 'action' => 'add'), array('title' => __('Add a new category', true)));
		?>
		]
	</span>
	<?php
}
?>
</h1>

<table>
<?php
echo $html->tableHeaders(array(__('Name', true), __('Description', true)));

foreach ($categories as $category)
{
	?><tr><td style="padding: 0px 10px 10px 0px; text-align:left;"><?php
	echo $html->link($category['ArticleCategory']['name'], array('controller' => 'article_categories', 'action' => 'view', $category['ArticleCategory']['slug']));
	?></td><td><?php
	echo $category['ArticleCategory']['description'];
	?></tr><?php
}
?>
</table>
