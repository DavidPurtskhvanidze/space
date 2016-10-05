<div class="userMenu buyersMenu">
    <ul>
        <li><a href="{page_path id='user_profile'}">[[My Profile]]</a></li>
        <li><a href="{page_path id='user_notifications'}">[[My Notifications]]</a></li>
		{if $GLOBALS.current_base_uri != {page_uri id='search_results'} && $GLOBALS.current_base_uri != {page_uri id='users_listings'}}
			<li><a href="{page_path id='user_saved_listings'}">[[Saved Listings]]</a></li>
			<li><a href="{page_path id='user_saved_searches'}">[[Saved Searches]]</a></li>
		{/if}
        <li><a class="logout" href="{page_path id='user_logout'}">[[Log Out]]</a></li>
    </ul>
</div>
