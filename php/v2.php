<?php
gc_collect_cycles();
$time_start = microtime(true);

$url = "http://localhost/multi/write.php";
$multi_chanel = curl_multi_init();

$chanels = [];
$num_channels = 10;
$channel = 0;
$postfields = array();
while ($channel < $num_channels) {
    $chanels[$channel] = curl_init($url);
    curl_setopt($chanels[$channel], CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chanels[$channel], CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($chanels[$channel], CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($chanels[$channel], CURLOPT_POSTFIELDS, $postfields);

    curl_multi_add_handle($multi_chanel, $chanels[$channel]);
    curl_multi_exec($multi_chanel, $active);
    $channel++;
}

do {
    $teste = curl_multi_exec($multi_chanel, $active);
    if ($active) {
        curl_multi_select($multi_chanel);
    }
} while ($active > 0 && $teste == CURLM_OK);

foreach ($chanels as $cchannel) {
    curl_multi_remove_handle($multi_chanel, $cchannel);
}
curl_multi_close($multi_chanel);

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);
echo '<b>Total Execution Time:</b> '.$execution_time.' segundos';