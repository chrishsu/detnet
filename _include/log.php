<?php

function logger($msg) {
    if (!DN_DEBUG) return;
    $DN_LOG_FILE = "error.log";
    $fp = fopen($DN_LOG_FILE, "a");
    if (flock($fp, LOCK_EX)) {
        $date =  date("d M Y H:i:s");
        fwrite($fp, "[" + $date + "] " + $msg);
        flock($fp, LOCK_UN);
    }
    fclose($fp);
}

?>