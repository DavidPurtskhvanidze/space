<div class="fullWidthBlock headerMenuBlock colorize-menu">
	<div class="fixedWidthBlock">
		<div class="headerMenuWrapper">
			{module name="menu" function="top_menu"}
		</div>
        {extension_point name='modules\main\apps\FrontEnd\IWidgetDisplayer' HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}

        <div class="loginMenuWrapper">
			{if !$GLOBALS.current_user.logged_in}
				<ul class="loginMenu">
					<li><a class="loginDialogControl colorize-menu" href='#'>[[Login]]</a></li>
					<li><span>|</span></li>
					<li><a class="colorize-menu" href='{page_path id='user_registration'}'>[[Register]]</a></li>
				</ul>
				<div class="loginDialogWrapper" style="display: none;">
					{module name="users" function="login" HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}
				</div>
			{else}
				<div class="userGreeting">
					<a class="myAccount colorize-menu" href="#">
						{if !empty($GLOBALS.current_user.ProfilePicture.file_url)}
							<img class="profilePicture" src="{$GLOBALS.current_user.ProfilePicture.file_url}" alt="[[Profile Picture:raw]]" />
						{else}
							<img class="profilePicture" src="{url file='main^user_small.png'}" alt="[[No Profile Picture:raw]]" />
						{/if}
						<span class="labelUserName">{$GLOBALS.current_user.user_name}</span>
					</a>
					<div class="myAccountDialogWrapper" style="display: none;">
						{module name="users" function="user_menu"}
					</div>
				</div>
				{extension_point name="modules\menu\apps\FrontEnd\ITopMenuItem" wrapperStartTag="<span class=\"basketTopMenuItem\">" wrapperEndTag="</span>"}
			{/if}
			{include file="miscellaneous^language_selector.tpl"}
		</div>
	</div>
</div>
<div class="fixedWidthBlock sloganBlock">
	<div class="slogan">
		{IncludeMainLogo}
	</div>
</div>
<div class="fixedWidthBlock globalErrorWrapper">
	{extension_point name='modules\main\apps\FrontEnd\IGlobalErrorDisplayer' HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}
</div>
{include file="miscellaneous^dialog_window.tpl"}
<script type="text/javascript">
	function expandCollapseMenuOnHover(menuSwitchSelector, menuSelector) {
		this.menuSwitch = $(menuSwitchSelector);
		this.menu = $(menuSelector);
		this.timer = false;

		this.fnExpandMenu = function() {
			if (this.timer) {
				window.clearTimeout(this.timer);
				this.timer = false;
			}
			else {
				this.menuSwitch.toggleClass("expanded");
				this.menu.show();
				this.menu.mouseenter({ obj: this}, function(event) {
					if (event.data.obj.timer) {
						window.clearTimeout(event.data.obj.timer);
						event.data.obj.timer = false;
					}
				});
				this.menu.mouseleave({ obj: this}, function(event) {
					event.data.obj.fnCollapseMenu();
				})
			}
			return false;
		}
		this.fnCollapseMenu = function() {
			$.proxy(
				this.timer = window.setTimeout(
					$.proxy(function() {
						this.timer = false;
						this.menu.hide();
						this.menuSwitch.toggleClass("expanded");
						this.menu.unbind('mouseenter');
						this.menu.unbind('mouseleave');
					},this),
					250
				),
				this
			);
			return false;
		}
			
		this._init = function() {
			if (this.menuSwitch.length == 0 || this.menu.length == 0) {
				return false;
			}
			var switchPosition = this.menuSwitch.position();
			var outerHeight = this.menuSwitch.outerHeight();
			this.menu.css({
				position: "absolute",
				top: (switchPosition.top + outerHeight) + "px",
				left: switchPosition.left + "px"
			});
			
			this.menuSwitch.mouseenter({ obj: this}, function(event) {
				event.data.obj.fnExpandMenu();
				return false;
			});
			this.menuSwitch.mouseleave({ obj: this}, function(event) {
				event.data.obj.fnCollapseMenu();
				return false;
			})
		}
		
		this._init();
	}
		
	function expandCollapseMenuOnClick(menuSwitchSelector, menuSelector) {
		this.menuSwitch = $(menuSwitchSelector);
		this.menu = $(menuSelector);
			
		this._init = function() {
			if (this.menuSwitch.length == 0 || this.menu.length == 0) {
				return false;
			}
			var switchPosition = this.menuSwitch.position();
			var outerHeight = this.menuSwitch.outerHeight();
			this.menu.css({
				position: "absolute",
				top: (switchPosition.top + outerHeight) + "px",
				left: switchPosition.left + "px"
			});
			
			this.menuSwitch.click({ obj: this}, function(event) {
				$(this).toggleClass("expanded");
				event.data.obj.menu.slideToggle("fast");
				return false;
			})
		}
		
		this._init();
	}
					
	$(document).ready(function(){
		new expandCollapseMenuOnClick(".loginMenuWrapper .loginMenu .loginDialogControl", ".loginMenuWrapper .loginDialogWrapper");
		new expandCollapseMenuOnHover(".userGreeting .myAccount", ".userGreeting .myAccountDialogWrapper");
	});
</script>
