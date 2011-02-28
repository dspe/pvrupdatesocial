<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$site.http_equiv.Content-language|wash}" lang="{$site.http_equiv.Content-language|wash}">
<head>
	{include uri='design:page_head.tpl'}

{include uri='design:page_head_style.tpl'}
{include uri='design:page_head_script.tpl'}

	<style type="text/css">
		twitter_show{ldelim} background-color: white; {rdelim}
	</style>

</head>
<body>
{ezscript_require( array( 'ezjsc::jquery' ) )}

<div style="background-color: white;">
	{$module_result.content}
	<!--DEBUG_REPORT-->
</div>

</body>
</html>