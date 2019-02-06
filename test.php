<?php
ini_set('display_errors', true);
require_once "Debugger.php";
new Debugger;

class testClass
{
	
	function __construct()
	{
		trigger_error("test in the class");
	}

	public static function testFunc($val1)
	{
		throw new Exception("Exception in testFunc", 1);
	}
}

function a($val1, $val2, $val3)
{
	b(["test21"], "test22", 23);
}

function b($val1, $val2, $val3)
{
	c(["test31"], "test32", 33);
}

function c($val1, $val2, $val3)
{
	throw new Exception("Exception in function in function in function", 1);
}


throw new Exception("The Bug F*cking Error is occured! Try to reload the page, suddenly it will fix the error.", 666);

a(["test11"], "test12", 13);

testClass::testFunc(33);
$test = new testClass();



echo "end of file";