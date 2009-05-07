<?php
if (!isset($elementId) || empty($elementId))
{
	$elementId = 'ratings';
}
?>
<span id="<?php echo $elementId; ?>" class="float-left"></span>
<?php
echo $javascript->codeBlock
	(
		sprintf
		(
			'new Starbox
				(
					"%s",
					%s,
					{
						locked: %s,
						rated: %s,
						total: %s,
						indicator: "%s",
						identity: "%s",
						onRate: function (element, info)
							{
								new Ajax.Request
									(
										info.identity + "/" + info.rated,
										{
											method: "get",
											onSuccess: function(transport)
											{
												alert(transport.responseText);
											}
										}
									);
							},
						stars: 5,
						buttons: 5,
						max: 5
					}
				);
			',
			$elementId,
			$totalRating,
			($voted ? 'true' : 'false'),
			($voted ? $votedValue : 'false'),
			$totalVotes,
			__n
			(
				'#{average} based on #{total} vote',
				'#{average} based on #{total} votes',
				$totalVotes,
				true
			),
			$url
		)
	);
?>