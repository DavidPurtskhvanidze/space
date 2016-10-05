<div class="userMenu shortenedUserMenu colorize-left-menu">
	<ul>
		<li><a href="{page_path id='service_providers'}">[[Service Providers]]</a></li>
		<li><a href="{page_path id='search'}">[[Search Listings]]</a></li>
		<li><a href="{page_path id='users_search'}">[[Search Sellers]]</a></li>
		{if $GLOBALS.current_page_uri == '/'}
			<li><a href="{page_path id='user_saved_listings'}">[[Saved Listings]]</a></li>
			<li><a href="{page_path id='user_saved_searches'}">[[Saved Searches]]</a></li>
		{/if}
	</ul>
</div>
