<h1>
<?php echo $html->link('Article categories', array('controller' => 'article_categories'), array('title' => 'Permanent link to Article categories')); ?>
<?php
if ($auth->valid())
{
	?>
	<span style="font-size:x-small">
		[
		<?php
		echo $html->link('Add', array('controller' => 'article_categories', 'action' => 'add'), array('title' => 'Add a new category'));
		?>
		]
	</span>
	<?php
}
?>
</h1>

<table>
<?php
echo $html->tableHeaders(array('Name', 'Description'));

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
