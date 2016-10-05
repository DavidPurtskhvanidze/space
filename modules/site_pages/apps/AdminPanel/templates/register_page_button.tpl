<form action='{page_path id='site_pages'}' method=POST>
	<input type='hidden' name='module' value={$pageInfo.module}>
	<input type='hidden' name='function' value={$pageInfo.function}>
	<input type='hidden' name='application_id' value='FrontEnd'>
	<input type='hidden' name='action' value='new_page'>
	<input type='hidden' name='parameters' value="{$pageInfo.parameters}">
	<table class='table'>
		<tr
			<td><input type='submit' value='Create a Page for this {$caption}' class="btn btn-default"></td>
		</tr>
	</table>
</form>
