<?php
require_once(dirname(__FILE__) . "/../php-sql-parser.php");
require_once(dirname(__FILE__) . "/../test-more.php");

$parser = new PHPSQLParser();

$sql = "insert into SETTINGS_GLOBAL (stg_value,stg_name) values('','force_ssl')";
$p = $parser->parse($sql);
$expected = getExpectedValue('insert1.serialized');
eq_array($p, $expected, 'insert some data into table');

$sql = " INSERT INTO settings_global VALUES ('DBVersion', '146')";
$p = $parser->parse($sql, true);
$expected = getExpectedValue('insert2.serialized');
eq_array($p, $expected, 'insert some data into table with positions');
