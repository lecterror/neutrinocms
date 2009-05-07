<?php $this->pageTitle = __('Article not found', true); ?>
<?php
if ($similar)
{
	$count = count($similar);
	echo $html->para
		(
			null,
			sprintf
			(
				__n
				(
					'We have found %d similar article:',
					'We have found %d similar articles:',
					$count,
					true
				),
				$count
			)
		);
	?>
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

if ($auth->valid() && isset($slug))
{
	echo sprintf
		(
			'<div style="text-align:center;">%s</div>',
			$html->link
			(
				__('Write it!', true),
				array
				(
					'controller' => 'articles',
					'action' => 'add',
					$slug
				)
			)
		);
}
?>