<?php
/*
	Received params:
		$wizard_title	= title of procedure (install, update..)
		$wizard_done	= finished steps
		$wizard_current	= current step
		$wizard_pending	= to-do steps
*/
?>
<h1 id="wizard-title"><?php echo $wizard_title; ?></h1>
<ul class="sitemenu">
<?php
foreach ($wizard_done as $step)
	echo '<li class="wizard-done">'.$step.'</li>';

echo '<li class="wizard-current">'.$wizard_current.'</li>';

foreach ($wizard_pending as $step)
	echo '<li class="wizard-pending">'.$step.'</li>';

?>
</ul>