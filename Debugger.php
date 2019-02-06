<?php

/**
 * 
 */
class Debugger
{
	private $error;
	private $file;
	private $variables;
	private $backtrace;
	private $theme = "light";
	
	function __construct()
	{
		set_exception_handler([$this, 'catchException']);
		set_error_handler([$this, 'catchError']);
	}

	public function catchError($errno, $errstr, $errfile, $errline, $variables)
	{
		$this->error = [
			"code" => $errno,
			"type" => $this->getErrorType($errno),
			"level" => $this->getErrorLevel($errno),
			"message" => $errstr,
		];
		$this->file = [
			"name" => $errfile,
			"line" => $errline
		];
		//$this->variables = $variables;	--DEPRICATED since PHP 7.2.0
		$this->backtrace = debug_backtrace();
		array_shift($this->backtrace);

		foreach ($this->backtrace as $index => $step) {
			$this->getFileLines($step['file'], $step['line']);
			if (!isset($step['asString'])) {
				$this->backtrace[$index]['asString'] = $this->getAsStringTrace($step);
			}
		}

		$this->render();
	}

	public function catchException($e)
	{
		$this->error = [
			"code" => $e->getCode(),
			"type" => get_class($e),
			"level" => 'EXCEPTION',
			"message" => $e->getMessage(),
		];
		$this->file = [
			"name" => $e->getFile(),
			"line" => $e->getLine(),
		];
		//$this->variables = $variables;	--DEPRICATED since PHP 7.2.0
		$this->backtrace = $e->getTrace();
		array_unshift($this->backtrace, ["file" => $e->getFile(), "line" => $e->getLine(), "asString" => "Throw new {$this->error['type']}(\"{$this->error['message']}\", {$this->error['code']})"]);

		foreach ($this->backtrace as $index => $step) {
			$this->getFileLines($step['file'], $step['line']);
			if (!isset($step['asString'])) {
				$this->backtrace[$index]['asString'] = $this->getAsStringTrace($step);
			}
		}

		$this->render();
	}

	/**
	 * Получение строк из файла
	 * @param  string  $fileName Имя файла
	 * @param  integer  $line    Порядковый номер искомой строки
	 * @param  integer $offset   Количество строк до линии и после
	 * @return void
	 */
	private function getFileLines($fileName, $line, $offset = 5)
	{
		$file = file($fileName);
		$start = ($line - $offset >= 0) ? $line - $offset : 0;
		$end = ($line - $offset >= 0) ? $line + $offset : $offset*2;
		$preview = "";
		for ($i = $start; $i != $end-1; $i++) {
			if (!isset($file[$i])) {
				continue;
			}
			$text = trim($file[$i], "\n\r\0\x0B" );
			$text = str_replace(["    ", "  "], "\t", $text);
			$text = str_replace("\t", "<span class=\"tab\"></span>", $text);

			$index = ($i < 9) ? "0".($i+1) : ($i+1) ;

			if ($i == $line - 1) {
				$preview .= '<p class="line" id="target"><span class="index">'.$index.'.</span>'.$text.'</p>'.PHP_EOL;
			}else{
				$preview .= '<p class="line"><span class="index">'.$index.'.</span>'.$text.'</p>'.PHP_EOL;
			}
		}
		$this->file["preview"][] = $preview;
	}

	private function getAsStringTrace($trace)
	{
		$arguments = [];
		foreach ($trace['args'] as $arg) {
			switch (gettype($arg)) {
				case 'string':
					$argColor = "green";
					$argStr = gettype($arg)." \"$arg\"";
					break;
				case 'double':
				case 'integer':
					$argColor = "blue";
					$argStr = gettype($arg)." $arg";
					break;
				case 'array':
					$argColor = "red";
					$argStr = gettype($arg)." [...]";
					break;
				case 'object':
					$argColor = "red";
					$argStr = gettype($arg)." of ".get_class($arg);
					break;
				case 'resource':
					$argColor = "red";
					$argStr = gettype($arg)." of ".get_resource_type($arg);
					break;
				case 'boolean':
					$argColor = "cyan";
					$argStr = ($arg) ? gettype($arg)." TRUE" : gettype($arg)." FALSE";
					break;
				default:
					$arguments[] = gettype($arg);
					break;
			}
			$arguments[] = "<span style=\"color:{$argColor}\">".$argStr."</span>";
		}


		if (isset($trace['class'])) {
			$asString = $trace['class'].$trace['type'].$trace['function']."(".implode(", ", $arguments).")";
		}else{
			$asString = $trace['function']."(".implode(", ", $arguments).")";
		}

		return $asString;
	}

	/**
	 * Рендерит шаблон с соответствующими параметрами
	 *
	 * @return boolean
	 */
	protected function render()
	{
		$theme = [
			"error" => $this->error,
			"file" => $this->file,
			"variables" => $this->variables,
			"backtrace" => $this->backtrace,
			"settings" => [
				"template" => $this->theme,
			],
		];
		if (!file_exists(__DIR__."/view/{$this->theme}.css")) {
			$theme['settings']['template'] = "light";
		}elseif (!file_exists(__DIR__."/view/{$this->theme}.php")) {
			$theme['settings']['template'] = "light";
		}

		$style = file_get_contents(__DIR__."/view/{$this->theme}.css");
		$script = file_get_contents(__DIR__."/view/script.js");

		echo "<style>".PHP_EOL.$style."</style>".PHP_EOL;
		echo '<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>'.PHP_EOL."<script>".PHP_EOL.$script."</script>".PHP_EOL;
		require __DIR__."/view/{$this->theme}.php";
		die();
		return true;
	}

	/**
	 * Возвращает уровень ошибки по RFC-5424
	 * @param  integer $type Константа типа ошибки
	 * @return string		Уровень ошибки от 3 до 5
	 */
	private static function getErrorLevel($errno) {
		if (($errno == 1)OR ( $errno == 16)OR ( $errno == 64)OR ( $errno == 256)OR ( $errno == 4096)) {
			return 3;
		}
		if (($errno == 2)OR ( $errno == 4)OR ( $errno == 32)OR ( $errno == 128)OR ( $errno == 512)) {
			return 4;
		}
		if (($errno == 8)OR ( $errno == 1024)OR ( $errno == 2048)OR ( $errno == 8192)OR ( $errno == 16384)) {
			return 5;
		}
		return 6;
	}
	

	/**
	 * Возвращает строкове название типа ошибки
	 * @param  integer $type Константа типа ошибки
	 * @return string	   Строковое название ошибки
	 */
	private static function getErrorType($errno) {
		if (($errno == 1)OR ( $errno == 16)OR ( $errno == 64)OR ( $errno == 256)OR ( $errno == 4096)) {
			return "ERROR";
		}
		if (($errno == 2)OR ( $errno == 4)OR ( $errno == 32)OR ( $errno == 128)OR ( $errno == 512)) {
			return "WARNING";
		}
		if (($errno == 8)OR ( $errno == 1024)OR ( $errno == 2048)OR ( $errno == 8192)OR ( $errno == 16384)) {
			return "NOTICE";
		}
		return "DEBUG";
	}
}