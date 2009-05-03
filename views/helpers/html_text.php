<?php

// HtmlTextHelper by grigri [just-a-quick-test version]

class HtmlTextHelper extends AppHelper
{
	var $helpers = array('Text');

	var $emptyTags = array(
		'area', 'base', 'basefont', 'bgsound', 'br', 'col', 'embed', 'frame',
		'hr', 'img', 'input', 'isindex', 'link', 'meta', 'param', 'wbr');

	function _tokenizeHTML($html, $prefix = null)
	{
		#   Regular expression derived from the _tokenize() subroutine in
		#   Brad Choate's MTRegex plugin.
		#   <http://www.bradchoate.com/past/mtregex.php>
		#
		$index = 0;
		$tokens = array();

		if ($prefix)
		{
			$pattern =	'(?s:<!(?:--.*?--\s*)+>)|'.	# comment
						'(?s:<\?.*?\?>)|'.			# processing instruction
						# regular tags
						'(?:<[/!$]?' . $prefix . ':[-a-zA-Z0-9]+\b(?>[^"\'>]+|"[^"]*"|\'[^\']*\')*>)';
		}
		else
		{
			$pattern =	'(?s:<!(?:--.*?--\s*)+>)|'.	# comment
						'(?s:<\?.*?\?>)|'.			# processing instruction
						# regular tags
						'(?:<[/!$]?[-a-zA-Z0-9:]+\b(?>[^"\'>]+|"[^"]*"|\'[^\']*\')*>)';
		}

		$parts = preg_split("{($pattern)}", $html, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);

		foreach ($parts as $match)
		{
			list($part, $offset) = $match;

			$token = array
			(
				'type'     => null,
				'contents' => $part,
				'offset'   => $offset
			);

			$index++;

			if (strlen($part) == 0)
				continue;

			if (strlen(trim($part)) == 0)
			{
				$token['type'] = 'whitespace';
			}
			else
			{
				if ($index % 2 && $part != '')
				{
					$token['type'] = 'text';
				}
				else
				{
					switch (true)
					{
						case substr($part, 0, 4) == '<!--':
							$token['type'] = 'comment';
							break;
						case substr($part, 0, 2) == '<?':
							$token['type'] = 'instruction';
							break;
						default:
							$token['type'] = 'tag';
					}
				}
			}

			$tokens[] = $token;
		}

		return $tokens;
	}

	function highlight($text, $phrase, $highlighter = '<span class="highlight">\1</span>')
	{
		$tokens = $this->_tokenizeHTML($text);
		$out = "";

		foreach ($tokens as $token)
		{
			if ($token['type'] == 'text')
			{
				$out .= $this->Text->highlight($token['contents'], $phrase, $highlighter);
			}
			else
			{
				$out .= $token['contents'];
			}
		}

		return $out;
	}

	function truncate($text, $length = 100, $ending = "...", $exact = true)
	{
		$tokens = $this->_tokenizeHTML($text);
		$nodeStack = array();
		$out = "";
		$curLength = 0;

		foreach ($tokens as $token)
		{
			if ($curLength >= $length)
				break;

			switch ($token['type'])
			{
				case 'tag':
					$out .= $token['contents'];

					if (!$this->_isEmptyTag($token['contents']))
					{
						$tagName = preg_split('/(?:\s+)|>/', $token['contents'], 2);
						$tagName = strtolower(substr($tagName[0], 1));
						if ($tagName[0] != '/')
						{
							// Opening tag
							array_push($nodeStack, $tagName);
						}
						else
						{
							// Closing tag
							while (($tmpTagName = array_pop($nodeStack)) && ($tmpTagName == $tagName))
								{}
						}
					}
					break;
				case 'text':
					if ($curLength + strlen($ending) + strlen($token['contents']) > $length)
					{
						$out .= $this->Text->truncate($token['contents'], $length - $curLength, $ending, $exact);
						$curLength = $length;
					}
					else
					{
						$out .= $token['contents'];
						$curLength += strlen($token['contents']);
					}
					break;
				default:
					$out .= $token['contents'];
			}
		}

		while ($tagName = array_pop($nodeStack))
		{
			$out .= '</' . $tagName . '>';
		}

		return $out;
	}

	function _isEmptyTag($tag)
	{
		if (substr($tag, -2, 2) == '/>')
			return true;

		if (preg_match('/^<(' . implode('|', $this->emptyTags) . ')\s/i', $tag))
			return true;

		return false;
	}
}

?>
