<?php
$articles = $this->requestAction
	(
		array
		(
			'controller' => 'articles',
			'action' => 'highestRated'
		),
		array
		(
			'limit' => $limit
		)
	);
?>
<?php if (!empty($articles)): ?>
	<ul>
	<?php foreach ($articles as $article): ?>
		<li>
			<?php
			echo $html->link
				(
					$article['Article']['title'],
					array
					(
						'controller' => 'articles',
						'action' => 'view',
						$article['Article']['slug']
					)
				);
			?>
		</li>
	<?php endforeach; ?>
	</ul>
<?php else: ?>
	<ul>
		<li><a href="#" onclick="javascript:return false;"><?php __('No rated articles'); ?></a></li>
	</ul>
<?php endif; ?>