<?php
// set_time_limit(0);
// ignore_user_abort(1);
// $filename = 'definitivo/teste.txt';

// $str = str_repeat(str_pad('', 400, 'A', STR_PAD_LEFT)."\n", 50000);
// $data = str_repeat($str, 100);

// file_put_contents($filename, $data, FILE_APPEND);
// die;


set_time_limit(0);
ignore_user_abort(1);
$time_start = microtime(true);


$filename = 'definitivo/teste.txt';
$fp = fopen($filename, 'a+');

$str = str_repeat(str_pad('', 400, 'A', STR_PAD_LEFT)."\n", 50000);
for ($i=1; $i <= 100; $i++) {
    fwrite($fp, $str);
}
// fclose($filename);

$filename2 = 'definitivo/time.txt';
$fp2 = fopen($filename2, 'a+');

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);
fwrite($fp2, $execution_time);
// fclose($filename2);
