{require component="jquery" file="jquery.js"}
<script type="text/javascript">

var chooseModuleLabel = '[[Choose module:raw]]';
var chooseModuleFunctionLabel = '[[Choose function:raw]]';
var removeAdditionalParamLabel = '[[Remove:raw]]';
var additionalParamDeletionConfirmationLabel = '[[Commit deletion?:raw]]';

var modulesAndFunctionsData = {$modulesAndFunctionsData};
var currentSitePageModule = '{$sitePageInfo.module}';
var currentSitePageModuleFunction = '{$sitePageInfo.function}';
var currentSitePageParamValues = {$sitePageInfo.parameters};
var lastAddedParam = 0;

function resetModuleFunctionParams() {
	$('#ModuleFunctionParamsContainer').empty();
	$('#AddNewParamSwitch').prop("disabled", true);
}

function initModuleSelector(selectedOption) {
	var selector = $('#ModuleSelector');
	selector.empty();

	selector.append(new Option(chooseModuleLabel, ''));
	$.each(modulesAndFunctionsData, function(key, value) {
		selector.append(new Option(key, key));
	});

	if (selectedOption) {
		selector.val(selectedOption);
	}
}

function initModuleFunctionSelector(selectedOption) {
	var functionParams = modulesAndFunctionsData[($('#ModuleSelector').val())];
	var selector = $('#ModuleFunctionSelector');
	selector.empty();

	selector.append(new Option(chooseModuleFunctionLabel, ''));
	if (functionParams) {
		$.each(functionParams, function(key, value) {
			selector.append(new Option(key, key));
		});
	}

	if (selectedOption) {
		selector.val(selectedOption);
	}
}

function initModuleFunctionParams() {
	if ($('#ModuleFunctionSelector').val() == '') {
		return;
	}
	$('#AddNewParamSwitch').prop("disabled", false);

	var params = modulesAndFunctionsData[($('#ModuleSelector').val())][$('#ModuleFunctionSelector').val()];

	$.each(params, function(key, paramName) {
		var controlsId = 'ParamId_' + paramName;

		$('<div />', {
			'class':'parameter row paddingTop'
		})
		.append(
			$('<label />', {
				'class'	: 'paramLabel middle col-sm-5',
				'for'	: controlsId,
				'text'	: paramName
			})
		)
		.append(
			$('<div />', {
				'class'	: 'paramControl col-sm-7 ' + paramName
			})
			.append(
				$('<input />', {
					'id'	: controlsId,
					'type'	: 'text',
					'name'	: 'parameters[' + paramName + ']',
					'class'	: 'form-control',
					'value'	: (currentSitePageParamValues[paramName] === undefined) ? '' : currentSitePageParamValues[paramName]
				})
                    )
                )
		.appendTo("#ModuleFunctionParamsContainer");
	});
}

var lastAddedParam = 0;
function createModuleFunctionExtraParam(labelVal, valueVal) {
	++lastAddedParam;
	var paramWrapperId = 'AddedParamWrapper' + lastAddedParam;

	$('<div />', {
		'class'	: 'additionalParameter',
		'id'	: paramWrapperId
	})
	.append(
		$('<div />', {
			'class'	:'paramNameControl col-sm-5 no-padding'
		})
		.append(
			$('<input />', {
				'name'	: 'additionalParameterNames[]',
				'class'	: 'form-control',
				'value'	: labelVal
			})
		)
	)
	.append(
		$('<div />', {
			'class'	:'paramValueControl',
            'style'	:'margin-top:5px'
		})
		.append(
			$('<input />', {
				'name'	: 'additionalParameterValues[] col-sm-6',
				'class'	: 'form-control',				
				'value'	: valueVal,
				'style'	:'left:5px;position:relative;'
			})
		)
	)
	.append(
		$('<div />', {
			'class'	: 'paramActions'
		})
		.append(
			$('<a />', {
				'href'		: '#',
				'text' : removeAdditionalParamLabel
			})
			.bind(
				'click',
				{ 'paramWrapperId': paramWrapperId },
				function (event) {
					if (confirm(additionalParamDeletionConfirmationLabel)) {
						$('#' + event.data.paramWrapperId).remove();
					}
					return false;
				}
			)
		)
	)
	.appendTo("#ModuleFunctionParamsContainer");
}

function initModlueFunctionExtraParams() {
	if ($('#ModuleSelector').val() === currentSitePageModule &&  $('#ModuleFunctionSelector').val() === currentSitePageModuleFunction) {

		if (modulesAndFunctionsData[($('#ModuleSelector').val())] != undefined) {
			var params = modulesAndFunctionsData[($('#ModuleSelector').val())][$('#ModuleFunctionSelector').val()];
		}

		$.each(currentSitePageParamValues, function(name, value) {
			if ($.inArray(name, params) == -1) {
				createModuleFunctionExtraParam(name, value);
			}
		});
	}
}

