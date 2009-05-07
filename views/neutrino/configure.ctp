<h2><?php __('Site configuration'); ?></h2>
<?php echo $form->create(null, array('url' => array('controller' => 'neutrino', 'action' => 'configure'))); ?>
	<?php echo $html->div('configure-inputbox'); ?>
		<?php echo $form->input('Configuration.SiteTitle', array('label' => __('Site title', true))); ?>
		<?php echo $form->input('Configuration.SiteDescription', array('label' => __('Site description/slogan..', true))); ?>
		<?php echo $form->input('Configuration.SiteKeywords', array('label' => __('Site keywords', true))); ?>
		<?php echo $form->input('Configuration.SiteCopyrightNotice', array('label' => __('Site copyright notice', true))); ?>
		<?php echo $form->input('Configuration.CaptchaSidenote', array('label' => __('Captcha message (can be empty)', true))); ?>
		<div class="input"><label for="ConfigurationTheme"><?php __('Theme'); ?></label>
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
		<?php echo $form->input('Configuration.GoogleWebmasterToolsVerificationCode', array('label' => __('Google Webmaster tools verification code<sup>[1]</sup><sup>[2]</sup>', true))); ?>
		<?php echo $form->input('Configuration.GoogleAnalyticsAccountCode', array('label' => __('Google Analytics code<sup>[1]</sup>', true))); ?>
		<br />
		<?php echo $form->submit(__('Save', true), array('class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>
<div>
<p>
	<?php __('[1] If you don\'t have an account just leave the field empty'); ?>
	<br />
	<?php __('[2] Enter only the content, not the whole meta-tag'); ?>
</p>
</div>