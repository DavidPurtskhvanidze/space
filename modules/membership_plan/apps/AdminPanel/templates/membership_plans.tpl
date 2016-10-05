<div class="membershipPlans">
	<div class="breadcrumbs">
    <ul class="breadcrumb">
      <li>[[Membership Plans]]</li>
    </ul>
  </div>

  <div class="page-content">
    <div class="page-header">
      <h1>[[Membership Plans]]</h1>
    </div>
    <a class="btn btn-link" href="{page_path id='membership_plan_add'}">[[Add a New Membership Plan]]</a>
    <div class="row">
      {include file="messages.tpl"}
      {display_success_messages}
      {display_error_messages}
      <div class="row">
				{assign var='plansCount' value=count($membershipPlans)}
				{if $plansCount != '0'}
                <div class="col-xs-3 col-sm-3 pricing-span-header {if $plansCount > 5}hidden{/if}">
					<div class="widget-box transparent">
						<div class="widget-header">
							<h5 class="bigger lighter"></h5>
						</div>
						<div class="widget-body">
							<div class="widget-main no-padding">
								<ul class="list-unstyled list-striped pricing-table-header">
									<li>[[Type]]</li>
									<li>[[Subscription Period]]</li>
									<li>[[Description]]</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="{if $plansCount > 5}col-xs-12 col-sm-12{else} col-xs-9 col-sm-9{/if} pricing-span-body">
					{foreach from=$membershipPlans item=membershipPlan}
						<div class="pricing-span pricing-span-{$plansCount}" data-item-sid="{$membershipPlan.sid}">
							<div class="widget-box pricing-box-small">
								<div class="widget-header header-color-blue">
									<div class="bigger lighter">[[{$membershipPlan.name}]]</div>
								</div>
								<div class="widget-body">
									<div class="widget-main no-padding">
										<ul class="list-unstyled list-striped pricing-table">
											<li>[[{$membershipPlan.type.value}]]</li>
											<li>[[{$membershipPlan.subscription_period.value}]] [[days]]</li>
											<li class="mpDescription">[[{$membershipPlan.description.value}]]</li>
										</ul>
										<div class="price">
											<span class="label label-lg label-inverse arrowed-in arrowed-in-right">
												{display_price_with_currency amount=$membershipPlan.price.value}
											</span>
										</div>
									</div>
									<div>
										<div class="btn-group btn-group-justified">
											<a data-sid="{$membershipPlan.sid}" class="itemControls edit btn btn-block btn-inverse" href="{page_path id='membership_plan_edit'}?sid={$membershipPlan.sid}" title="[[Edit]]">
												<i class="icon-edit bigger-110"></i>
											</a>
											{if $membershipPlan.quantity_of_contracts == '0'}
												<a class="itemControls delete btn btn-block btn-inverse" href="{page_path id='membership_plans'}?action=delete&membership_plan_sid={$membershipPlan.sid}" onClick="return confirm('[[Are you sure you want to delete this membership plan?:raw]]');" title="[[Delete:raw]]">
													<i class="icon-trash bigger-110"></i>
												</a>
											{/if}
										</div>
									</div>
								</div>
							</div>
						</div>
					{/foreach}
				</div>
            {/if}
      </div>

      <div class="alert alert-info">
        <p>[[Membership plans are used to define the ability for users to activate and re-activate listings. Two types of MP's are available: Fee Based and Subscription.]]</p>
        <p>[[Fee Based allows to re-activate listings at any time while the membership plan is active. Fee Based MP expires, but it does not de-activate the listings created under it. Subscription MP expires and de-activates all listings created under it.]]</p>
        <p>[[Please note that MP's are tied up with user's profile and each user can select and use only one MP per profile.]]</p>
        <p>[[Please carefully formulate membership plan terms. When you create a new MP, you will have to add it to one or more User Group(s) so that users can select them.]]</p>
        <p>[[Please also refer to the following sections of User Manual to learn more on how Membership Plans and their Listing Packages work:]]
          <ul>
            <li><a class="btn btn-link" href="{$GLOBALS.site_url}/../doc/UserManual/membership_plans.htm">[[What are Membership Plans and Listing Packages]]</a></li>
            <li><a class="btn btn-link" href="{$GLOBALS.site_url}/../doc/UserManual/managing_membership_plans.htm">[[Common description for Membership Plans and Listing Packages configuration]]</a></li>
          </ul>
        </p>
      </div>
    </div>
  </div>
</div>
