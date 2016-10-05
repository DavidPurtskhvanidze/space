<div class="breadcrumbs">
  <ul class="breadcrumb">
    <li><a href="{page_path id='membership_plans'}">[[Membership Plans]]</a></li>
    <li>{$membershipPlanName}</li>
  </ul>
</div>

<div class="page-content">
  <div class="page-header">
    <h1>[[Edit Membership Plan]]</h1>
  </div>

  <div class="row">
    <h4 class="headerBlue">[[Membership Plan Info]]</h4>

    {include file='membership_plan_form_fields.tpl'}

    <a class="btn btn-link" href="{page_path id='membership_plan_package_add'}?membership_plan_sid={$membershipPlanSID}">[[Add a New Package]]</a>
    {foreach $grouped_packages as $grouped_package}
      {include file="{$grouped_package.typeData.packages_template}" packages=$grouped_package.packages}
      <hr />
    {/foreach}
  </div>
</div>
