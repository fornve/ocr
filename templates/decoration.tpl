<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
	<title>{if $title}{$title}{else}OCR project - Image to text{/if}</title>
	<link rel="stylesheet" href="/resources/css/style.css" type="text/css" />
	<script type="text/javascript" src="/resources/js/jquery.js"></script>
	<script type="text/javascript" src="/resources/js/common.js"></script>
</head>
<body>
	<div class="user_notification" id="user_notification" title="User Notification" style="display: none;">
		<img src="/resources/images/action_stop.gif" onClick="$('#user_notification').fadeOut('slow')" class="close_icon" />
		<div id="user_notification_message">{$user_notification}</div>
	</div>
	<div class="header">OCR project - Image to text</div>
    
	{*<ul class="menu">
        <li><a href="/" title="Home">Home</a></li>
		<li><a href="/Documentation/">Reference documentation</a></li>
    </ul>*}

    <div class="content">{$content}</div>

	{*
	<div class="footer">This is a 2nd version of Snoopy [ Generated in: {$generated}s, db queries: <span onclick="$('#query_debug').show('fast');">{$entity_query|@count}] </span>
	</div>
	*}
	{if $entity_query}
	<div id="query_debug" style="display: none; position: fixed; bottom: 0; left: 0; padding: 5px; border: 1px solid gray; background-color: silver;">
		<span onclick="$('#query_debug').hide('fast')">Close</span>
		<ol style="">
			{foreach from=$entity_query item=query}
			<li style="font-size: 11px;background-color: mistyrose; margin: 5px 0; padding: 3px;">{$query}</li>
			{/foreach}
		</ol>
	</div>
	{/if}

</body>
</html>
