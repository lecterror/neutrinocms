<div class="rss">
	<?php
	$img_tag = $html->image('rss.png', array('class' => 'rss-image'));
	echo $html->link($img_tag, '/articles.rss', array('title' => 'Articles RSS'), null, null, false);
	?>
</div>