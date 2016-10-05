<h4 class="headerBlue">[[Listing Packages Included]]</h4>
{include file="messages.tpl" messages=$packageMessages}
{display_success_messages}
{display_error_messages}

<table class="items sortable table table-striped" data-sorting-url="{page_path module='membership_plan' function='set_package_display_order'}?membership_plan_sid={$membershipPlanSID}">
	<thead>
    <tr class="head">
      <th>[[Name]]</th>
      <th>[[Description]]</th>
      <th>[[# of Listings]]</th>
      <th colspan="2">[[Actions]]</th>
    </tr>
  </thead>
  <tbody>
    {capture assign="returnBackParam"}{page_uri id='membership_plan_edit'}?sid={$membershipPlanSID}{/capture}
    {capture assign="returnBackParam"}return_uri={$returnBackParam|urlencode}{/capture}
    {foreach from=$packages item=package}
      <tr data-item-sid="{$package.sid}">
        <td>[[{$package.name}]]</td>
        <td>[[{$package.description}]]</td>
        <td>{$package.number_of_listings}</td>
        <td>
			<a class="itemControls edit btn btn-xs btn-info" href="{page_path id='membership_plan_package_edit'}?sid={$package.sid}" title="[[Edit:raw]]">
				<i class="icon-edit"></i>
			</a>
        	<a class="itemControls delete btn btn-xs btn-danger" href="{page_path id='membership_plan_packages'}?action=delete&package_sid={$package.sid}&{$returnBackParam}" onClick="return confirm('[[Are you sure you want to delete this package?:raw]]');" title="[[Delete:raw]]">
				<i class="icon-trash"></i>
			</a>
        	<a class="itemControls applyToListings btn btn-xs btn-success" href="{page_path id='membership_plan_packages'}?action=aplly_to_listings&package_sid={$package.sid}&{$returnBackParam}" onClick="return confirm('[[Are you sure you want to synchronize this package with listing packages?:raw]]');" title="[[Synchronize with listing packages:raw]]">
				<i class="icon-list"></i>
        	</a>
          {if $membershipPlanType == 'Subscription'}
            <a class="itemControls applyToContracts btn btn-xs " href="{page_path id='membership_plan_packages'}?action=aplly_to_contracts&package_sid={$package.id}&{$returnBackParam}" onClick="return confirm('[[Are you sure you want to synchronize this package with contracts?:raw]]');" title="[[Synchronize with contract packages:raw]]">
				<i class="icon-user"></i>
            </a>
          {/if}
        </td>
        <td class="sort">
          <span title="[[Drag and drop to change the order:raw]]">
            <i class="icon-sort"></i>
          </span>
        </td>
      </tr>
    {/foreach}
  </tbody>
</table>
{include file="miscellaneous^sortable_js.tpl"}

