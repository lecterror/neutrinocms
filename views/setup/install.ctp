<h1>Welcome</h1>
<p>Hello there and welcome to NeutrinoCMS installation!</p>

<p style="text-align:center;">
<?php
	echo $ajax->link(
			'Read introduction',
			'#',
			array
			(
				'before' => 'Effect.BlindUp("neutrino-skip",{queue:{scope:"global", position:"end"}}); Effect.BlindDown("neutrino-intro",{queue:{scope:"global", position:"end"}}); return false;',
			)
		);
	echo ' &clubs ';
	echo $ajax->link(
			'Proceed to installation',
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
		NeutrinoCMS is meant to be used primarily by software developers. Neutrino aims
		to make it easy for them to publish programming articles and code - while not
		getting in the way. Because of that, Neutrino is not a CMS for everyone.
		There are no WYSIWYG editors of any kind. Articles are written using Markdown
		syntax. Code syntax highlighting is done using dp.SyntaxHighlighter and it
		is currently supported for the following languages:
		<ul>
			<li>C++</li>
			<li>C#</li>
			<li>CSS</li>
			<li>Delphi</li>
			<li>Java</li>
			<li>JavaScript</li>
			<li>PHP</li>
			<li>Python</li>
			<li>Ruby</li>
			<li>SQL</li>
			<li>VB</li>
			<li>XML</li>
		</ul>
	</p>
	<p>
		Please note that Neutrino is single user CMS at this stage and it might stay that
		way for a while (multiple users might be possible but this is not tested).
	</p>
	<p>
		NeutrinoCMS is a personal project I started as a hobby,
		to get familiar with CakePHP. As this is my first CakePHP project,
		there will obviously be some bugs and illogical things in the code,
		feel free to report those to &lt;neutrinocms[at]gmail.com&gt;
	</p>
	<p>
		NeutrinoCMS is released under GPL licence.
	</p>
</div>
<div id="neutrino-skip" style="display:none;">

		<?php echo $form->create(null, array('url' => array('controller' => 'setup', 'action' => 'install'))); ?>
		<?php echo $html->div('install-inputbox'); ?>
			<p>To proceed with NeutrinoCMS installation press next.</p>
			<?php echo $form->hidden('Installation.Step', array('value' => '0')); ?>
			<?php echo $form->submit('Next', array('class' => 'button')); ?>
		</div>
		<?php echo $form->end(); ?>

</div>