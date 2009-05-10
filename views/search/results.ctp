<?php
$this->pageTitle = sprintf(__('Search results for %s', true), $phrase);
$count = count($results);
?>
<?php echo $html->div('post'); ?>
	<h2><?php __('Search results'); ?></h2>
	<small>
	<?php
	echo sprintf
		(
			__n
			(
				'Your search for %s resulted in %d article:',
				'Your search for %s resulted in %d articles:',
				$count,
				true
			),
			$phrase,
			$count
		);
	?>
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
			<h3 style="text-align:center;"><?php __('No articles!'); ?></h3>
			<?php
			if ($auth->isValid())
			{
				?>
				<div style="text-align:center;">
					<?php
					echo $html->link
						(
							__('Write one!', true),
							array
							(
								'controller' => 'articles',
								'action' => 'add',
								$phrase
							)
						);
					?>
				</div>
				<?php
			}
		}
		?>
	</div>
</div>