<?php
echo $javascript->codeBlock
	('
		if (Prototype.Browser.IE)
		{
			$("flash-wrap").innerHTML +=
				"<div class=\"message\" style=\"text-align:center;\">" +
				"It seems like you are using Internet Explorer. This is bad.<br />" +
				"IE does not obey the web standards, and many of the features on this site will not work.<br />" +
				"Because Microsoft is doing this on purpose, <u>these features will not be tweaked to accomodate IE<\/u>.<br />" +
				"To view this site properly, please switch to " +
				"<a href=\"http://getfirefox.com/\" title=\"Get Firefox - The Browser, Reloaded.\">Firefox<\/a> " +
				"as soon as possible.<\/div>";
		}
	');
?>