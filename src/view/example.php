<?php
/**
 * Copyright (c)
 * by tregor 12.3.2019.
 */

/**
 * Hi, it is a quick tutorial of how to create you own template for this ErrorHandler!
 *
 * The first thing is that you can create anything you wanna! Yeah, realy any what you want!
 * By default, handler will render template with some variables.
 * All of that params is stored in array "$theme";
 * Those param is listed down here:
 * Name					Type			Desc
 * "name"				String			Theme name, used to navigate .css and .js files
 * "error"				Array			Array, that contains everything about handled error
 * 		"level"			String			Error level by RFC-5424, can be from 3 (Error), 4 (Warning) or 5 (Notice)
 * 		"code"			Integer			PHP int code of error
 * 		"type"			String			ERROR, WARNING or NOTICE
 *		"message"		String			Error message
 * "backtrace"			Array			Array, that contains backtrace. 0-index is action before handled error, last-index is root action
 * 		"file"			String			File name with path
 * 		"line"			String			Line of file
 * 		"asString"		String			String formated name of method and params
 * "file"				Array			Array, where stored contents of files, that have part in backtrace
 * 		"preview"		String			String, that contains some non-compiled strings of backtrace file
 *
 * In order to understand how it works - there are simple code for you:
 */
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="<?=__DIR__ . "/view/".$theme['name']?>.css">
</head>
<body>
	<div>
		<p><?=$theme['error']['type']?>, <?=$theme['error']['code']?></p>
		<p><?=$theme['error']['message']?></p>
		<div>
			<?php
			foreach ($theme['backtrace'] as $traceItem){
				echo $traceItem['asString'];
			}
			?>
		</div>
	</div>
</body>
</html>