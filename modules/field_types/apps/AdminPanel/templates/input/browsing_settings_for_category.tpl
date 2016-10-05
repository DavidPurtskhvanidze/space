<h4 class="headerBlue">[[Browsing settings]]</h4>
 <table class="items sortable table table-striped table-hover">
    <thead>
     <tr class="head">
           <th>[[Level]]</th>
           <th>[[Field]]</th>
           <th colspan="3">[[Actions]]</th>
     </tr>
    </thead>
    <tbody>
	{foreach from=$browsing_settings_values item=setting_item name=items_block}
		<tr class="{cycle values="odd,even"}">
			<td>{$smarty.foreach.items_block.iteration}</td>
			<td>{$browsing_settings_options[$setting_item]}</td>
			<td>
				{if $smarty.foreach.items_block.iteration < $smarty.foreach.items_block.total}
					<a href="{page_path module='classifieds' function='category_settings'}?sid={$category.sid}&handler_move_browsing_setting={$setting_item}&dir=down" title="[[Move down]]"><img src="{url file='main^arrow_down.png'}" />
				{/if}
			</td>
			<td>
				{if $smarty.foreach.items_block.iteration > 1}
					<a href="{page_path module='classifieds' function='category_settings'}?sid={$category.sid}&handler_move_browsing_setting={$setting_item}&dir=up" title="[[Move up]]"><img src="{url file='main^arrow_up.png'}" />
				{/if}
			</td>
			<td>
				<a class="itemControls delete" href="{page_path module='classifieds' function='category_settings'}?sid={$category.sid}&handler_delete_browsing_setting={$setting_item}" onclick='return confirm("[[Are you sure that you want to delete this field?:raw]]")' title="[[Delete]]">[[Delete]]</a>
			</td>
		</tr>
	{foreachelse}
		<tr>
			<td colspan="5">[[No fields selected for browsing]]</td>
		</tr>
	{/foreach}
    </tbody>
</table>
<div class="browsingFieldSelector">
	<select multiple="multiple" size="4" name="new_browsing_setting[]" class="form-control">
		{foreach from=$browsing_settings_options key=key item=item}
			{if !in_array($key, $browsing_settings_values) || in_array($key, $tree_fields_ids)}
				<option value="{$key}">{$item}</option>
			{/if}
		{/foreach}
	</select>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="clearfix form-actions">
          <input type="submit" name='handler_add_browsing_setting' value="[[Add Browsing Level:raw]]" class="btn btn-default" />
        </div>
    </div>
</div>


