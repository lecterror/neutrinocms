<?php
// for some reason, this line is necessary when Cookie component is included,
// and a cookie exists in the request.. don't know why really, so here it is..
srand(time());
?>
<a href="javascript:void(0);" onclick="javascript:document.images.captcha.src='<?php echo $html->url(array('controller' => 'comments', 'action' => 'captcha')); ?>?' + Math.round(Math.random(0)*1000)+1">
<img id="captcha" src="<?php
	echo $html->url
		(
			array
			(
				'controller' => 'comments',
				'action' => 'captcha',
				strval(rand(100,999))
			)
		);
	?>" alt="<?php __('Captcha spam prevention image'); ?>" title="<?php __('Click to reload the image if it is unreadable!'); ?>" />
</a>