<?php $this->pageTitle = 'Search results for '.$phrase; ?>
<?php
$count = count($results);
?>
<?php echo $html->div('post'); ?>
	<h2>Search results</h2>
	<small>
	Your search for "<?php echo $phrase; ?>" resulted in <?php echo $count; ?> article<?php echo ($count != 1 ? 's' : ''); ?>:
	</small>
	<?php echo $html->div('entry'); ?>
		<?php
		if (count($results))
		{
			foreach ($results as $article)
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
		}
		else
		{
			?>
			<h3 style="text-align:center;">No articles!</h3>
			<?php
			if ($auth->valid())
			{
				?>
				<div style="text-align:center;">
					<?php echo $html->link('Write one!', array('controller' => 'articles', 'action' => 'add', $phrase)); ?>
				</div>
				<?php
			}
			?>
			<?php
		}
		?>
	</div>
</div>