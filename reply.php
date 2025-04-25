<?php
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $post = array();
    $data=file_get_contents('php://input');
    $time = date('U');
    $devicefile="devicefiles/".strtolower($_GET['mac']).".reply";
    file_put_contents($devicefile,$time."\n".$data,  LOCK_EX);
}

?>