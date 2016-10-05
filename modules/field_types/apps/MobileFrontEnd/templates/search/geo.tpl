{capture name="select_box_field_distance" assign="select_box_field_distance"}
	{include file="field_types^search/geo.distance.tpl"}
{/capture}

{capture name="input_text_field_location" assign="input_text_field_location"}
	{include file="field_types^search/geo.location.tpl"}
{/capture}

[[$select_box_field_distance<br />
<span>of Zip</span> 
$input_text_field_location]]
