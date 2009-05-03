<?php
foreach ($articles as $article)
{
	echo $this->element
	(
		'article',
		array
		(
			'article'			=> $article,
			'show_intro'		=> true,
			'show_content'		=> false,
			'comments_count'	=> count($article['Comment'])
		)
	);
}
?>