<?php $this->pageTitle = 'Article category not found'; ?>
<?php
if ($similar)
{
	$count = count($similar);
	?>
	<p>We have found <?php echo $count; ?> similar categor<?php echo ($count > 1 ? 'ies' : 'y'); ?>:</p>
	<p>
		<table style="width:90%; margin:auto;">
		<?php
		echo $html->tableHeaders(array('Name', 'Description'));

		foreach ($similar as $category)
		{
			?><tr><td style="padding: 0px 10px 10px 0px; text-align:left;"><?php
			echo $html->link($category['ArticleCategory']['name'], array('controller' => 'article_categories', 'action' => 'view', $category['ArticleCategory']['slug']));
			?></td><td><?php
			echo $category['ArticleCategory']['description'];
			?></tr><?php
		}
		?>
		</table>
	</p>
	<?php
}
?>
<?php
if ($auth->valid() && isset($slug))
{
	echo '<div style="text-align:center;">';
	echo $html->link('Create "'.$slug.'"!', array('controller' => 'article_categories', 'action' => 'add', $slug));
	echo '</div>';
}
?>