<?php
// $_SESSION['id'] = 1;
$this->layout('layout/json');
/*
$output = $this->rumus()->getList();
$usia = 70;
$d = array();
foreach ($output as $key => $value)
{    
    foreach ($value as $k => $v)
    {
        $d[$key][$k] = $v;
        if ($k = "nama")
        {
            $v = ucwords(strtolower($v));
            $d[$key][$k] = $v;
        }
        $d[$key]["date_past"] = datePast("{$usia} day");
        $d[$key]["date_future"] = dateFuture("{$usia} day");
    }
}

echo json_encode($d, JSON_PRETTY_PRINT);
*/
$data = get_respon_code();
$data = array_merge($data, array("data" => array("api" => "running",  "api_url" => $config['APP']['url'])));
http_response_code();

echo json_encode($data, JSON_PRETTY_PRINT);