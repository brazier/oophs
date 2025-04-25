<?php
    if (isset($_POST['mac'])):
    $mac=strtolower($_POST['mac']);
    $runfile = "devicefiles/".$mac.".run";
    
    file_put_contents($runfile,$_POST['mode'], LOCK_EX);
    echo "Running mode:".$_POST['mode']."\n";
    echo "Waiting for reply...";
        if ($_POST['mode']=="3"){
            $sh = $_POST['sh'];
            file_put_contents($runfile,"\n".$sh, FILE_APPEND | LOCK_EX);
        }
    endif;
?>