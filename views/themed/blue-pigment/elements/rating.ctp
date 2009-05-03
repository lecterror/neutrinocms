<?php
if (!isset($elementId) || empty($elementId))
{
	$elementId = 'ratings';
}
?>
<span id="<?php echo $elementId; ?>" class="float-left"></span>
<?php
echo $javascript->codeBlock('
	new Starbox(
		"'.$elementId.'",
		'.$totalRating.',
		{
			locked: '.($voted ? 'true' : 'false').',
			rated: '.($voted ? $votedValue : 'false').',
			total: '.$totalVotes.',
			indicator: "#{average} based on #{total} votes",
			identity: "'.$url.'",
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
			max: 5,
			overlay: "../../themed/blue-pigment/img/starbox/default.png"
		});
	');
?>