<?php

include "./config.php";

$data = array(
    'user_id' => 1,
);

http_request("demo", $data);
