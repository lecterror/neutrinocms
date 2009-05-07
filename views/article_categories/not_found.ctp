<?php $this->pageTitle = __('Article category not found', true); ?>
<?php
if ($similar)
{
	$count = count($similar);
	echo $html->para
		(
			null,
			sprintf
			(
				__n
				(
					'We have found %s similar category:',
					'We have found %s similar categories:',
					$count,
					true
				),
				$count
			)
		);
	?>
	<p>
		<table style="width:90%; margin:auto;">
		<?php
		echo $html->tableHeaders(array(__('Name', true), __('Description', true)));

		foreach ($similar as $category)
		{
			?>
			<tr>
				<td style="padding: 0px 10px 10px 0px; text-align:left;">
				<?php
				echo $html->link
					(
						$category['ArticleCategory']['name'],
						array
						(
							'controller' => 'article_categories',
							'action' => 'view',
							$category['ArticleCategory']['slug']
						)
					);
				?>
				</td>
				<td>
				<?php
				echo $category['ArticleCategory']['description'];
				?>
			</tr>
			<?php
		}
		?>
		</table>
	</p>
	<?php
}

if ($auth->valid() && isset($slug))
{
	echo sprintf
		(
			'<div style="text-align:center;">%s</div>',
			$html->link
			(
				sprintf(__('Create "%s"!', true), $slug),
				array
				(
					'controller' => 'article_categories',
					'action' => 'add',
					$slug
				)
			)
		);
}
?>