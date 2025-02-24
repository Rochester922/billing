<?php
defined('BASEPATH') or exit('No direct script access allowed');

function log_msg($level, $msg) {
    //$header = "<<< APP MESSAGE >>> [FILE: " . __FILE__ . "; FUNCTION " . __FUNCTION__ . "(); LINE " . __LINE__ ."] ---> ";    
    $header = "[APP_MSG] ";    
    log_message($level, $header . $msg);
}

function log_error_msg($msg) {
    log_msg('error', $msg);
}

function log_debug_msg($msg) {
    log_msg('debug', $msg);
}

function log_info_msg($msg) {
    log_msg('info', $msg);
}

