<ul id="nav">
	<li>
		<?php echo $ajax->link(
			__('Markdown block elements', true),
			'#',
			array
			(
				'id' => 'link-tab-markdown-block',
				'class' => 'selected',
				'before' => '
					toggleTab("link-tab-markdown-block", "markdown-block");
					return false'
			)
		); ?>
	</li>
	<li><?php echo $ajax->link(
			__('Markdown span elements', true),
			'#',
			array
			(
				'id' => 'link-tab-markdown-span',
				'before' => '
					toggleTab("link-tab-markdown-span", "markdown-span");
					return false'
			)
		); ?>
	</li>
	<li><?php echo $ajax->link(
			__('Markdown extra', true),
			'#',
			array
			(
				'id' => 'link-tab-markdown-extra',
				'before' => '
					toggleTab("link-tab-markdown-extra", "markdown-extra");
					return false'
			)
		); ?>
	</li>
	<li><?php echo $ajax->link(
			__('Credits', true),
			'#',
			array
			(
				'id' => 'link-tab-credits',
				'before' => '
					toggleTab("link-tab-credits", "credits");
					return false'
			)
		); ?>
	</li>
</ul>
<div id="container">
<div id="markdown-block" class="content">
<dl>
	<dt onclick="toggleBlock('markdown-block', 'dd-headers');"><?php __('Headers'); ?></dt>
	<dd id="dd-headers">
		<p>
		<?php __('Headers use 1-6 hash characters at the start of the line, corresponding to header levels 1-6. For example:'); ?>
		</p>
		<div class="syntax-example">
		<?php __('# This is an H1'); ?>
		</div>
		<p>
		<?php __('Optionally, you may "close" atx-style headers.'); ?>
		<?php __('This is purely cosmetic - you can use this if you think it looks better.'); ?>
		<?php __('The closing hashes don\'t even need to match the number of hashes used to open the header.'); ?>
		<?php __('(The number of opening hashes determines the header level.)'); ?>
		</p>
		<div class="syntax-example">
			<?php __('## This is an H2 ##'); ?>
			<br />
			<?php __('### This is an H3 ######'); ?>
		</div>
	</dd>
	<dt onclick="toggleBlock('markdown-block', 'dd-block-quotes');"><?php __('Block quotes'); ?></dt>
	<dd id="dd-block-quotes">
		<div class="syntax-example">
			&gt; <?php __('This is a blockquote with two paragraphs.'); ?> Lorem ipsum dolor sit amet,<br />
			&gt; consectetuer adipiscing elit. Aliquam hendrerit mi posuere lectus.<br />
			&gt; Vestibulum enim wisi, viverra nec, fringilla in, laoreet vitae, risus.<br />
			&gt;<br />
			&gt; Donec sit amet nisl. Aliquam semper ipsum sit amet velit. Suspendisse<br />
			&gt; id sem consectetuer libero luctus adipiscing.
		</div>
		<p>
			<?php __('Markdown allows you to be lazy and only put the &gt; before the first line of a hard-wrapped paragraph:'); ?>
		</p>
		<div class="syntax-example">
			&gt; <?php __('This is a blockquote with two paragraphs.'); ?> Lorem ipsum dolor sit amet,<br />
			consectetuer adipiscing elit. Aliquam hendrerit mi posuere lectus.<br />
			Vestibulum enim wisi, viverra nec, fringilla in, laoreet vitae, risus.<br />
			<br />
			&gt; Donec sit amet nisl. Aliquam semper ipsum sit amet velit. Suspendisse<br />
			id sem consectetuer libero luctus adipiscing.
		</div>
		<p>
			<?php __('Blockquotes can be nested (i.e. a blockquote-in-a-blockquote) by adding additional levels of &gt;:'); ?>
		</p>
		<div class="syntax-example">
			&gt; <?php __('This is the first level of quoting.'); ?><br />
			&gt;<br />
			&gt; &gt; <?php __('This is nested blockquote.'); ?><br />
			&gt;<br />
			&gt; <?php __('Back to the first level.'); ?><br />
		</div>
		<p>
			<?php __('Blockquotes can contain other Markdown elements, including headers, lists, and code blocks:'); ?>
		</p>
		<div class="syntax-example">
			&gt; <?php __('## This is a header.'); ?><br />
			&gt;<br />
			&gt; <?php __('1. This is the first list item.'); ?><br />
			&gt; <?php __('2. This is the second list item.'); ?><br />
			&gt;<br />
			&gt; <?php __('Here\'s some example code:'); ?><br />
			&gt;<br />
			&gt; &nbsp;&nbsp;&nbsp;&nbsp;return shell_exec("echo $input | $markdown_script");
		</div>
	</dd>
	<dt onclick="toggleBlock('markdown-block', 'dd-lists');"><?php __('Lists'); ?></dt>
	<dd id="dd-lists">
		<p>
			<?php __('Unordered lists use asterisks, pluses, and hyphens - interchangably - as list markers:'); ?>
		</p>
		<div class="syntax-example">
			* <?php __('Red'); ?><br />
			* <?php __('Green'); ?><br />
			* <?php __('Blue'); ?><br />
		</div>
		<p>
			<?php __('is equivalent to:'); ?>
		</p>
		<div class="syntax-example">
			+ <?php __('Red'); ?><br />
			+ <?php __('Green'); ?><br />
			+ <?php __('Blue'); ?><br />
		</div>
		<p>
			and:
		</p>
		<div class="syntax-example">
			- <?php __('Red'); ?><br />
			- <?php __('Green'); ?><br />
			- <?php __('Blue'); ?><br />
		</div>
		<p>
			<?php __('Ordered lists use numbers followed by periods:'); ?>
		</p>
		<div class="syntax-example">
			<?php __('1. Ted Crilly'); ?><br />
			<?php __('2. Jack Hackett'); ?><br />
			<?php __('3. Dougal McGuire'); ?><br />
		</div>
	</dd>
	<dt onclick="toggleBlock('markdown-block', 'dd-code-blocks');"><?php __('Code Blocks'); ?></dt>
	<dd id="dd-code-blocks">
		<p>
			<?php __('To produce a code block in Markdown, simply indent every line'); ?>
			<?php __('of the block by at least 4 spaces or 1 tab.'); ?>
		</p>
		<div class="syntax-example">
			<?php __('This is a normal paragraph:'); ?><br />
			<br />
    		&nbsp;&nbsp;&nbsp;&nbsp;<?php __('This is a code block.'); ?>
		</div>
		<p>
			<?php __('Within a code block, ampersands (&amp;) and angle brackets'); ?>
			<?php __('(&lt; and &gt;) are automatically converted into HTML entities.'); ?>
		</p>
		<p>
			<?php __('Regular Markdown syntax is not processed within code blocks.'); ?>
		</p>
	</dd>
	<dt onclick="toggleBlock('markdown-block', 'dd-horizontal-rules');"><?php __('Horizontal Rules'); ?></dt>
	<dd id="dd-horizontal-rules">
		<p>
			<?php __('You can produce a horizontal rule tag (&lt;hr /&gt;) by placing'); ?>
			<?php __('three or more hyphens, asterisks, or underscores on a line by'); ?>
			<?php __('themselves. If you wish, you may use spaces between the hyphens'); ?>
			<?php __('or asterisks. Each of the following lines will produce a horizontal rule:'); ?>
		</p>
		<div class="syntax-example">
			* * *<br />
			<br />
			***<br />
			<br />
			*****<br />
			<br />
			- - -<br />
			<br />
			---------------------------------------
		</div>
	</dd>
