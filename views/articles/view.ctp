<?php $this->pageTitle = $article['Article']['title']; ?>
<?php
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
?>
<?php
echo $javascript->codeBlock('
	document.observe("dom:loaded",
		function()
		{
			var options = {
				loadingImage:	"'.$this->webroot.IMAGES_URL.'shadowbox/loading.gif",
        		keysClose:		["c", 27]
			};
			Shadowbox.init(options);
		}
	);'); ?>