<div class="dataTables_length">
	<form method="get" action="" class="form-inline">
		<input type="hidden" name="action" value="restore" />
		<input type="hidden" name="searchId" value="{$search.id}"/>
		<input type="hidden" name="page" value="1" />
      <label>[[Number of records per page]]</label>
      <select name="items_per_page" onchange="this.form.submit()" size="1">
        <option value="10" {if $search.objects_per_page == 10}selected{/if}>10</option>
        <option value="20" {if $search.objects_per_page == 20}selected{/if}>20</option>
        <option value="50" {if $search.objects_per_page == 50}selected{/if}>50</option>
        <option value="100" {if $search.objects_per_page == 100}selected{/if}>100</option>
      </select>
	</form>
</div>