</dl>
</div>
<div id="markdown-span" class="content" style="display:none;">
<dl>
	<dt onclick="toggleBlock('markdown-span', 'dd-links');"><?php __('Links'); ?></dt>
	<dd id="dd-links">
		<p>
			<?php __('Markdown supports two style of links: inline and reference.'); ?>
		</p>
		<p>
			<?php __('Inline link:'); ?>
		</p>
		<div class="syntax-example">
			<?php __('This is [an example](http://example.com/ "Title") inline link.'); ?><br />
			<br />
			<?php __('[This link](http://example.net/) has no title attribute.'); ?>
		</div>
		<p>
			<?php __('If you\'re referring to a local resource on the same server, you can use relative paths:'); ?>
		</p>
		<div class="syntax-example">
			<?php __('See my [About](/about/) page for details.'); ?>
		</div>
		<p>
			<?php __('Reference-style links use a second set of square brackets,'); ?>
			<?php __('inside which you place a label of your choosing to identify the link.'); ?>
			<?php __('The implicit link name shortcut allows you to omit the name of the link,'); ?>
			<?php __('in which case the link text itself is used as the name.'); ?>
		</p>
		<div class="syntax-example">
			<?php __('This is [an example][id] reference-style link.'); ?><br />
			<?php __('This is [an example] [id] reference-style link with spaces.'); ?><br />
			[NeutrinoCMS][]
		</div>
		<p>
			<?php __('Then, anywhere in the document, you define your link label like this, on a line by itself:'); ?>
		</p>
		<div class="syntax-example">
			<?php __('[id]: http://example.com/  "Optional Title Here"'); ?><br />
			<?php __('[id]: http://example.com/  \'Optional Title Here\''); ?><br />
			<?php __('[id]: http://example.com/  (Optional Title Here)'); ?><br />
			<?php __('[id]: &lt;http://example.com/&gt;  "Optional Title Here"'); ?><br />
			[NeutrinoCMS]: http://dsi.vozibrale.com/
		</div>
	</dd>
	<dt onclick="toggleBlock('markdown-span', 'dd-emphasis');"><?php __('Emphasis'); ?></dt>
	<dd id="dd-emphasis">
		<p>
			<?php __('Markdown treats asterisks (*) and underscores (_) as indicators of emphasis.'); ?>
			<?php __('Text wrapped with one * or _ will be wrapped with an HTML &lt;em&gt; tag;'); ?>
			<?php __('double *\'s or _\'s will be wrapped with an HTML &lt;strong&gt; tag.'); ?>
		</p>
		<div class="syntax-example">
			<?php __('*single asterisks*'); ?><br />
			<br />
			<?php __('_single underscores_'); ?><br />
			<br />
			<?php __('**double asterisks**'); ?><br />
			<br />
			<?php __('__double underscores__'); ?>
		</div>
		<p>
			<?php __('Emphasis can be used in the middle of a word. But if you surround an * or'); ?>
			<?php __('_ with spaces, it\'ll be treated as a literal asterisk or underscore.'); ?>
		</p>
	</dd>
	<dt onclick="toggleBlock('markdown-span', 'dd-code');"><?php __('Code'); ?></dt>
	<dd id="dd-code">
		<p>
			<?php __('To indicate a span of code, wrap it with backtick quotes (&lsquo;). Unlike a'); ?>
			<?php __('pre-formatted code block, a code span indicates code within a normal paragraph.'); ?>
		</p>
		<div class="syntax-example">
			<?php __('Use the &lsquo;printf()&lsquo;  function.'); ?>
		</div>
	</dd>
	<dt onclick="toggleBlock('markdown-span', 'dd-image');"><?php __('Images'); ?></dt>
	<dd id="dd-image">
		<p>
			<?php __('Markdown uses an image syntax that is intended to resemble the syntax'); ?>
			<?php __('for links, allowing for two styles: inline and reference.'); ?>
		</p>
		<p>
			<?php __('Inline image syntax looks like this:'); ?>
		</p>
		<div class="syntax-example">
			<?php __('![Alt text](/path/to/img.jpg)'); ?><br />
			<br />
			<?php __('![Alt text](/path/to/img.jpg "Optional title")'); ?>
		</div>
		<p>
			<?php __('Reference-style image syntax looks like this:'); ?>
		</p>
		<div class="syntax-example">
			<?php __('![Alt text][id]'); ?><br />
		</div>
		<p>
			<?php __('Image references are defined using syntax identical to link references:'); ?>
		</p>
		<div class="syntax-example">
			<?php __('[id]: url/to/image.jpg  "Optional title attribute"'); ?>
		</div>
	</dd>
