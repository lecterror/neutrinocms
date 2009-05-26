<?php
$this->pageTitle = $article['Article']['title'];
$this->viewVars['content_description_for_layout'] = $article['Article']['content_description'];
$this->viewVars['content_keywords_for_layout'] = $article['Article']['content_keywords'];

echo $this->element
	(
		'article',
		array
		(
			'article'			=> $article,
			'show_intro'		=> true,
			'small_intro'		=> true,
			'show_content'		=> true,
			'show_comments'		=> true
		)
	);

echo $javascript->codeBlock
	(
		sprintf
		(
			'document.observe
			(
				"dom:loaded",
				function()
				{
					var options = {
						loadingImage:	"%sshadowbox/loading.gif",
		        		keysClose:		["c", 27]
					};
					Shadowbox.init(options);
				}
			);',
			$this->webroot.IMAGES_URL
		)
	);
?>
