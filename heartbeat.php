<?php
if (isset($_GET['mac'])) {
    $mac=strtolower($_GET['mac']);
    $heartbeat="devicefiles/".$mac.".heartbeat";
    $time=date("U");

    // Update timestamp
    file_put_contents($heartbeat,$time, LOCK_EX);

    $runfile = "devicefiles/".$mac.".run";
    if(file_exists($runfile)){
        $response = fopen($runfile, "r") or die("Unable to open file!");
        $case=fgets($response);
        $sh=fread($response,filesize($runfile));
        //might be used later to read rest of file
        //$content=fread($response,filesize($file));
        fclose($response);

        //Run only once so delete the .run file before outputting anything 
        unlink($runfile);

        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<command>\n";
        // 1: telnet, 2: devicedetails, 3: shText, 4: media
        switch($case) {
            case "1":
            echo " <configuration>\n";
            echo "  <device>\n  <telnetEnabled>true</telnetEnabled>\n";
            echo "  </device>\n </configuration>\n";
            break;
            case "2":
            echo " <postDeviceDetails url=\"https://services.openpeak.net/reply.php?mac=$mac\" method=\"post\" />\n";
            break;
            case "3":
            echo " <remoteExec commandId=\"1\" timeout=\"5\">\n";
            echo "  <callbackURL>https://services.openpeak.net/reply.php?mac=$mac</callbackURL>\n";
            echo "  <shText>$sh</shText>\n";
            echo " </remoteExec>\n";
            break;
            case "4":
            echo " <mediaDisplay contentType=\"application/x-shockwave-flash\">\n";
            echo "  <mediaURL>http://openpeak.net/media.swf</mediaURL>\n";
            echo " </mediaDisplay>\n";
            break;
            case "5":
            echo " <downloadFirmware mode=\"\" reboot=\"\">\n";
            echo "  <downloadURL>http://https://services.openpeak.net/firmware_file.ext</downloadURL>\n";
            echo "  <successURL>http://https://services.openpeak.net/reply.php?mac=$mac</successURL>\n";
            echo "  <failureURL>http://https://services.openpeak.net/reply.php?mac=$mac</failureURL>\n";
            echo "  <notesURL>http://https://services.openpeak.net/notes</notesURL>\n";
            echo " </downloadFirmware>\n";
            break;
            case "6":
            echo "<publishMessage channel=\"http://http://service.openpeak.net/reply.php?mac=$mac\" />\n";
            break;
            case "7":
            echo " <motd />\n";
            break;
            default:
            echo "\n";
        }
        echo "</command>";
    }
}
?>