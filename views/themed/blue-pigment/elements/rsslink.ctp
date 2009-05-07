<div class="rss">
	<?php
	$img_tag = $html->image('rssfeed.gif', array('class' => 'rss-image'));
	echo $html->link($img_tag, '/articles.rss', array('title' => __('Articles RSS', true)), null, null, false);
	?>
</div>