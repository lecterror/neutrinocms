<?php
echo $html->link
	(
		$html->image
		(
			'cake.power.gif',
			array
			(
				'alt'		=> __("CakePHP: the rapid development php framework", true),
				'style'	=> 'border:0px'
			)
		),
		'http://www.cakephp.org/',
		array(),
		null,
		false
	);
?>