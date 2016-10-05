<?php
/**
 *
 *    iLister v.7.5.0, (c) WorksForWeb 2005 - 2016
 *
 *    Package: iLister-7.5.0
 *    Tag: modular/projects/classifieds/tags/7.5.0@19890, 2016-06-17 13:38:22
 *
 *    This file is a part of iLister software
 *    and is protected by international copyright laws
 *
 */


namespace lib\WYSIWYG;

class WYSIWYGEditorProvider
{
	/**
	 * @var WYSIWYGWrapper
	 */
	var $editor;
	var $defaultEditor = '\lib\WYSIWYG\ckeditorWrapper';
	var $availableEditors = array
	(
		'ckeditor'	=> array
		(
			'name' => 'CKEditor',
			'class_name' => '\lib\WYSIWYG\ckeditorWrapper'
		),
		'none' => array
		(
			'name' => 'Simple TextArea',
			'class_name' => '\lib\WYSIWYG\textareaWrapper'
		),
	);

	function setType($type)
	{
		if (array_key_exists($type, $this->availableEditors))
		{
			$className = $this->availableEditors[$type]['class_name'];
			$this->editor = new $className;
		}
		else
		{
			$this->editor = new $this->defaultEditor;
		}
		$this->editor->setExternalComponentsDir(PATH_TO_ROOT . \App()->SystemSettings['VendorLibs']);
		$this->editor->setSiteUrl(\App()->SystemSettings['SiteUrl']);
		$this->editor->init();
	}
	function getEditorHTML($content = null, $parameters)
	{
		return $this->editor->getHTML($content, $parameters);
	}

}

class WYSIWYGWrapper
{
	protected $externalComponentsDir;
	protected $siteUrl;

	public function setExternalComponentsDir($externalComponentsDir)
	{
		$this->externalComponentsDir = $externalComponentsDir;
	}
	public function setSiteUrl($siteUrl)
	{
		$this->siteUrl = $siteUrl;
	}
	function setEditorPath($relativeEditorPath)
	{
		$this->editorDir = $this->externalComponentsDir . $relativeEditorPath;
	}
	function correctPath()
	{
		$path = $this->siteUrl . '/';
		return $path;
	}
	function init()
	{
	}
}

/**
 * Type of WYSIWYG editor, displays simple textarea
 * with specific name and content from function arguments
 */

class textareaWrapper extends WYSIWYGWrapper {

	function getHTML($content, $params) {
		$width = isset($params['width']) ? $params['width'] : '200px';
		$height = isset($params['height']) ? $params['height'] : '200px';
		$name = isset($params['name']) ? $params['name'] : '';
		$content = htmlspecialchars($content, ENT_COMPAT, 'UTF-8');
		$result = "<textarea name=\"$name\" style=\"width:$width; height:$height\">$content</textarea>";
		return $result;
	}
}


/**
 * Type of WYSIWYG editor, support form elements,
 * it's displayed at HTML source as frame
 */

class ckeditorWrapper extends WYSIWYGWrapper {

	function init()
	{
		$this->setEditorPath('ckeditor/ckeditor.js');
	}

	function getHTML($content, $params)
	{
		$width = isset($params['width']) ? $params['width'] : '200px';
		$height = isset($params['height']) ? $params['height'] : '200px';
		$name = isset($params['name']) ? $params['name'] : 'ckeditor0';
		$content = htmlspecialchars($content, ENT_COMPAT, 'UTF-8');
        $toolbars = [
            'Tiny' => '[
                        { 
                          name: \'basicstyles\', 
                          items: [\'Bold\', \'Italic\', \'Underline\', \'Strike\', \'Subscript\', \'Superscript\']
                        },
                        { 
                          name: \'paragraph\', 
                          items: [ 
                          \'NumberedList\', \'BulletedList\', 
                          \'-\', 
                          \'Outdent\', \'Indent\', 
                          \'-\', 
                          \'Blockquote\',
                          \'-\', 
                          \'JustifyLeft\', \'JustifyCenter\', \'JustifyRight\', \'JustifyBlock\',
                          \'-\', 
                          \'BidiLtr\', \'BidiRtl\' 
                          ] 
                        },
                        ]'
        ];
        $toolbarSet = (isset($params['ToolbarSet'])) ? $params['ToolbarSet'] : 'FullNoForms';
        $toolbar = isset($toolbars[$toolbarSet]) ? $toolbars[$toolbarSet] :  '\'FullNoForms\'';
		$editorConfigs = array(
			'width' => '\'' . $width . '\'',
			'height' =>'\'' . $height . '\'',
			'toolbar' => $toolbar,//(isset($params['ToolbarSet'])) ? $params['ToolbarSet'] : 'FullNoForms',
			'entities' => ! empty($params['entities']) ? '\'' . true . '\'': '\'' . false . '\'' ,
			'entities_latin' => ! empty($params['entities_latin']) ? '\'' . true . '\'' : '\'' . false . '\'',
			'ForceSimpleAmpersand' => ! empty($params['ForceSimpleAmpersand']) ? '\'' . true . '\'': '\'' . false . '\'',
		);

		/**
		 * @var ICKEditorExtraConfigs[] $CKEditorExtraConfigs
		 */
		$CKEditorExtraConfigs = new \core\ExtensionPoint('lib\WYSIWYG\ICKEditorExtraConfigs');
		foreach ($CKEditorExtraConfigs as $ext)
		{
			$configs = $ext->getConfigs();
			foreach ($configs as $configName => $value)
			{
				$editorConfigs[$configName] = '\'' . $value . '\'';
			}
		}
		\App()->ExternalComponents->requireComponent('ckeditor', 'ckeditor.js');

		$result = "";
		$result .= "<textarea id=\"{$name}\" name=\"{$name}\" style=\"width: {$width}; height: {$height};\">{$content}</textarea>";
		$result .= "<script type=\"text/javascript\">";
		$result .= "	$(document).ready(function(){\n";
		$result .= "		CKEDITOR.replace('{$name}', {\n";
		foreach ($editorConfigs as $configName => $value)
		{
			$result .= "			{$configName}:$value,\n";
		}
        $result = rtrim($result, ',');
		$result .= "		});\n";
		$result .= "	});";
		$result .= "</script>";
		
		return $result;
	}
}
