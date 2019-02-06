<?php

namespace tregor\ErrorHandler;

/**
 * PHP library for handling exceptions and errors.
 * 
 * Class ErrorHandler
 * @package  tregor
 * 
 * @author    tregor<tregor1997@gmail.com>
 * @copyright 2019 (C) tregor
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/tregor/ErrorHandler
 * @since     1.0.0
 */
class ErrorHandler
{
	/* Array of error params */
	private $error;
	/* Array of error file params */
	private $file;
	/* Array of trace steps */
	private $backtrace;
	/* Array of view template */
	private $template = "light";
	/* Max count of trace steps */
	private $traceDepth = 0;

	/** Getter for template setting */
	public function getTemplate() {return $this->template;}
	/** Getter for trace depth */
	public function getTraceDepth() {return $this->traceDepth;}
	/** Setter for template setting */
	public function setTemplate($value) {return $this->template = $value;}
	/** Setter for trace depth */
	public function setTraceDepth($value) {return $this->traceDepth = $value;}
	
	/**
	 * Initializing and setup handlers
	 */
	function __construct()
	{
		set_exception_handler([$this, 'catchException']);
		set_error_handler([$this, 'catchError']);
	}

	/**
	 * Error handler
	 * @param  integer $errno     Error code
	 * @param  string  $errstr    Error message
	 * @param  string  $errfile   File where error is occured
	 * @param  integer $errline   Line of file where error is occured
	 * @param  array   $variables DEPRICATED since PHP 7.2.0
	 * @return void
	 */
	public function catchError($errno, $errstr, $errfile, $errline, $variables)
	{
		/* Setting params */
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
		$this->backtrace = debug_backtrace();
		if ($this->traceDepth > 0) {
			$this->backtrace = array_splice($this->backtrace, $this->traceDepth);
		}

		/* Fix for Error Traces */
		array_shift($this->backtrace);

		/* Get part of code for each trace step */
		foreach ($this->backtrace as $index => $step) {
			$this->getFileLines($step['file'], $step['line']);
			if (!isset($step['asString'])) {
				$this->backtrace[$index]['asString'] = $this->getAsStringTrace($step);
			}
		}

		$this->render();
	}

	/**
	 * Exception handler
	 * @param  Exception $e instance of Exception
	 * @return void
	 */
	public function catchException($e)
	{
		/* Setting params */
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
		$this->backtrace = $e->getTrace();
		if ($this->traceDepth > 0) {
			$this->backtrace = array_splice($this->backtrace, $this->traceDepth);
		}

		/* Fix for Exception Traces */
		array_unshift($this->backtrace, ["file" => $e->getFile(), "line" => $e->getLine(), "asString" => "Throw new {$this->error['type']}(\"{$this->error['message']}\", {$this->error['code']})"]);

		/* Get part of code for each trace step */
		foreach ($this->backtrace as $index => $step) {
			$this->getFileLines($step['file'], $step['line']);
			if (!isset($step['asString'])) {
				$this->backtrace[$index]['asString'] = $this->getAsStringTrace($step);
			}
		}

		$this->render();
	}

	/**
	 * Getting lines from file to preview
	 * @param  string  $fileName Absolute path to file
	 * @param  integer $line     Target line of file
	 * @param  integer $offset   Number of lines before and after target
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

			/* Making lines indentation */
			$text = trim($file[$i], "\n\r\0\x0B" );
			$text = str_replace(["    ", "  "], "\t", $text);
			$text = str_replace("\t", "<span class=\"tab\"></span>", $text);

			/* 2 digits in index */
			$index = ($i < 9) ? "0".($i+1) : ($i+1) ;

			/* Current line is target line */
			if ($i == $line - 1) {
				$preview .= '<p class="line" id="target"><span class="index">'.$index.'.</span>'.$text.'</p>'.PHP_EOL;
			}else{
				$preview .= '<p class="line"><span class="index">'.$index.'.</span>'.$text.'</p>'.PHP_EOL;
			}
		}

		$this->file["preview"][] = $preview;
	}

	/**
	 * Return trace step initiator as readable string
	 * @param  array  $trace Trace step
	 * @return string        Readable string of trace step
	 */
	private function getAsStringTrace($trace)
	{
		/* Smart implode args with coolor effects :) */
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

		/* String for call with args */
		if (isset($trace['class'])) {
			$asString = $trace['class'].$trace['type'].$trace['function']."(".implode(", ", $arguments).")";
		}else{
			$asString = $trace['function']."(".implode(", ", $arguments).")";
		}

		return $asString;
	}

	/**
	 * Rendering view by setted template
	 *
	 * @return void
	 */
	private function render()
	{
		/* Compressing data for template */
		$theme = [
			"error" => $this->error,
			"file" => $this->file,
			"variables" => $this->variables,
			"backtrace" => $this->backtrace,
			"settings" => [
				"template" => $this->template,
			],
		];

		/* Checking that template is exist */
		if (!file_exists(__DIR__."/view/{$this->theme}.css")) {
			$theme['settings']['template'] = "light";
		}elseif (!file_exists(__DIR__."/view/{$this->theme}.php")) {
			$theme['settings']['template'] = "light";
		}

		/* To create new template see /view/example.php */
		require __DIR__."/view/{$this->theme}.css";
		require __DIR__."/view/{$this->theme}.php";
		require __DIR__."/view/script.js";

		die();
	}

	/**
	 * Return error level by RFC-5424
	 * @param  integer $type Error code
	 * @return string		 Error level from 3 to 5
	 */
	private static function getErrorLevel($errno) {
		/* E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR */
		if (($errno == 1)OR ( $errno == 16)OR ( $errno == 64)OR ( $errno == 256)OR ( $errno == 4096)) {
			return 3;
		}
		/* E_WARNING, E_PARSE, E_CORE_WARNING, E_COMPILE_WARNING, E_USER_WARNING */
		if (($errno == 2)OR ( $errno == 4)OR ( $errno == 32)OR ( $errno == 128)OR ( $errno == 512)) {
			return 4;
		}
		/* E_NOTICE, E_USER_NOTICE, E_STRICT, E_DEPRECATED, E_USER_DEPRECATED */
		if (($errno == 8)OR ( $errno == 1024)OR ( $errno == 2048)OR ( $errno == 8192)OR ( $errno == 16384)) {
			return 5;
		}
		return 6;
	}
	

	/**
	 * Return error type by error code
	 * @param  integer $type Error code
	 * @return string	     Error type
	 */
	private static function getErrorType($errno) {
		/* E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR */
		if (($errno == 1)OR ( $errno == 16)OR ( $errno == 64)OR ( $errno == 256)OR ( $errno == 4096)) {
			return "ERROR";
		}
		/* E_WARNING, E_PARSE, E_CORE_WARNING, E_COMPILE_WARNING, E_USER_WARNING */
		if (($errno == 2)OR ( $errno == 4)OR ( $errno == 32)OR ( $errno == 128)OR ( $errno == 512)) {
			return "WARNING";
		}
		/* E_NOTICE, E_USER_NOTICE, E_STRICT, E_DEPRECATED, E_USER_DEPRECATED */
		if (($errno == 8)OR ( $errno == 1024)OR ( $errno == 2048)OR ( $errno == 8192)OR ( $errno == 16384)) {
			return "NOTICE";
		}
		return "DEBUG";
	}
}