</dl>
</div>
<div id="markdown-extra" class="content" style="display:none;">
<dl>
	<dt onclick="toggleBlock('markdown-extra', 'dd-header-id-attribute');"><?php __('Header ID Attribute'); ?></dt>
	<dd id="dd-header-id-attribute">
		<p>
			<?php __('With PHP Markdown Extra, you can set id attribute to headers.'); ?>
			<?php __('You should add the id prefixed by a hash inside curly brackets after'); ?>
			<?php __('the header at the end of the line, like this:'); ?>
		</p>
		<div class="syntax-example">
			<?php __('## Header 2 ##      {#header2}'); ?>
		</div>
		<p>
			<?php __('Then you can create links to different parts of the same document like this:'); ?>
		</p>
		<div class="syntax-example">
			<?php __('[Link back to header 1](#header1)'); ?>
		</div>
	</dd>
	<dt onclick="toggleBlock('markdown-extra', 'dd-tables');"><?php __('Tables'); ?></dt>
	<dd id="dd-tables">
		<div class="syntax-example">
			<?php __('First Header | Second Header'); ?><br />
			------------ | -------------<br />
			<?php __('Content Cell | Content Cell'); ?><br />
			<?php __('Content Cell | Content Cell'); ?>
		</div>
		<p>
			<?php __('You can specify alignement for each column by adding colons to separator'); ?>
			<?php __('lines. A colon at the left of the separator line will make the column'); ?>
			<?php __('left-aligned; a colon on the right of the line will make the column'); ?>
			<?php __('right-aligned; colons at both side means the column is center-aligned.'); ?>
		</p>
		<div class="syntax-example">
			| <?php __('Item&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | Value'); ?> |<br />
			| --------- | -----:|<br />
			| <?php __('Computer&nbsp; | $1600'); ?> |<br />
			| <?php __('Phone&nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;$12'); ?> |<br />
			| <?php __('Pipe&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;$1'); ?> |
		</div>
	</dd>
	<dt onclick="toggleBlock('markdown-extra', 'dd-definition-lists');"><?php __('Definition Lists'); ?></dt>
	<dd id="dd-definition-lists">
		<p>
			<?php __('A simple definition list in PHP Markdown Extra is made of a single-line'); ?>
			<?php __('term followed by a colon and the definition for that term.'); ?>
		</p>
		<div class="syntax-example">
			<?php __('Apple'); ?><br />
			: &nbsp;&nbsp;<?php __('Pomaceous fruit of plants of the genus Malus in'); ?><br />
			&nbsp;&nbsp;&nbsp; <?php __('the family Rosaceae.'); ?><br />
			<br />
			<?php __('Orange'); ?><br />
			: &nbsp;&nbsp;<?php __('The fruit of an evergreen tree of the genus Citrus.'); ?><br />
		</div>
		<p>
			<?php __('Definition lists can have more than one definition associated with one term.'); ?>
			<?php __('You can also associate more than one term to a definition.'); ?>
		</p>
		<div class="syntax-example">
			<?php __('Apple'); ?><br />
			: &nbsp;&nbsp;<?php __('Pomaceous fruit of plants of the genus Malus in'); ?><br />
			&nbsp;&nbsp;&nbsp; <?php __('the family Rosaceae.'); ?><br />
			: &nbsp;&nbsp;<?php __('An american computer company.'); ?><br />
			<br />
			<?php __('Term 1'); ?><br />
			<?php __('Term 2'); ?><br />
			: &nbsp;&nbsp;<?php __('Definition a'); ?>
		</div>
	</dd>
	<dt onclick="toggleBlock('markdown-extra', 'dd-footnotes');"><?php __('Footnotes'); ?></dt>
	<dd id="dd-footnotes">
		<p>
			<?php __('Footnotes work mostly like reference-style links.'); ?>
		</p>
		<div class="syntax-example">
			<?php __('That\'s some text with a footnote.[^1]'); ?><br />
			<br />
			<?php __('[^1]: And that\'s the footnote.'); ?>
		</div>
	</dd>
	<dt onclick="toggleBlock('markdown-extra', 'dd-abbreviations');"><?php __('Abbreviations'); ?></dt>
	<dd id="dd-abbreviations">
		<p>
			<?php __('PHP Markdown Extra adds supports for abbreviations (HTML tag &lt;abbr&gt;).'); ?>
			<?php __('How it works is pretty simple: create an abbreviation definition like this:'); ?>
		</p>
		<div class="syntax-example">
			<?php __('*[HTML]: Hyper Text Markup Language'); ?><br />
			<?php __('*[W3C]:  World Wide Web Consortium'); ?>
		</div>
	</dd>
</dl>
</div>
<div id="credits" class="content" style="display:none;">
	<p>
		<?php __('Credits for the original Markdown / Markdown Extra and their documentation go to'); ?>
		<a href="http://daringfireball.net/">John Gruber</a> <?php __('and'); ?>
		<a href="http://www.michelf.com/">Michel Fortin</a>.
	</p>
	<p>
		<?php __('Markdown and Markdown extra syntax pages have been mostly copy-pasted into this'); ?>
		<?php __('help document from their original location.'); ?>
	</p>
	<p>
		<?php __('The original syntax pages can be located here:'); ?>
	</p>
	<div>Markdown: <a href="http://daringfireball.net/projects/markdown/syntax">http://daringfireball.net/projects/markdown/syntax</a></div>
	<div>Markdown Extra: <a href="http://michelf.com/projects/php-markdown/syntax/">http://michelf.com/projects/php-markdown/syntax/</a></div>
	<p>
		<?php __('If you find a discrepancy in this help document, send an e-mail to'); ?>
		<?php __('neutrinocms [AT] gmail.com and it will be fixed in the next release of NeutrinoCMS.'); ?>
	</p>
</div>
</div>
<?php
echo $javascript->codeBlock
	('
		$$("#container dd").each(Element.hide);
	');
?>