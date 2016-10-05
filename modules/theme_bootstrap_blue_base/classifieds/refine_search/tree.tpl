{*By default the tree type value comes through request*}
{*This template uses tree_in value type so here the required tree_in value is obtained from tree value*}
{if isset($value.tree) and $value.tree.0 != ''}
	{$parentSID = $value.tree.0}
	{$value.tree_in.$parentSID = $value.tree}
{/if}

<input type="hidden" name="{$id}[tree][0]">
<input type="hidden" name="{$id}[tree_in]" class="treeItem">
<div class="treeField">

	{* ==== Rendering of tree elements obtained from request ==== *}
	<div class="selectedParentsAndChildren {$id}">
		{*Here we are looking for an tree item. It can be either parent or child*}
		{*We can't get it directly by its SID, so we take it's parent and searching for the element by iterating its parents children*}

		{foreach $value.tree_in as $key => $parentAndChildItem} {* $parentAndChildItem may contain parent and a child or just parent*}
			<div class="selectedFamily">
			{foreach $parentAndChildItem as $index => $value}{*getting the sid of element's parent SID*}
				{if $index == 0} {*if the element is a parent then "0" is returned*}
					{$parent = 0}
				{else}
					{$parent=$key} {*tree parents sid is stored as tree key*}
				{/if}
				{foreach $tree_values.$parent as $tree_value}{* for all of the children of the particular parent f.e. for all makes or for all models of particular make*}
					{if $value == $tree_value.sid} {*searching for element by it's SID*}
						<div class="removable"> {*as we find required element we add it to the form*}
							<input type="hidden" name="{$id}[tree_in][{$key}][{$index}]" class="treeItem" value="{$parentAndChildItem.$index}">
							<div class="caption{if $parent==0} parent{/if}" data-{if $parent==0}parent{else}child{/if}-caption="{tr mode="raw" domain="Property_$id"}{$tree_value.caption}{/tr}">{tr mode="raw" domain="Property_$id"}{$tree_value.caption}{/tr}</div>
							<div class="removeLinkContainer"><a href="#" class="removeAction{if $parent==0} parent{/if}" title="Remove"><i class="fa fa-remove"></i></a></div>
						</div>
					{/if}
				{/foreach}
			{/foreach}
		</div>
		{/foreach}
	</div>
	<a href="#" class="showAddMoreOptionsPopUp {$id}">[[Add more]]</a>

	{* ==== Add more elements pop up window ==== *}
	<div class="addMoreOptionsPopUp {$id} modal fade">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">[[Add Make & Model]]</h4>
				</div>
				<div class="modal-body">
					{for $index=0 to $tree_depth-1}
						<div class="form-group">
							<select class="searchTreeLevel{$index+1} form-control" name="{$id}[tree][{$index}]">
								<option value="">[[Any]] [[FormFieldCaptions!{$levels_captions.$index}:raw]]</option>
								{*{defining parent of the current selectbox}*}
								{if $index == 0}
									{assign var='parent' value=0}
								{else}
									{assign var='parentIndex' value=$index-1}
									{assign var='parent' value=$value.$parentIndex}
								{/if}
								{*{generating tree items based on parent}*}
								{foreach from=$tree_values.$parent item=tree_value}
									<option value="{$tree_value.sid|escape}">{$tree_value.caption}</option>
								{/foreach}
							</select>
						</div>
					{/for}
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary add">[[Add:raw]]</button>
				</div>
			</div>
		</div>
	</div>
</div>
{include file="field_types^tree_js.tpl"}
<script>
	$(function () {

		var $addMoreTreeItemPopup = $('.addMoreOptionsPopUp.{$id}');
		$addMoreTreeItemPopup.appendTo('body');

		var onAdd = function () { //Pop up has one button "Add"

			var newItemsWasAdded = false; //Will be a trigger to update or do not the Refine Search Form
			var parentsCaption = ''; // Will be used for tree children to obtain its parent info
			var parentsValue = ''; // Will be used for tree children to obtain its parent info
			for (var i = 1; i <= treeDepth; i++) { //Adding new element to form
				// Getting the info
				var value = $("select.searchTreeLevel" + i, $addMoreTreeItemPopup).val();
				var caption = $("select.searchTreeLevel" + i + " option:selected", $addMoreTreeItemPopup).text();
				var parent = "";
				if (value == "") {
					break;
				}
				var formElement = "";
				if (i == 1) { // is a parent
					parentsCaption = caption; //We'll need it later
					parentsValue = value; //We'll need it later
					if ($('[data-parent-caption="' + caption + '"]').size() == 0) { //If the element wasn't added before
						formElement = "<div class='removable'>"
						+ "<input type='hidden' class='treeItem' name='{$id}[tree_in][" + value + "][]' value='" + value + "'>"
						+ "<div class='caption parent' data-parent-caption='" + caption + "'>" + caption + "</div>"
						+ "<div class='removeLinkContainer'><a href='#' class='removeAction parent' title='[[Remove]]'><i class='fa fa-remove'></i></a></div></div>";
						$(".selectedParentsAndChildren.{$id}").append("<div class='selectedFamily'>" + formElement + "<div>");
						newItemsWasAdded = true;
					}
				} else { // if element is a child
					if ($('[data-parent-caption="' + parentsCaption + '"]').parents('.selectedFamily').has('[data-child-caption="' + caption + '"]').length == 0) { //Checking if this element is already on the form
						formElement = "<div class='removable'>"
						+ "<input type='hidden' class='treeItem' name='{$id}[tree_in][" + parentsValue + "][]' value='" + value + "'>"
						+ "<div class='caption' data-child-caption='" + caption + "'>" + caption + "</div>"
						+ "<div class='removeLinkContainer'><a href='#' class='removeAction " + parent + "' title='[[Remove]]'><i class='fa fa-remove'></i></a></div></div>";
						$('[data-parent-caption="' + parentsCaption + '"]').parents(".selectedFamily").append(formElement);
						newItemsWasAdded = true;
					}
				} // if (i == 1)
			} // for (var i = 1; i <= treeDepth; i++)
			$addMoreTreeItemPopup.modal('hide');
			if (newItemsWasAdded == true) {
				$("input.treeItem").first().change(); // Calling form change event for search results ajax reloading
			}
		};

		$('.add', $addMoreTreeItemPopup).click(onAdd);

		var treeDepth = {$tree_depth};
		$addMoreTreeItemPopup.modal({
			show: false
		});

		$(document).on('mouseenter', ".removeAction", function (event) { //Dot styling
			$(this).parents(".removable").addClass('hovered');
		});
		$(document).on('mouseleave', ".removeAction", function (event) { //Dot styling
			$(this).parents(".removable").removeClass('hovered');
		});
		$(document).on('click', ".removeAction", function (event) {
			if ($(this).hasClass("parent")) { // If removing caption is a parent tree caption
				$(this).parents(".selectedFamily").remove(); // itself and its child will be removed
			} else {
				$(this).parents(".removable").remove(); // otherwise the only selected element will be removed
			}
			$(".refineSearchForm input").first().change(); // Calling form change event for search results ajax reloading
			return false; // for removing element without page jumping
		});

		$(".showAddMoreOptionsPopUp.{$id}").click(function(e){
			e.preventDefault();
			$addMoreTreeItemPopup.modal('show');
		});

		var cleaner = new Object();
		cleaner.clearAllSelection = function () {
			$('.selectedParentsAndChildren.{$id}').empty();
		};

		resetObserver[resetObserver.length]=cleaner;
	})
</script>
