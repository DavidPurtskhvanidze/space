function TreeController()
{
	this.fieldId = null;
	this.treeData = [];
	this.controls = {};
}
TreeController.prototype.onChange = function(level)
{
	this.buildOptions(1 * level + 1, this.controls[level].value);
	if (this.controls[level].dispatchEvent)
	{
		var customEvent = document.createEvent("CustomEvent");
		customEvent.initEvent("onChildChanged",true,true);
		this.controls[level].dispatchEvent(customEvent);
	}
	else if(document.createEventObject) // IE < 9
	{
		var customEvent = document.createEventObject(window.event);
		this.controls[level].fireEvent("onChildChanged", customEvent);
	}
}
TreeController.prototype.buildOptions = function(level, id)
{
	if (this.controls[level] == undefined)
		return;
	var targetSelectBox = this.controls[level];
	targetSelectBox.options.length = 1;
	if (this.treeData[this.fieldId][id] != undefined)
	{
		for (var key in this.treeData[this.fieldId][id])
		{
			targetSelectBox.options.add(new Option(this.treeData[this.fieldId][id][key]['caption'], this.treeData[this.fieldId][id][key]['value']));
		}
	}
	this.onChange(level);
}
TreeController.prototype.setFieldId = function(fieldId)
{
	this.fieldId = fieldId;
}
TreeController.prototype.setTreeData = function(treeData)
{
	this.treeData = treeData;
}
TreeController.prototype.bindSelectBox = function(selectBox, level)
{
	this.controls[level] = selectBox;
	var controller = this;
	selectBox.onchange = function()
	{
		controller.onChange(level);
	};
}

function TreeControlRegistry()
{
	this.forms = [];
	this.treeData = {};
}
TreeControlRegistry.prototype.extractLevelFromControl = function(selectBox)
{
	var m = selectBox.name.match(/\w+(\[tree\])?\[(\d+)\]/);
	return m != null ? m[2] : null;
}
TreeControlRegistry.prototype.bindTreeData = function(fieldId, treeData)
{
	this.treeData[fieldId] = treeData;
}
TreeControlRegistry.prototype.bindControl = function(fieldId, selectBox)
{
	var level = this.extractLevelFromControl(selectBox);
	if (level == null) return;
	this.getTreeController(selectBox.form, fieldId).bindSelectBox(selectBox, level);
}
TreeControlRegistry.prototype.getTreeController = function(form, fieldId)
{
	if (form.treeControllers == undefined)
	{
		form.treeControllers = {};
		this.forms.push(form);
	}
	if (form.treeControllers[fieldId] == undefined)
	{
		treeController = new TreeController();
		treeController.setFieldId(fieldId);
		treeController.setTreeData(this.treeData);
		form.treeControllers[fieldId] = treeController;
	}
	return form.treeControllers[fieldId];
}

if (document.globalTreeControlRegistry == undefined)
{
	document.globalTreeControlRegistry = new TreeControlRegistry();
}