$(document).ready(function() {
	$('#ModuleSelector').bind(
		'change', 
		function() {
			resetModuleFunctionParams();
			initModuleFunctionSelector(false);
		}
	);
	$('#ModuleFunctionSelector').bind(
		'change', 
		function() {
			resetModuleFunctionParams();
			initModuleFunctionParams();
			initModlueFunctionExtraParams();
		}
	);
	$('#AddNewParamSwitch').bind(
		'click', 
		function() {
			createModuleFunctionExtraParam('', '');
		}
	);
	resetModuleFunctionParams();
	initModuleSelector(currentSitePageModule);
	initModuleFunctionSelector(currentSitePageModuleFunction);
	initModuleFunctionParams();
	initModlueFunctionExtraParams();
});
</script>

<div class="sitePageForm">

	{display_error_messages}

	<form method="post" class="form form-horizontal">
        {CSRF_token}
		<input type="hidden" name="action" value="save" />
		<input type="hidden" name="oldPageId" value="{$oldPageId}" />
		<input type="hidden" name="application_id" value="{$applicationId}" />

    <div class="form-group">
        <label class="col-sm-3 control-label">
            [[Id]]
        </label>
        <div class="col-sm-6">
            <input type="text" name="id" value="{$sitePageInfo.id}" class="form-control">
        </div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label">
        [[URI]]
      </label>
      <div class="col-sm-6">
        <input type="text" name="uri" value="{$sitePageInfo.uri}" class="form-control">
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label">[[Pass parameters via URI]]</label>
      <div class="col-sm-6">
          <div class="checkbox">
              <input type="hidden" name="pass_parameters_via_uri" value="0">
              <label>
                <input class="ace ace-switch ace-switch-6" type="checkbox" name="pass_parameters_via_uri" {if $sitePageInfo.pass_parameters_via_uri} checked {/if} value="1">
                <span class="lbl"></span>
              </label>
          </div>
      </div>
    </div>

	<div class="form-group">
		<label class="col-sm-3 control-label">[[No Index]]</label>
		<div class="col-sm-6">
			<div class="checkbox">
				<input type="hidden" name="no_index" value="0">
				<label>
					<input class="ace ace-switch ace-switch-6" type="checkbox" name="no_index" {if $sitePageInfo.no_index} checked {/if} value="1">
					<span class="lbl"></span>
				</label>
			</div>
		</div>
	</div>
			
    {foreach from=$extraFields item='field'}
        
      <div class="form-group">
        <label for="" class="col-sm-3 control-label">[[{$field->getCaption()}]]</label>
        {$fieldValue=$sitePageInfo[$field->getId()]}
        <div class="col-sm-6">
           {if $extraFields.id = 'include_in_sitemap'}
                <div class="checkbox">
                    <input type="hidden" name="include_in_sitemap" value="0">
                    <label>
                      <input class="ace ace-switch ace-switch-6" type="checkbox" name="include_in_sitemap" {if $sitePageInfo.include_in_sitemap} checked {/if} value="1">
                      <span class="lbl"></span>
                    </label>
                </div>   
           {else}
           [[{$field->getInputBox($fieldValue)}]]
           {/if}
        </div>
      </div>
    {/foreach}

    <div class="form-group">
      <label class="col-sm-3 control-label">[[Title]]</label>
      <div class="col-sm-6"><input type="text" name="title" value="{$sitePageInfo.title}" class="form-control"></div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label">[[Template]]</label>
      <div class="col-sm-6"><input type="text" name="template" value="{$sitePageInfo.template}" class="form-control"></div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label">[[Module]]</label>
      <div class="col-sm-6"><select name="module" id="ModuleSelector" class="form-control"></select></div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label">[[Function]]</label>
      <div class="col-sm-6"><select name="function" id="ModuleFunctionSelector" class="form-control"></select></div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label">[[Parameters]]</label>
      <div class="col-sm-6">
        <div id="table_params">
          <div id="ModuleFunctionParamsContainer" class='moduleFunctionParamsContainer'></div>
          <div class="space-4"></div>
          <input type="button" value="[[Add parameter:raw]]" id="AddNewParamSwitch" class="btn btn-default">
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label">[[Keywords]]</label>
      <div class="col-sm-6"><textarea name="keywords" class="form-control">{$sitePageInfo.keywords}</textarea></div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label">[[Description]]</label>
      <div class="col-sm-6"><textarea name="description" class="form-control">{$sitePageInfo.description}</textarea></div>
    </div>

		<div class="clearfix form-actions">
		  <input type="submit" value="[[Save:raw]]" class="btn btn-default">
    </div>

	</form>
</div>
