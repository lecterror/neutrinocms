<?php echo $html->div('article'); ?>
	<h1><?php
		echo $html->link
			(
				$article['Article']['title'],
				array
				(
					'controller' => 'articles',
					'action' => 'view',
					$article['Article']['slug']
				),
				array
				(
					'title' => sprintf
						(
							'Permanent link to %s',
							$article['Article']['title']
						),
					'class' => 'article-title'
				)
			);

		$allowArticleEdit = $allowArticleDelete = false;

		if ($auth->isValid())
		{
			$allowArticleEdit = $auth->check('articles', 'edit', $article['Article']['user_id']);
			$allowArticleDelete = $auth->check('articles', 'delete', $article['Article']['user_id']);
		}

		if ($allowArticleEdit || $allowArticleDelete)
		{
			?>
			<span class="article-actions">
				[
				<?php
				$articleActions = array();

				if ($allowArticleEdit)
				{
					$articleActions[] = $html->link
						(
							__('Edit', true),
							array
							(
								'controller' => 'articles',
								'action' => 'edit',
								$article['Article']['slug']
							),
							array
							(
								'title' => sprintf(__('Edit %s', true), $article['Article']['title'])
							)
						);
				}

				if ($allowArticleDelete)
				{
					$articleActions[] = $html->link
						(
							__('Delete', true),
							array
							(
								'controller' => 'articles',
								'action' => 'delete',
								$article['Article']['slug']
							),
							array
							(
								'title' => sprintf(__('Delete ', true), $article['Article']['title'])
							)
						);
				}

				echo implode(' | ', $articleActions);
				?>
				]
			</span>
			<?php
		}
		?>
	</h1>

	<p class="article-header">
		<small class="date">
			<?php
			$categoryLink = $html->link
				(
					$article['ArticleCategory']['name'],
					array
					(
						'controller' => 'article_categories',
						'action' => 'view',
						$article['ArticleCategory']['slug']
					)
				);
			
			$publishingDate = date
				(
					Configure::read('Neutrino.DateDisplayFormat'),
					strtotime($article['Article']['created'])
				);

			if ($article['Article']['isdraft'])
			{
				echo sprintf(__('Draft in %s on %s', true), $categoryLink, $publishingDate);
			}
			else
			{
				echo sprintf(__('Posted in %s on %s', true), $categoryLink, $publishingDate);
			}
			?>
		</small>
	</p>
	<?php
	echo $html->div('entry');

		if (isset($show_intro) && $show_intro == true)
		{
			if (isset($small_intro) && $small_intro == true)
			{
				echo $html->div('intro-small', $article['Article']['intro']);
			}
			else
			{
				echo $html->div('intro', $article['Article']['intro']);
			}
		}

		if (isset($show_content) && $show_content == true)
		{
			$cacheKey = sprintf('article-markdown-%s', $article['Article']['id']);
			$content = Cache::read($cacheKey);

			if ($content == null)
			{
				 $content = $markdown->parse($article['Article']['content']);
				 Cache::write($cacheKey, $content);
			}

			if (isset($highlight_phrase))
			{
				echo $htmlText->highlight($content, $highlight_phrase, '<span class="search_highlight">\1</span>');
			}
			else
			{
			//	echo $mathPublisher->parse($markdown->parse($article['Article']['content']));
				echo $content;
			}
		}
		?>
	</div>

	<div class="article-footer">
		<?php
		if (!isset($show_content) || $show_content == false)
		{
			echo $html->link
				(
					__('Read more', true),
					array
					(
						'controller' => 'articles',
						'action' => 'view',
						$article['Article']['slug']
					),
					array
					(
						'class' => 'read-more'
					)
				);
		}
		else
		{
			$voted = false;
			$votedValue = 0;
			$totalVotes = 0;
			$totalRating = 0;

			if (isset($article['Rating']['Summary']))
			{
				$voted = $article['Rating']['Summary']['voted'];
				$votedValue = $article['Rating']['Summary']['rating'];
				$totalVotes = $article['Rating']['Summary']['totalVotes'];
				$totalRating = $article['Rating']['Summary']['totalRating'];
			}

			$url = $html->url
				(
					array
					(
						'controller' => 'articles',
						'action' => 'rate',
						$article['Article']['slug']
					)
				);

			echo $this->element
				(
					'rating',
					compact('article', 'voted', 'votedValue', 'totalVotes', 'totalRating', 'url')
				);
		}
		?>
		<span class="date">
		<?php
		$categoryLink = $html->link
			(
				$article['ArticleCategory']['name'],
				array
				(
					'controller' => 'article_categories',
					'action' => 'view',
					$article['ArticleCategory']['slug']
				)
			);
		
		$publishingDate = date
			(
				Configure::read('Neutrino.DateDisplayFormat'),
				strtotime($article['Article']['created'])
			);

		if ($article['Article']['isdraft'])
		{
			echo sprintf(__('Draft in %s', true), $categoryLink);
		}
		else
		{
			echo sprintf(__('Posted in %s', true), $categoryLink);
		}

		if ($article['Article']['updated'] != $article['Article']['created'])
		{
			echo ' &raquo; ';
			echo sprintf
				(
					__('Last updated: %s', true),
					date
					(
						Configure::read('Neutrino.DateDisplayFormat'),
						strtotime($article['Article']['updated'])
					)
				);
		}

		echo '</span><br />';
		echo sprintf('<span class="comments" id="comments-counter-%s">', $article['Article']['id']);

		$commentsCount = count($article['Comment']);

		if ($commentsCount > 0)
		{
			if (!isset($show_comments) || $show_comments == false)
			{
				echo $html->link
					(
						sprintf
						(
							 __n
							 (
							 	'%s comment',
							 	'%s comments',
							 	$commentsCount,
							 	true
							 ),
							 $commentsCount
						),
						array
						(
							'controller' => 'articles',
							'action' => 'view',
							$article['Article']['slug'].'#comments'
						)
					);
			}
			else
			{
				echo sprintf
					(
						 __n
						 (
						 	'%s comment',
						 	'%s comments',
						 	$commentsCount,
						 	true
						 ),
						 $commentsCount
					);
			}
		}
		else
		{
			__('No comments');
		}
		?>
		</span>
	</div>
	<?php
	if (isset($show_comments) && $show_comments == true)
	{
		?>
		<a name="comments"></a>
		<div id="comments-outer-wrap" class="comments">
			<?php echo $this->element('comments/index'); ?>
		</div>
		<?php
	}
	?>
</div>
