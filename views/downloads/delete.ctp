<?php
$this->pageTitle = sprintf(__('Delete download - %s', true), $download['Download']['name']);
echo $form->create
	(
		null,
		array
		(
			'url' =>
			array
			(
				'controller' => 'downloads',
				'action' => 'delete',
				$download['Download']['slug']
			)
		)
	);

echo $html->div('download-deletebox');
$text = sprintf
	(
		__('I am sure I want to delete the download %s', true),
		$html->link
		(
			$download['Download']['name'],
			array
			(
				'controller' => 'downloads',
				'action' => 'view',
				$download['Download']['slug']
			)
		)
	);

echo $form->input
	(
		'Download.delete',
		array
		(
			'type'	=> 'checkbox',
			'style' => 'display:inline; margin-top:15px;',
			'label' =>
			array
			(
				'style' => 'display:inline; margin-bottom:15px; margin-left:5px;',
				'text' => $text
			)
		)
	);

$buttonAttr = array('name' => 'data[Submit][type]', 'class' => 'button');
echo $form->submit(__('Delete', true), $buttonAttr);
echo $form->submit(__('Cancel', true), $buttonAttr);
?>
</div>
<?php echo $form->end(); ?>