<?php $this->pageTitle = sprintf(__('Delete article - %s', true), $article['Article']['title']); ?>
<?php echo $form->create(null, array('url' => array('controller' => 'articles', 'action' => 'delete', $article['Article']['slug']))); ?>
	<?php echo $html->div('article-deletebox'); ?>
		<?php
		$text = sprintf
			(
				__('I am sure I want to delete the article %s', true),
				$html->link
				(
					$article['Article']['title'],
					array
					(
						'controller' => 'articles',
						'action' => 'view',
						$article['Article']['slug']
					)
				)
			);

		echo $form->input
			(
				'Article.delete',
				array
				(
					'type'	=> 'checkbox',
					'style' => 'display:inline; margin-top:15px;',
					'label' => array
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