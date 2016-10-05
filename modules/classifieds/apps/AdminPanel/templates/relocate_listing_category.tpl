{if $action_done}
<script>
	var url = window.location.href;
	url = url.replace(/&message=([a-z0-9_]+)/i, '');
	url = url.replace(/&dest_cat_id=([0-9]+)/i, '');
	window.location.href = url;
</script>
{/if}

{display_error_messages}
<div class="RelocateBlock">
<form class="form form-horizontal" action="">
	<input type="hidden" name="action" value="set_child_of"/>
	<input type="hidden" name="relocating_category_sid" value="{$relocating_category.sid}"/>
    <div class="form-group">
        <label class="control-label col-sm-6">
          [[Set this category as a child of]]
        </label>
        <div class="col-sm-4">
          <select name="dest_category_sid" class="form-control">
                {if $grandparent_category != null}
                    <option value="{$grandparent_category.sid}">{$grandparent_category.id}</option>
                {/if}
                {foreach from=$subcategories_of_current_listing_category item=category}
                    {if $category.sid != $relocating_category.sid}
                        <option value="{$category.sid}">{$category.id}</option>
                    {/if}
                {/foreach}
            </select>
            
        </div>
            <div class="col-sm-2"><input type="submit" class="btn btn-default RelocateCategory" value="[[Set:raw]]" /></div>
      </div>
</form>



<form class="form form-horizontal" method="post" action="">
{CSRF_token}
<input type="hidden" name="action" value="move"/>
<input type="hidden" name="relocating_category_sid" value="{$relocating_category.sid}"/>
    <div class="form-group">
        <label class="control-label col-sm-4">
          [[Move this category]]
        </label>
        <div class="col-sm-3">
            <select name="position" class="form-control">
                <option value="before">[[before:raw]]</option>
                <option value="after">[[after:raw]]</option>
            </select>
        </div>
        <div class="col-sm-3">
            <select name="dest_category_sid" class="form-control">
                {foreach from=$subcategories_of_current_listing_category item=category}
                    {if $category.sid != $relocating_category.sid}
                        <option value="{$category.sid}">{$category.id}</option>
                    {/if}
                {/foreach}
            </select>
        </div>
            <div class="col-sm-2"><input type="submit" class="btn btn-default RelocateCategorySet" value="[[Set:raw]]" /></div>
      </div>
	
</form>
</div>
