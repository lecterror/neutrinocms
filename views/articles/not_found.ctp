<?php $this->pageTitle = 'Article not found'; ?>
<?php
if ($similar)
{
	$count = count($similar);
	?>
	<p>We have found <?php echo $count; ?> similar article<?php echo ($count > 1 ? 's' : ''); ?>:</p>
	<p>
		<?php
			foreach ($similar as $article)
			{
				echo $this->element(
					'article',
						array(
							'article'			=> $article,
							'show_intro'		=> true,
							'show_content'		=> false,
							'comments_count'	=> count($article['Comment'])
						)
					);
			}
		?>
	</p>
	<?php
}
?>
<?php
if ($auth->valid() && isset($slug))
{
	echo '<div style="text-align:center;">'.$html->link('Write it!', array('controller' => 'articles', 'action' => 'add', $slug)).'</div>';
}
?>