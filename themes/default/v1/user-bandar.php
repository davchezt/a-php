<?php
$this->layout('layout/json');

$detail = isset($_POST['detail']) ? trim($_POST['detail']):null;

$data = array();
$user =  $this->user()->getData("2");
$count = $this->user()->getDataCount("2");
if (isset($offset) && isset($limit)) {
  $user =  $this->user()->getData("2", $offset, $limit);
  if ($detail) $user =  $this->user()->getData("2", $offset, $limit, true);
}
if ($detail) {
  http_response_code(200);
  $data = get_respon_code();
  $data = array_merge($data, array("count" => $count));
  $data = array_merge($data, array("data" => $user));
}
else {
  http_response_code(200);
  $data = array_merge($data, array("data" => $user));
}
echo json_encode($data, JSON_PRETTY_PRINT);
?>