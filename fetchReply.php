<?php
if (isset($_POST['mac'])) {
    $mac=strtolower($_POST['mac']);
    $replyfile = "devicefiles/".$mac.".reply";
    
    if(file_exists($replyfile)) {
        $response = fopen($replyfile, "r") or die("Unable to open file!");
        $timestamp=fgets($response);
        $content=fread($response,filesize($replyfile));
        fclose($response);

        if ($_POST['id']=="XML") {
            echo "<!--".$content."-->";
        } else if ($_POST['id']=="timestamp"){
            echo date("d.m.y - H:i:s", $timestamp);
        }  
    } else if ($_POST['id']!="heartbeat"){
        echo 'empty';
    }
    if ($_POST['id']=="heartbeat"){
        $heartbeat='devicefiles/'.$mac.'.heartbeat';
        $timestamp = file_get_contents($heartbeat); 
        echo $timestamp;

    }
    //echo $content;
}