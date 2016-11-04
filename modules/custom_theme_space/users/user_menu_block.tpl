{if !$GLOBALS.current_user.logged_in}
    <ul class="nav navbar-nav navbar-right nav-user-links">
        <li><a class="button wb" href='{page_path id='user_registration'}'><span>[[Register]]</span></a></li>
        <li><a class="colorize-menu login-modal-trigger colorize-menu-text" href="#"><span>[[Login]]</span></a></li>
    </ul>
    <div class="modal login-window fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    {module name="users" function="login" template="login_dialog.tpl" HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.login-modal-trigger').click(function(e){
                $(this).closest('#main-navbar-collapse').find('.modal').modal('show');
            });
        })
    </script>
{else}
    <ul class="nav navbar-nav navbar-right nav-user-links">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle  colorize-menu colorize-menu-text" data-toggle="dropdown">
                <span>{$GLOBALS.current_user.user_name}</span>
                <i class="fa fa-angle-down" aria-hidden="true"></i>
            </a>
            {module name="users" function="user_menu"}
        </li>
    </ul>
{/if}

