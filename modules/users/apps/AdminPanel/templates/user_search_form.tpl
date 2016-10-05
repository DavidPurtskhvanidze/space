<div class="searchForm searchFormUsers">
	<div class="breadcrumbs">
    <ul class="breadcrumb">
      <li>[[Users]]</li>
    </ul>
  </div>
  <div class="page-content">
    <div class="page-header">
      <h1 class="lighter">[[Users]]</h1>
    </div>
      <a class="btn btn-link" href="{page_path module='users' function='add_user'}">[[Add a New User]]</a>

    <div class="widget-box no-border">
      <div class="widget-header header-color-dark">
        <h4 class="white" title="[[Click to hide the search form:raw]]">
					<a data-action="collapse" href="#">
						<i class="icon-chevron-up"></i> [[Search Users]]
					</a>
				</h4>
      </div>
      <div class="widget-body">
        <div class="widget-main padding-6 no-padding-left no-padding-right">
          <form name="search_form" id="SearchUsersForm" class="form form-horizontal">
          <input type="hidden" name="action" value="search" />
            <div class="form-group">
              <label class="col-sm-3 control-label">
                [[User ID]]
              </label>
              <div class="col-sm-6">
                {search property="sid" template="string.tpl"}
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">
                [[Username]]
              </label>
              <div class="col-sm-6">
                {search property="username" template="string_with_autocomplete.tpl"}
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">[[Email]]</label>
              <div class="col-sm-6">{search property="email" template="string_with_autocomplete.tpl"}</div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">[[User Group]]</label>
              <div class="col-sm-6">{search property="user_group"}</div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">[[Membership Plan]]</label>
              <div class="col-sm-6">
                  {search property="with_membership_plan"}
                <div class="space-4"></div>
                <div class="checkbox">
                  <label>{search property="without_membership_plan" template="is_null.tpl"} [[or]] [[without Membership Plan (not subscribed users, or the users with the expired subscription)]]</label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">[[Registration Date]]</label>
              <div class="col-sm-6">{search property="registration_date"}</div>
            </div>

              {foreach from=$templateFormProperties item=formProperty}
                <div class="form-group">
                  <label class="col-sm-3 control-label">[[{$formProperty.caption}]]</label>
                  {if empty($formProperty.template)}
                    <div class="col-sm-6">{search property=$formProperty.id}</div>
                  {else}
                    <div class="col-sm-6">{search property=$formProperty.id template=$formProperty.template}</div>
                  {/if}
                </div>
              {/foreach}
              <div class="clearfix form-actions">
                  <input type="submit" value="Search" class="btn btn-default">
              </div>
            </form>
        </div>
      </div>
        {include file="miscellaneous^toggle_search_form_js.tpl"}
        <script type="text/javascript">
          $(document).ready(function(){
            var searchWithoutMembershipPlans = $("input[name='without_membership_plan[is_null]']");
            var searchWithMembershipPlans = $("select[name='with_membership_plan[equal]']");

            searchWithMembershipPlans.prop('disabled', searchWithoutMembershipPlans.prop('checked'));
            searchWithoutMembershipPlans.change(function(){
              searchWithMembershipPlans.prop('disabled', searchWithoutMembershipPlans.prop('checked'));
            });

						$('.widget-box').filterState('manageUsers');
          });
        </script>
      </div>
    </div>
</div>
