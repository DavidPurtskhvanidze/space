<div class="breadcrumbs">
	<a href="{page_path id='membership_plans'}">[[Membership Plans]]</a> &gt;
	<a href="{$GLOBALS.site_url}/membership_plan/?id={$membership_plan_id}">[[$membership_plan_info.name]]</a> &gt; [[Edit Package]]
</div>

<h1>[[Edit Package]]</h1>

{$package_update_form_block}

{require component="jquery" file="jquery.js"}
<script>
{literal}
$(document).ready(function(){
    $('form input,textarea').change(function(){$("#sync").hide();$("#sync_contracts").hide();});
});
{/literal}
</script>
