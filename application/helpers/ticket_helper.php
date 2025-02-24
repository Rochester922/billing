<?php
defined('BASEPATH') or exit('No direct script access allowed');


function getStatusFromID($id) {
	$status = '';
	if($id==1) $status='<span style="color:#e9551e">In Progress</span>';
	else if($id==2) $status='<span style="color:#186107">Fixed</span>';
	else if($id==3) $status='<span style="color:#71001f">Re-opened</span>';
	return $status;
}
function getPriorityFromID($id) {
	$priority = '';
	if($id==1) $priority='<span style="color:#830909">High</span>';
	else if($id==2) $priority='<span style="color:#090909">Normal</span>';
	else if($id==3) $priority='<span style="color:#125f71">Low</span>';
	return $priority;
}
function getOwnerNameFromID($id) {
	$CI =& get_instance();
	$CI->load->database();
	$owner = 'admin';
	$owner_sql = "SELECT username FROM users WHERE id='".$id."' LIMIT 1";
	$owner_query = $CI->db->query($owner_sql);
	foreach ($owner_query->result() as $item) {
		return $item->username;
	}
	return $owner;
}


function listOptions($data) {
	$array_options = array();

	if($data->no_video==1) {
		array_push($array_options, 'No Video');
	}
	if($data->no_audio==1) {
		array_push($array_options, 'No Audio');
	}
	if($data->stream_error==1) {
		array_push($array_options, 'Stream Error');
	}
	if($data->no_epg==1) {
		array_push($array_options, 'No EPG');
	}
	if($data->catch_up_needed==1) {
		array_push($array_options, 'Catch Up Needed');
	}
	if($data->epg_needed==1) {
		array_push($array_options, 'EPG needed');
	}
	if($data->file_missing==1) {
		array_push($array_options, 'File Missing On Catch Up');
	}
	if($data->wrong_channel_name==1) {
		array_push($array_options, 'Wrong Channel Name');
	}

	return implode(', ', $array_options);

}
