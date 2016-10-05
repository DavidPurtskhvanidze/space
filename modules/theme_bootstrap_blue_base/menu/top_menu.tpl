<ul class="nav navbar-nav">
    <li>
        <a class="colorize-menu" href="{page_path id='root'}">
            <i class="fa fa-home hidden-md hidden-lg"></i>[[Home]]
        </a>
    </li>
    <li>
        <a class="colorize-menu" href='{page_path id='search_car'}'>
            <i class="fa fa-search hidden-md hidden-lg"></i>[[Find]]
        </a>
    </li>
    <li>
        <a class="colorize-menu" href='{page_path id='listing_add'}'>
            <i class="fa fa-shopping-cart hidden-md hidden-lg"></i>[[Sell]]
        </a>
    </li>
    <li>
        <a class="colorize-menu" href='{page_path id='users_search'}'>
            <i class="fa fa-users hidden-md hidden-lg"></i>[[Sellers]]
        </a>
    </li>
    <li>
        <a class="colorize-menu" href='{page_path id='service_providers'}'>
            <i class="fa fa-list hidden-md hidden-lg"></i>[[Services]]
        </a>
    </li>
    <li>
        <a class="colorize-menu" href='{page_path id='contact'}'>
            <i class="fa fa-envelope-o hidden-md hidden-lg"></i>[[Contact Us]]
        </a>
    </li>
    {if !$GLOBALS.current_user.logged_in}
    <li class="visible-xs">
        <a class="colorize-menu colorize-menu-text" href='{page_path id='user_registration'}'>
            <i class="fa fa-pencil hidden-md hidden-lg"></i>[[Register]]
        </a>
    </li>
    {/if}
</ul>
