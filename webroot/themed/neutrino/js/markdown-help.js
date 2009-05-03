var tabDivs =
	[
		"markdown-block",
		"markdown-span",
		"markdown-extra",
		"credits"
	];

var tabTabs =
	[
		"link-tab-markdown-block",
		"link-tab-markdown-span",
		"link-tab-markdown-extra",
		"link-tab-credits"
	];

function toggleTab(tab, block)
{
	tabDivs.each(Element.hide);
	tabTabs.each(function (n) {$(n).removeClassName("selected");});
	$(block).show();
	Element.addClassName($(tab), "selected");
}

function toggleBlock(block, self)
{
	items = $A($(block).getElementsBySelector("dd"));

	items.each
		(
			function(n)
			{
				if ($(n).visible() && $(n) != $(self))
				{
					Effect.BlindUp(n);
				}
			}
		)

	if (!$(self).visible())
	{
		Effect.BlindDown(self);
	}
	else
	{
		Effect.BlindUp(self);
	}
}
