<ul id="nav">
	<li>
		<?php echo $ajax->link(
			'Markdown block elements',
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
			'Markdown span elements',
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
			'Markdown extra',
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
			'Credits',
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
	<dt onclick="toggleBlock('markdown-block', 'dd-headers');">Headers</dt>
	<dd id="dd-headers">
		<p>
			Headers use 1-6 hash characters at the start of the line,
			corresponding to header levels 1-6. For example:
		</p>
		<div class="syntax-example">
			# This is an H1
		</div>
		<p>
			Optionally, you may "close" atx-style headers. This is purely cosmetic -
			you can use this if you think it looks better. The closing hashes don't
			even need to match the number of hashes used to open the header. (The number
			of opening hashes determines the header level.)
		</p>
		<div class="syntax-example">
			## This is an H2 ##<br />
			### This is an H3 ######
		</div>
	</dd>
	<dt onclick="toggleBlock('markdown-block', 'dd-block-quotes');">Block quotes</dt>
	<dd id="dd-block-quotes">
		<div class="syntax-example">
			&gt; This is a blockquote with two paragraphs. Lorem ipsum dolor sit amet,<br />
			&gt; consectetuer adipiscing elit. Aliquam hendrerit mi posuere lectus.<br />
			&gt; Vestibulum enim wisi, viverra nec, fringilla in, laoreet vitae, risus.<br />
			&gt;<br />
			&gt; Donec sit amet nisl. Aliquam semper ipsum sit amet velit. Suspendisse<br />
			&gt; id sem consectetuer libero luctus adipiscing.
		</div>
		<p>
			Markdown allows you to be lazy and only put the &gt; before the first line of
			a hard-wrapped paragraph:
		</p>
		<div class="syntax-example">
			&gt; This is a blockquote with two paragraphs. Lorem ipsum dolor sit amet,<br />
			consectetuer adipiscing elit. Aliquam hendrerit mi posuere lectus.<br />
			Vestibulum enim wisi, viverra nec, fringilla in, laoreet vitae, risus.<br />
			<br />
			&gt; Donec sit amet nisl. Aliquam semper ipsum sit amet velit. Suspendisse<br />
			id sem consectetuer libero luctus adipiscing.
		</div>
		<p>
			Blockquotes can be nested (i.e. a blockquote-in-a-blockquote)
			by adding additional levels of &gt;:
		</p>
		<div class="syntax-example">
			&gt; This is the first level of quoting.<br />
			&gt;<br />
			&gt; &gt; This is nested blockquote.<br />
			&gt;<br />
			&gt; Back to the first level.<br />
		</div>
		<p>
			Blockquotes can contain other Markdown elements, including headers, lists,
			and code blocks:
		</p>
		<div class="syntax-example">
			&gt; ## This is a header.<br />
			&gt;<br />
			&gt; 1. This is the first list item.<br />
			&gt; 2. This is the second list item.<br />
			&gt;<br />
			&gt; Here's some example code:<br />
			&gt;<br />
			&gt; &nbsp;&nbsp;&nbsp;&nbsp;return shell_exec("echo $input | $markdown_script");
		</div>
	</dd>
	<dt onclick="toggleBlock('markdown-block', 'dd-lists');">Lists</dt>
	<dd id="dd-lists">
		<p>
			Unordered lists use asterisks, pluses, and hyphens
			- interchangably - as list markers:
		</p>
		<div class="syntax-example">
			* Red<br />
			* Green<br />
			* Blue<br />
		</div>
		<p>
			is equivalent to:
		</p>
		<div class="syntax-example">
			+ Red<br />
			+ Green<br />
			+ Blue<br />
		</div>
		<p>
			and:
		</p>
		<div class="syntax-example">
			- Red<br />
			- Green<br />
			- Blue<br />
		</div>
		<p>
			Ordered lists use numbers followed by periods:
		</p>
		<div class="syntax-example">
			1. Ted Crilly<br />
			2. Jack Hackett<br />
			3. Dougal McGuire<br />
		</div>
	</dd>
	<dt onclick="toggleBlock('markdown-block', 'dd-code-blocks');">Code Blocks</dt>
	<dd id="dd-code-blocks">
		<p>
			To produce a code block in Markdown, simply indent every line
			of the block by at least 4 spaces or 1 tab.
		</p>
		<div class="syntax-example">
			This is a normal paragraph:<br />
			<br />
    		&nbsp;&nbsp;&nbsp;&nbsp;This is a code block.
		</div>
		<p>
			Within a code block, ampersands (&amp;) and angle brackets
			(&lt; and &gt;) are automatically converted into HTML entities.
		</p>
		<p>
			Regular Markdown syntax is not processed within code blocks.
		</p>
	</dd>
	<dt onclick="toggleBlock('markdown-block', 'dd-horizontal-rules');">Horizontal Rules</dt>
	<dd id="dd-horizontal-rules">
		<p>
			You can produce a horizontal rule tag (&lt;hr /&gt;) by placing
			three or more hyphens, asterisks, or underscores on a line by
			themselves. If you wish, you may use spaces between the hyphens
			or asterisks. Each of the following lines will produce a horizontal rule:
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
	<dt onclick="toggleBlock('markdown-span', 'dd-links');">Links</dt>
	<dd id="dd-links">
		<p>
			Markdown supports two style of links: inline and reference.
		</p>
		<p>
			Inline link:
		</p>
		<div class="syntax-example">
			This is [an example](http://example.com/ "Title") inline link.<br />
			<br />
			[This link](http://example.net/) has no title attribute.
		</div>
		<p>
			If you're referring to a local resource on the same server,
			you can use relative paths:
		</p>
		<div class="syntax-example">
			See my [About](/about/) page for details.
		</div>
		<p>
			Reference-style links use a second set of square brackets,
			inside which you place a label of your choosing to identify the link.
			The implicit link name shortcut allows you to omit the name of the link,
			in which case the link text itself is used as the name.
		</p>
		<div class="syntax-example">
			This is [an example][id] reference-style link.<br />
			This is [an example] [id] reference-style link with spaces.<br />
			[NeutrinoCMS][]
		</div>
		<p>
			Then, anywhere in the document, you define your link label like this,
			on a line by itself:
		</p>
		<div class="syntax-example">
			[id]: http://example.com/  "Optional Title Here"<br />
			[id]: http://example.com/  'Optional Title Here'<br />
			[id]: http://example.com/  (Optional Title Here)<br />
			[id]: &lt;http://example.com/&gt;  "Optional Title Here"<br />
			[NeutrinoCMS]: http://dsi.vozibrale.com/
		</div>
	</dd>
	<dt onclick="toggleBlock('markdown-span', 'dd-emphasis');">Emphasis</dt>
	<dd id="dd-emphasis">
		<p>
			Markdown treats asterisks (*) and underscores (_) as indicators of emphasis.
			Text wrapped with one * or _ will be wrapped with an HTML &lt;em&gt; tag;
			double *'s or _'s will be wrapped with an HTML &lt;strong&gt; tag.
		</p>
		<div class="syntax-example">
			*single asterisks*<br />
			<br />
			_single underscores_<br />
			<br />
			**double asterisks**<br />
			<br />
			__double underscores__
		</div>
		<p>
			Emphasis can be used in the middle of a word. But if you surround an * or
			_ with spaces, it'll be treated as a literal asterisk or underscore.
		</p>
	</dd>
	<dt onclick="toggleBlock('markdown-span', 'dd-code');">Code</dt>
	<dd id="dd-code">
		<p>
			To indicate a span of code, wrap it with backtick quotes (&lsquo;). Unlike a
			pre-formatted code block, a code span indicates code within a normal paragraph.
		</p>
		<div class="syntax-example">
			Use the &lsquo;printf()&lsquo;  function.
		</div>
	</dd>
	<dt onclick="toggleBlock('markdown-span', 'dd-image');">Images</dt>
	<dd id="dd-image">
		<p>
			Markdown uses an image syntax that is intended to resemble the syntax
			for links, allowing for two styles: inline and reference.
		</p>
		<p>
			Inline image syntax looks like this:
		</p>
		<div class="syntax-example">
			![Alt text](/path/to/img.jpg)<br />
			<br />
			![Alt text](/path/to/img.jpg "Optional title")
		</div>
		<p>
			Reference-style image syntax looks like this:
		</p>
		<div class="syntax-example">
			![Alt text][id]<br />
		</div>
		<p>
			Image references are defined using syntax identical to link references:
		</p>
		<div class="syntax-example">
			[id]: url/to/image.jpg  "Optional title attribute"
		</div>
	</dd>
</dl>
</div>
<div id="markdown-extra" class="content" style="display:none;">
<dl>
	<dt onclick="toggleBlock('markdown-extra', 'dd-header-id-attribute');">Header ID Attribute</dt>
	<dd id="dd-header-id-attribute">
		<p>
			With PHP Markdown Extra, you can set id attribute to headers.
			You should add the id prefixed by a hash inside curly brackets after
			the header at the end of the line, like this:
		</p>
		<div class="syntax-example">
			## Header 2 ##      {#header2}
		</div>
		<p>
			Then you can create links to different parts of the same document like this:
		</p>
		<div class="syntax-example">
			[Link back to header 1](#header1)
		</div>
	</dd>
	<dt onclick="toggleBlock('markdown-extra', 'dd-tables');">Tables</dt>
	<dd id="dd-tables">
		<div class="syntax-example">
			First Header | Second Header<br />
			------------ | -------------<br />
			Content Cell | Content Cell<br />
			Content Cell | Content Cell
		</div>
		<p>
			You can specify alignement for each column by adding colons to separator
			lines. A colon at the left of the separator line will make the column
			left-aligned; a colon on the right of the line will make the column
			right-aligned; colons at both side means the column is center-aligned.
		</p>
		<div class="syntax-example">
			| Item&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | Value |<br />
			| --------- | -----:|<br />
			| Computer&nbsp; | $1600 |<br />
			| Phone&nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;$12 |<br />
			| Pipe&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;$1 |
		</div>
	</dd>
	<dt onclick="toggleBlock('markdown-extra', 'dd-definition-lists');">Definition Lists</dt>
	<dd id="dd-definition-lists">
		<p>
			A simple definition list in PHP Markdown Extra is made of a single-line
			term followed by a colon and the definition for that term.
		</p>
		<div class="syntax-example">
			Apple<br />
			: &nbsp;&nbsp;Pomaceous fruit of plants of the genus Malus in<br />
			&nbsp;&nbsp;&nbsp; the family Rosaceae.<br />
			<br />
			Orange<br />
			: &nbsp;&nbsp;The fruit of an evergreen tree of the genus Citrus.<br />
		</div>
		<p>
			Definition lists can have more than one definition associated with one term.
			You can also associate more than one term to a definition.
		</p>
		<div class="syntax-example">
			Apple<br />
			: &nbsp;&nbsp;Pomaceous fruit of plants of the genus Malus in<br />
			&nbsp;&nbsp;&nbsp; the family Rosaceae.<br />
			: &nbsp;&nbsp;An american computer company.<br />
			<br />
			Term 1<br />
			Term 2<br />
			: &nbsp;&nbsp;Definition a
		</div>
	</dd>
	<dt onclick="toggleBlock('markdown-extra', 'dd-footnotes');">Footnotes</dt>
	<dd id="dd-footnotes">
		<p>
			Footnotes work mostly like reference-style links.
		</p>
		<div class="syntax-example">
			That's some text with a footnote.[^1]<br />
			<br />
			[^1]: And that's the footnote.
		</div>
	</dd>
	<dt onclick="toggleBlock('markdown-extra', 'dd-abbreviations');">Abbreviations</dt>
	<dd id="dd-abbreviations">
		<p>
			PHP Markdown Extra adds supports for abbreviations (HTML tag &lt;abbr&gt;).
			How it works is pretty simple: create an abbreviation definition like this:
		</p>
		<div class="syntax-example">
			*[HTML]: Hyper Text Markup Language<br />
			*[W3C]:  World Wide Web Consortium
		</div>
	</dd>
</dl>
</div>
<div id="credits" class="content" style="display:none;">
	<p>
		Credits for the original Markdown / Markdown Extra and their documentation go to
		<a href="http://daringfireball.net/">John Gruber</a> and
		<a href="http://www.michelf.com/">Michel Fortin</a>.
	</p>
	<p>
		Markdown and Markdown extra syntax pages have been mostly copy-pasted into this
		help document from their original location.
	</p>
	<p>
		The original syntax pages can be located here:
	</p>
	<div>Markdown: <a href="http://daringfireball.net/projects/markdown/syntax">http://daringfireball.net/projects/markdown/syntax</a></div>
	<div>Markdown Extra: <a href="http://michelf.com/projects/php-markdown/syntax/">http://michelf.com/projects/php-markdown/syntax/</a></div>
	<p>
		If you find a discrepancy in this help document, send an e-mail to
		neutrinocms [AT] gmail.com and it will be fixed in the next release of NeutrinoCMS.
	</p>
</div>
</div>
<?php
echo $javascript->codeBlock
	('
		$$("#container dd").each(Element.hide);
	');
?>