<?php
error_reporting(0);
$run_sample_user = $_POST['run_sample_user'];
$sample_laber = $_POST['sample_label'];
$file_path = '/mnt/scc8t/zhousq/pipeline_state/' . $run_sample_user . '_' . $sample_label . '_command.txt';
$file = fopen($file_path, 'r');
$arr = array();
$i = 0;
while (!feof($file)) {
    $arr[$i] = fgets($file);
    $i++;
}
fclose($file);
$file_arr = array_filter($arr);
print json_encode([
    'success' => true,
    'data' => $file_arr
]);
