<ul class="userMenu custom dropdown-menu-right dropdown-menu" role="menu">
	<li><a href="{page_path id='listing_add'}"><i class="fa fa-plus"></i>[[Add Listing]]</a></li>
	<li><a href="{page_path id='user_listings'}"><i class="fa fa-list-ul"></i>[[My Listings]]</a></li>
	{extension_point name="modules\users\apps\FrontEnd\IUserMenuItem"}
	<li><a href="{page_path id='user_payments'}"><i class="fa fa-arrows-v"></i>[[My Transactions]]</a></li>
	<li><a href="{page_path id='user_profile'}"><i class="fa fa-user"></i>[[My Profile]]</a></li>
	<li><a href="{page_path id='user_notifications'}"><i class="fa fa-bell-o"></i>[[My Notifications]]</a></li>
	<li><a href="{page_path id='user_subscription'}"><i class="fa fa-rss"></i>[[Subscription]]</a></li>
    <li><a href="{page_path id='user_logout'}"><i class="fa fa-sign-out"></i>[[Log Out]]</a></li>
</ul>
