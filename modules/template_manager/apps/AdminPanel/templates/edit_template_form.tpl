{require component="codemirror" file="lib/codemirror.js"}
{require component="codemirror" file="lib/codemirror.css"}
{require component="codemirror" file="mode/xml/xml.js"}
{require component="codemirror" file="mode/javascript/javascript.js"}
{require component="codemirror" file="mode/css/css.js"}
{require component="codemirror" file="mode/htmlmixed/htmlmixed.js"}
{require component="codemirror" file="mode/smarty/smarty.js"}
{require component="codemirror" file="mode/smartymixed/smartymixed.js"}

{if $templateIsEditable}
<div class="col-sm-8">
    <form action="" method="post" class="form form-horizontal">
      {CSRF_token}
      <input type="hidden" name="application_id" value="{$appId}">
      <input type="hidden" name="template" value="{$templateName}">
      {if isset($moduleTemplateProvider)}
        <input type="hidden" name="moduleTemplateProviderId" value="{$moduleTemplateProvider->getId()}">
      {/if}
      <input type="hidden" name="action" value="save">

      <div class="template">
        <div class="form-group">
          <textarea name="template_content" id="template_content" class="form-control">{$templateContent|escape}</textarea>
        </div>
        <input type="submit" value="[[Save:raw]]" class="btn btn-default">
      </div>
    </form>
</div>

<div class="col-sm-4">
  <div class="codeInsertForm">
      <form>
        <table class="table table-no-border">
          <tr>
            <td>[[Module]]</td>
            <td>
              <select name="moduleName" class="form-control">
                <option value="">[[Choose module]]:</option>
              {foreach from=$modulesAndFunctionsData key="moduleName" item="functions"}
                <option>{$moduleName}</option>
              {/foreach}
              </select>
            </td>
          </tr>
          <tr>
            <td>[[Function]]</td>
            <td>
              <select name="functionName" class="form-control">
                <option value="">[[Choose function]]:</option>
              </select>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <div id="params_caption">[[Parameters]]:</div>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <div id="table_params"></div>
            </td>
          </tr>
          <tr>
            <td colspan="2"><input type="submit" value="[[Insert:raw]]" class="btn btn-default"></td>
          </tr>
	      <tr>
		      <td>[[Colorize]]</td>
		      <td>
			      <select name="colorize" id="colorize">
				      {foreach $themeColors as $key => $colorize}
					      {if isset($colorize.className) && $colorize.className eq false}{continue}{/if}
					      <option value="{if !empty($colorize.className)}{$colorize.className}{else}{$key}{/if}">{$colorize.Caption}</option>
				      {/foreach}
			      </select>
		      </td>
	      </tr>
	      <tr>
		      <td colspan="2">
			      <a href="#" id="insertColorize" class="btn btn-default">[[Insert:raw]]</a>
		      </td>
	      </tr>

        </table>
      </form>
    </div>
  {else}
    <div class="template">
      <textarea name="template_content" id="template_content" disabled="disabled" class="form-control">{$templateContent|escape}</textarea>
    </div>
  {/if}
</div>

{require component="jquery" file="jquery.js"}
<script type="text/javascript">
	$(document).ready(function() {
		var readOnly = false;
		var disabled = $('#template_content').attr('disabled');
		if (disabled == 'disabled')
		var readOnly = 'nocursor';
		var myCodeMirror = CodeMirror.fromTextArea(document.getElementById('template_content'), {
			mode: 'smartymixed',
			smartyVersion  : 3,
			lineNumbers: true,
			lineWrapping: true,
			readOnly: readOnly
		});

		var modulesAndFunctionsData = {$modulesAndFunctionsData|json_encode};
		var $moduleSelector = $(".codeInsertForm select[name='moduleName']");
		var $functionSelector = $(".codeInsertForm select[name='functionName']");
		var $parametersDiv = $(".codeInsertForm #table_params");
		var $codeInsertForm = $(".codeInsertForm form");

		$moduleSelector.change(function() {
			$parametersDiv.html("");
			var selectedModuleName = $(this).val();
			$("option:not(option:first)", $functionSelector).remove();
			for (var functionName in modulesAndFunctionsData[selectedModuleName]) {
				$functionSelector.append($("<option />").attr("value", functionName).html(functionName));
			}
		});

		$functionSelector.change(function() {
			$parametersDiv.html("");
			var selectedModuleName = $moduleSelector.val();
			var selectedFunctionName = $(this).val();
			var $parametersTable = $("<table />");
			for (i in modulesAndFunctionsData[selectedModuleName][selectedFunctionName]) {
				var parameterName = modulesAndFunctionsData[selectedModuleName][selectedFunctionName][i];
				var $inputBox = $("<input />").attr("name", parameterName);
				var $row = $("<tr />");
				$("<td />").text(parameterName).appendTo($row);
				$("<td />").html("&nbsp;=&nbsp;").appendTo($row);
				$("<td />").append($inputBox).appendTo($row);
				$parametersTable.append($row);
			}
			$parametersDiv.append($parametersTable);
		});

		$codeInsertForm.submit(function() {

			var selectedModuleName = $moduleSelector.val();
			var selectedFunctionName = $functionSelector.val();
			if (selectedModuleName == "" || selectedFunctionName == "") {
				alert("[[Please select module and function:raw]]");
				return false;
			}

			var parametersString = "";
			var parametersData = $("input", this).serializeArray();
			for (i in parametersData) {
				parametersString += " " + parametersData[i].name + "=\"" + parametersData[i].value + "\"";
			}

			var codeToInsert = "{ldelim}module name=\"" + selectedModuleName + "\" function=\"" + selectedFunctionName + "\"" + parametersString + "{rdelim}";
			insertCode(codeToInsert);
			return false;
		});

		function insertCode(codeToInsert)
		{
			myCodeMirror.replaceSelection(codeToInsert);
			var tArea = document.getElementById("template_content");
			tArea.focus();
			if (document.selection) // IE
			{
				var s = document.selection.createRange();
				s.text = codeToInsert;
				s.select();
			}
			else
			{
				if (typeof(tArea.selectionStart) != "undefined")   // Mozilla
					cursor = tArea.selectionStart;
				else											   // other browser
					cursor = tArea.length;

				str = tArea.value;
				strBeg = str.substr(0, cursor);
				strEnd = str.substr(cursor, (str.length - cursor));

				scrTop = tArea.scrollTop;
				tArea.value = strBeg + codeToInsert + strEnd;
				tArea.scrollTop = scrTop;
			}
		}

		$codeInsertForm.find('#insertColorize').click(function(event){
			event.preventDefault();
			var colorizeClass = $codeInsertForm.find('select#colorize option:selected').val();
			if (colorizeClass == "")
			{
				alert("[[Please select colorize class:raw]]");
				return false;
			}

			var codeToInsert = colorizeClass;
			insertCode(codeToInsert);
			return false;
		})
	});
</script>
