{if !$GLOBALS.current_user.logged_in}
    <div class="quest-menu pull-right">
        <div class="text-right">
            <a class="colorize-menu colorize-menu-text hidden-xs" href='{page_path id='user_registration'}'>[[Register]]</a>
            <span class="hidden-xs">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
            <a class="colorize-menu login-modal-trigger colorize-menu-text" href="#">[[Login]]</a>

        </div>
        <div class="modal login-window fade">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        {module name="users" function="login" template="login_dialog.tpl" HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.login-modal-trigger').click(function(e){
                $(this).closest('.quest-menu').find('.modal').modal('show');
            });
        })
    </script>
{else}
<ul class="user-menu-block pull-right list-inline clear-margin">
    <li class="dropdown yamm-fw">
        <a href="#" class="dropdown-toggle  colorize-menu colorize-menu-text" data-toggle="dropdown">
            {$GLOBALS.current_user.user_name} <i class="fa fa-chevron-down"></i>
        </a>
        {module name="users" function="user_menu"}
    </li>
</ul>
{/if}
