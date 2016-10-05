<div class="breadcrumbs">
    <ul class="breadcrumb">
        <li><a href="{page_path id='membership_plans'}">[[Membership Plans]]</a> &gt;
            <a href="{page_path id='membership_plan_edit'}?sid={$membershipPlan.sid}">[[$membershipPlan.name]]</a> &gt; [[Add Package]]</li>
    </ul>
</div>
<div class="page-content">
    <div class="page-header">
        <h1>[[Choose Package Type]]</h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <h4>[[Please select a package type for the new package:]]</h4>
            <div class="space-14"></div>
            <ul class="package_types list-unstyled spaced">
                {foreach from=$package_types item=type}
                    <li class="bigger-120">
                        <i class="icon-double-angle-right"></i>
                        <a href="?membership_plan_sid={$membership_plan_sid}&class_name={$type.class_name}">[[PhrasesInTemplates!{$type.name}]]</a>
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
</div>
