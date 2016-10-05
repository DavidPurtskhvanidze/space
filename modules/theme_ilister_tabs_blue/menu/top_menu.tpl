<ul class="headerMenu">
	{if $GLOBALS.mobile_front_end_url}
		<li>
			<a href="{$GLOBALS.mobile_front_end_url}" class="colorize-menu"><img src="{url file='main^icons/smartphone.png'}" alt="[[Mobile Website:raw]]" /></a>
			<a href="{$GLOBALS.mobile_front_end_url}" class="colorize-menu">[[Mobile Website]]</a>
		</li>
	{/if}
	<li><a href='{page_path id='search'}' class="colorize-menu">[[Find]]</a></li>
	<li><a href='{page_path id='listing_add'}' class="colorize-menu">[[Sell]]</a></li>
	<li><a href='{page_path id='users_search'}' class="colorize-menu">[[Sellers]]</a></li>
	<li><a href='{page_path id='service_providers'}' class="colorize-menu">[[Services]]</a></li>
	<li><a href='{page_path id='contact'}' class="colorize-menu">[[Contact Us]]</a></li>
</ul>
