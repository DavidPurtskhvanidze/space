{require component='jquery' file='jquery.js'}
{require component="jquery-cookie" file="jquery.cookie.js"}
<script type="text/javascript">
	function saveActiveTab(selector)
	{
		selector = selector.replace('active','');
		selector = '.' + $.trim(selector).replace(/\s/gi, ".");
		if(SupportsHtml5Storage())
		{
			localStorage.setItem('listingActiveTab_' + listingSid,selector);
		}
		else
		{
			$.cookie('listingActiveTab_' + listingSid,selector);
		}
	}

	function restoreActiveTab()
	{
		if(SupportsHtml5Storage())
		{
			var selector = localStorage.getItem('listingActiveTab_' + listingSid);
			$(selector + ' a').trigger('click');
		}
		else
		{
			var selector = $.cookie('listingActiveTab_' + listingSid);
			$(selector + ' a').trigger('click');
		}
	}

	function SupportsHtml5Storage()
	{
		try
		{
			return 'localStorage' in window && window['localStorage'] !== null;
		}
		catch (e)
		{
			return false;
		}
	}
</script>
