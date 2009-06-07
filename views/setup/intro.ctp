<h1><?php __('Welcome'); ?></h1>
<p><?php __('Hello there and welcome to NeutrinoCMS installation!'); ?></p>

<p style="text-align:center;">
<?php
	echo $ajax->link(
			__('Read introduction', true),
			'#',
			array
			(
				'before' => 'Effect.BlindUp("neutrino-skip",{queue:{scope:"global", position:"end"}}); Effect.BlindDown("neutrino-intro",{queue:{scope:"global", position:"end"}}); return false;',
			)
		);
	echo ' &clubs ';
	echo $ajax->link(
			__('Proceed to installation', true),
			'#',
			array
			(
				'before' => 'Effect.BlindUp("neutrino-intro",{queue:{scope:"global", position:"end"}}); Effect.BlindDown("neutrino-skip",{queue:{scope:"global", position:"end"}}); return false;',
			)
		);
?>
</p>
<div id="neutrino-intro" style="display:none;">
	<p>
		<?php __('NeutrinoCMS is meant to be used primarily by software developers. Neutrino aims'); ?>
		<?php __('to make it easy for them to publish programming articles and code - while not'); ?>
		<?php __('getting in the way. Because of that, Neutrino is not a CMS for everyone.'); ?>
		<?php __('There are no WYSIWYG editors of any kind. Articles are written using Markdown'); ?>
		<?php __('syntax. Code syntax highlighting is done using google-code-prettify and it'); ?>
		<?php __('supports many different languages.'); ?>
	</p>
	<p>
		<?php __('Neutrino also features full ACL (Access Control List) implmentation which allows'); ?>
		<?php __('the administrator to define access rights to the finest detail.'); ?>
	</p>
	<p>
		<?php __('NeutrinoCMS is a personal project I started as a hobby,'); ?>
		<?php __('to get familiar with CakePHP. As this is my first CakePHP project,'); ?>
		<?php __('there will obviously be some bugs and illogical things in the code,'); ?>
		<?php __('feel free to report those to &lt;neutrinocms[at]gmail.com&gt;'); ?>
	</p>
	<p>
		<?php __('NeutrinoCMS is released under GPL licence.'); ?>
	</p>
</div>
<div id="neutrino-skip" style="display:none;">

		<?php echo $form->create(false, array('url' => $this->here)); ?>
		<?php echo $html->div('install-inputbox'); ?>
			<p><?php __('To proceed with NeutrinoCMS installation press next.'); ?></p>
			<?php echo $form->hidden('Installation.Step', array('value' => '0')); ?>
			<?php echo $form->submit(__('Next', true), array('class' => 'button')); ?>
		</div>
		<?php echo $form->end(); ?>

</div>