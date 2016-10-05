<ul class="nav nav-list">
  {foreach from=$menu item="block"}
      <li{if $block.active} class="active"{/if}>
        <a href="#" class="dropdown-toggle">
          <i class="icon-{$block.caption|lower|replace:' ':'-'}"></i>
          <span class="menu-text">[[{$block.caption}]]</span>
          <b class="arrow icon-angle-down"></b>
        </a>
        <ul class="submenu">
          {foreach from=$block.items item="menuItem"}
              <li class="{if $menuItem.active}active{/if}">
                <a href="{$menuItem.reference}" class="{if $menuItem.active}active{/if}">
                  <i class="icon-double-angle-right"></i>
                  [[$menuItem.title]]
                </a>
              </li>
          {/foreach}
        </ul>
      </li>
  {/foreach}
</ul>

<div class="sidebar-collapse" id="sidebar-collapse">
  <i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
</div>
{literal}
<script type="text/javascript">
  try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}


    $(function() {

        $('#sidebar .nav li.active .arrow').toggleClass('icon-angle-down icon-angle-up');

        $( ".dropdown-toggle" ).click(function(e)
        {
            if (!$(this).closest('li').hasClass('open'))
            {
                $('#sidebar .nav li:not(.active).open .arrow').toggleClass('icon-angle-down icon-angle-up');
            }

            $( this ).children(".arrow").toggleClass('icon-angle-down icon-angle-up');
        });
    });

</script>
{/literal}
