<div class="breadcrumbs">
    <ul class="breadcrumb">
        <li><a href="{page_path id='membership_plans'}">[[Membership Plans]]</a> &gt;
	<a href="{page_path id='membership_plan_edit'}?sid={$membershipPlan.sid}">[[$membershipPlan.name]]</a> &gt; [[Edit Package]]</li>
    </ul>
</div>
 <div class="page-content">
        <div class="page-header">
            <h1 class="lighter">[[Edit Package]]</h1>
        </div>
        
{include file='package_form_fields.tpl'}

{capture assign="returnBackParam"}{page_uri id='membership_plan_edit'}?sid={$membershipPlan.sid}{/capture}
{capture assign="returnBackParam"}return_uri={$returnBackParam|urlencode}{/capture}
     {if $packageClassName eq 'SubDomainPackage'}
         <a class="btn btn-link" href="{page_path id='membership_plan_packages'}?action=aplly_to_subdomains&package_sid={$packageSID}&{$returnBackParam}" onClick="return confirm('[[Are you sure you want to synchronize this package with subdomain packages?:raw]]');" title="[[Synchronize with subdomain packages:raw]]" id="sync">[[Apply to the subdomains]]</a><br />
     {else}
         <a class="btn btn-link" href="{page_path id='membership_plan_packages'}?action=aplly_to_listings&package_sid={$packageSID}&{$returnBackParam}" onClick="return confirm('[[Are you sure you want to synchronize this package with listing packages?:raw]]');" title="[[Synchronize with listing packages:raw]]" id="sync">[[Apply to the listings]]</a><br />
     {/if}
    {if $membershipPlan.type == "Subscription"}
        <a class="btn btn-link" href="{page_path id='membership_plan_packages'}?action=aplly_to_contracts&package_sid={$packageSID}&{$returnBackParam}" onClick="return confirm('[[Are you sure you want to synchronize this package with users contracts?:raw]]');" title="[[Synchronize with contract packages:raw]]" id="sync_contracts">[[Apply to user contracts]]</a>
    {/if}
 </div>
{require component="jquery" file="jquery.js"}
<script>
{literal}
$(document).ready(function(){
    $('form input,textarea').change(function(){$("#sync").hide();$("#sync_contracts").hide();});
});
{/literal}
</script>
