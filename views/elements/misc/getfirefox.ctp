<?php
echo $html->link
	(
		$html->image
		(
			'firefox_80x15.png',
			array
			(
				'style'		=> 'border:0px; width:80px; height:15px;',
				'alt'		=> __('Get Firefox', true)
			)
		),
		'http://getfirefox.com/',
		array('title' => __('Get Firefox - The Browser, Reloaded.', true)),
		null,
		false
	);
?>