<h2>Step 3: Site information</h2>
<?php echo $form->create(null, array('url' => array('controller' => 'setup', 'action' => 'install_step3'))); ?>
	<?php echo $html->div('install-inputbox'); ?>
		<?php echo $form->input('Configuration.SiteTitle', array('label' => 'Site title')); ?>
		<?php echo $form->input('Configuration.SiteDescription', array('label' => 'Site description/slogan..')); ?>
		<?php echo $form->input('Configuration.SiteKeywords', array('label' => 'Site keywords')); ?>
		<?php echo $form->input('Configuration.SiteCopyrightNotice', array('label' => 'Site copyright notice')); ?>
		<?php echo $form->input('Configuration.CaptchaSidenote', array('label' => 'Captcha additional message (can be empty)')); ?>
		<div class="input"><label for="ConfigurationTheme">Theme</label>
		<?php
		echo $form->select
			(
				'Configuration.Theme',
				$themes,
				(isset($defaultTheme) ? $defaultTheme : null),
				array(),
				false
			);
		?>
		</div>
		<?php echo $form->input('Configuration.GoogleWebmasterToolsVerificationCode', array('label' => 'Google Webmaster tools verification code<sup>[1]</sup><sup>[2]</sup>')); ?>
		<?php echo $form->input('Configuration.GoogleAnalyticsAccountCode', array('label' => 'Google Analytics code<sup>[1]</sup>')); ?>
		<br />
		<?php echo $form->submit('Next', array('class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>
<div>
<p>
	[1] If you don't have an account just leave the field empty<br />
	[2] Enter only the content, not the whole meta-tag
</p>
</div>