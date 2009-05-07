<?php $this->pageTitle = __('Download not found', true); ?>
<?php
/*
if ($similar)
{
	$count = count($similar);
	?>
	<p>We have found <?php echo $count; ?> similar article<?php echo ($count > 1 ? 's' : ''); ?>:</p>
	<p>
		<?php
			foreach ($similar as $article)
			{
				echo $this->element(
					'article',
						array(
							'article'		=> $article,
							'show_intro'	=> true,
							'show_content'	=> false
						)
					);
			}
		?>
	</p>
	<?php
}
*/
?>
<?php
if ($auth->valid() && isset($slug))
{
	$createLink = $html->link
		(
			__('Create it!', true),
			array
			(
				'controller' => 'downloads',
				'action' => 'add',
				$slug
			)
		);

	echo sprintf('<div style="text-align:center;">%s</div>', $createLink);
}
?>