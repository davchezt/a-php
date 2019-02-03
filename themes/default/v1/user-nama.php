<?php
$this->layout('layout/json');
$user = $this->user()->getPublic($id);
$data = array(
  "id" => $user ? $user->id:null,
  "name" => $user ? $user->nama:null,
  "picture" => $user ? $user->foto:null,
  "gender"=> $user ? $user->kelamin:null
);
// $data = array("name" => $this->user()->getNama($id));

echo json_encode($data, JSON_PRETTY_PRINT);
?>