
<div class="title">
	<h1>Another one PHP error...</h1>
</div>
<div class="container ErrLevel<?=$theme['error']['level']?>">
	<div class="block">
		<div class="head">
			<span class="errType">[<?=$theme['error']['code']?>] <?=$theme['error']['type']?></span>
			<span id="HelpMeStack"><a href="https://stackoverflow.com/search?q=[php] <?=$theme['error']['message']?>" target="_blank">Help me, Stack Overflow!</a></span>
		</div>
		<div class="head">
			<span><?=$theme['error']['message']?></span>
		</div>
	</div>
	<div class="block">
		<?php
			foreach ($theme['backtrace'] as $index => $step) {
				echo "<div class=\"code\" id=\"file{$index}\">".PHP_EOL;
				echo $theme['file']['preview'][$index];
				echo "</div>".PHP_EOL;
			}
		?>
	</div>
	<div class="block">
		<div class="backtrace">
			<p id="backtraceSwitch"><a href="#">Show/Hide Trace</a></p>
			<div id="backtraceList" style="display: none;">
			<?php
            //TODO: Подсвечивать активный бектрейс
				foreach ($theme['backtrace'] as $index => $step) {
					echo "<p onclick=\"selectBacktrace({$index})\" class=\"backtraceItem\">
					<span class=\"file\">{$step['file']}:{$step['line']}</span>
					<span class=\"index\">{$index} </span>
					Initiator: {$step['asString']}
					</p>".PHP_EOL;
				}
			?>
			</div>
		</div>
	</div>
    <div class="block">
        <div class="variables">
            <div id="varGET">
                <pre>
                <?
                $varString = print_r($theme['variables'], TRUE);

                $varString = preg_replace("/Array\n.*\(/", "[", $varString);
                $varString = preg_replace("/\)\n/", "]", $varString);
                $varString = preg_replace("/\[(.*)\]/mU", "'$1'", $varString);
                $varString = str_replace(["'GET'", "'POST'", "'COOKIE'", "'SESSION'", "'SERVER'"], ["\$_GET", "\$_POST", "\$_COOKIE", "\$_SESSION", "\$_SERVER"], $varString);

                echo $varString;
                ?>
                </pre>
            </div>
        </div>
    </div>
</div>