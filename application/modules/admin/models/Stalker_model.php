<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stalker_model extends CI_Model {
    protected $stb;

    public function __construct() {
        parent::__construct();
        $this->stb = $this->load->database('stalker', true);
    }

    public function add_package($uid, $packages = array()) {
        $pack_data = array();
        foreach ($packages as $package) {
            $pack_data[] = array('user_id' => $uid, 'package_id' => $package);
        }
        $this->stb->insert_batch('user_package_subscription', $pack_data);
    }

    public function update_package($uid, $packages = array()) {
        //define pack_data
        $pack_data   = array();
        $db_data     = array();
        $post_data   = implode(',', $packages);
        $db_data_sql = $this->stb->where('user_id', $uid)->get('user_package_subscription');
        if ($db_data_sql->num_rows() > 0) {
            foreach ($db_data_sql->result() as $dbs) {
                $db_data[] = $dbs->package_id;
            }
            $already_in = implode(',', $db_data);
            if ($already_in == $post_data) {
                return true;
            } else {
                $this->stb->where('user_id', $uid)->delete('user_package_subscription');
                foreach ($packages as $package) {
                    $pack_data[] = array('user_id' => $uid, 'package_id' => $package);
                    $options     = array('user_id' => $uid, 'package_id' => $package);
                    $this->stb->insert('user_package_subscription', $options);
                }
            }
        } else {
            $this->add_package($uid, $packages);
        }
    }

    public function get_user_packages($userid) {
        $this->stb->select('package_id');
        $this->stb->from('user_package_subscription');
        $this->stb->where('user_id', $userid);
        $query  = $this->stb->get();
        $result = $query->result();
        $ids    = array();
        foreach ($result as $data) {
            $ids[] = $data->package_id;
        }
        return $ids;
    }

    public function get_package($plan_id) {
        $this->stb->select('services_package.name,package_in_plan.package_id');
        $this->stb->from('package_in_plan');
        $this->stb->join('services_package', 'services_package.id=package_in_plan.package_id');
        $this->stb->where('package_in_plan.plan_id', $plan_id);
        $this->stb->order_by('services_package.name', 'asc');
        $query = $this->stb->get();
        // $sql    = $this->stb->where(array('plan_id' => $plan_id))->get('package_in_plan');
        $result = $query->result();
        return $result;
    }

    public function get_parent_password($userid) {
        $this->stb->select('package_id');
        $this->stb->from('user_package_subscription');
        $this->stb->where('user_id', $userid);
        $query  = $this->stb->get();
        $result = $query->result();
        $ids    = array();
        foreach ($result as $data) {
            $ids[] = $data->package_id;
        }
        return $ids;
    }

    public function get_packages() {
        log_debug_msg('admin->stalker_model->get_packages()');
        $tariff_sql     = $this->stb->where(array('user_default' => 1))->get('tariff_plan');
        $default_tariff = $tariff_sql->row();
        $package_sql    = $this->stb->where('plan_id', $default_tariff->id)->get('package_in_plan');
        $result         = $package_sql->result();
        return $result;
    }

    public function get_custom_plan_id() {
        log_debug_msg('admin/models/Stalker_model.php/get_custom_plan_id()');
        $tariff_sql = $this->stb->where(array('name' => 'CUSTOM PACKAGE'))->get('tariff_plan');
        if ($tariff_sql->num_rows() > 0) {
            $custom_pack = $tariff_sql->row();
            $result      = $custom_pack->id;
            return $result;
        } else {
            $result = 0;
            return $result;
        }
    }

    public function get_package_name($id) {
        $pack_sql = $this->stb->where('id', $id)->get('services_package');
        if ($pack_sql->num_rows() > 0) {
            $package = $pack_sql->row();
            $name    = $package->name;
            return $name;
        } else {
            return '-';
        }
    }

    public function get_last_id() {
        $insert_id = $this->stb->insert_id();
        return $insert_id;
    }

    /*
     *Start of Events Sections
     */

    public function send_message($id, $message) {
        $date        = date('Y-m-d H:i:s');
        $currentDate = strtotime($date);
        $futureDate  = $currentDate + (60 * 24);
        $formatDate  = date("Y-m-d H:i:s", $futureDate);
        $options     = array(
            'uid'          => $id,
            'event'        => 'send_msg',
            'msg'          => $message,
            'priority'     => 2,
            'addtime'      => $date,
            'need_confirm' => 1,
            'eventtime'    => $formatDate,
        );
        if ($this->stb->insert('events', $options)) {
            return true;
        } else {
            return false;
        }

    }

    public function cut_off($uid) {
        log_debug_msg("admin/models/Stalker_model.php/cut_off(): [uid: $uid]: entering");

        $status = ACCOUNT_STATUS_OFF;
        $users_update_data = array(
            'status'      => $status,
        );

        $this->stb->where('id', $uid);
        if ($this->stb->update('users', $users_update_data)) {
            log_debug_msg("admin/models/Stalker_model.php/cut_off(): [uid: $uid]: \$this->stb->update() has successfully set the status field to " . ACCOUNT_STATUS_OFF);
            log_debug_msg("admin/models/Stalker_model.php/cut_off(): [uid: $uid]: returning to caller");

            // add a cut_off record to the events table
            $time_now = date('Y-m-d H:i:s');
            $event_time = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " +4 minutes"));
            $event_options = array(
                'uid' => $uid,
                'event' => 'cut_off',
                'priority' => 1,
                'addtime' => $time_now,
                'eventtime' => $event_time
            );
            if ($this->stb->insert('events', $event_options)) {
                log_debug_msg("admin/models/Stalker_model.php/cut_off(): [uid: $uid]: \$this->stb->insert() has successfully inserted the event data");
            } else {
                // an error here is not so critical, the user will not be kicked right away but on the next portal reload attempt she wont be able to enter
                log_debug_msg("admin/models/Stalker_model.php/cut_off(): [uid: $uid]: \$this->stb->insert() has failed to insert the event data");
            }

            return true;
        }

        log_debug_msg("admin/models/Stalker_model.php/cut_off(): [uid: $uid]: \$this->stb->update() has failed to set the status field to " . ACCOUNT_STATUS_OFF);
        log_debug_msg("admin/models/Stalker_model.php/cut_off(): [uid: $uid]: returning to caller");
        return false;
    }

    public function cut_on($uid) {
        log_debug_msg("admin/models/Stalker_model.php/cut_on(): [uid: $uid]: entering");

        $status = ACCOUNT_STATUS_ON;
        $users_update_data = array(
            'status'      => $status,
        );

        $this->stb->where('id', $uid);
        if ($this->stb->update('users', $users_update_data)) {
            log_debug_msg("admin/models/Stalker_model.php/cut_on(): [uid: $uid]: \$this->stb->update() has successfully set the status field to " . ACCOUNT_STATUS_ON);
            log_debug_msg("admin/models/Stalker_model.php/cut_on(): [uid: $uid]: returning to caller");

            // add a cut_on record to the events table
            $time_now = date('Y-m-d H:i:s');
            $event_time = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " +4 minutes"));
            $event_options = array(
                'uid' => $uid,
                'event' => 'cut_on',
                'priority' => 1,
                'addtime' => $time_now,
                'eventtime' => $event_time
            );
            if ($this->stb->insert('events', $event_options)) {
                log_debug_msg("admin/models/Stalker_model.php/cut_on(): [uid: $uid]: \$this->stb->insert() has successfully inserted the event data");
            } else {
                // an error here is not so critical, it seems that a lost cut_on event is not so important (the unblocked user has to reload the portal anyway)
                log_debug_msg("admin/models/Stalker_model.php/cut_on(): [uid: $uid]: \$this->stb->insert() has failed to insert the event data");
            }

            return true;
        }

        log_debug_msg("admin/models/Stalker_model.php/cut_on(): [uid: $uid]: \$this->stb->update() has failed to set the status field to " . ACCOUNT_STATUS_ON);
        log_debug_msg("admin/models/Stalker_model.php/cut_on(): [uid: $uid]: returning to caller");
        return false;
    }

    public function get_events($id) {
        $sql = $this->stb->where('uid', $id)->get('events');
        return $sql;
    }

    /*Account date time functions*/

    public function user_info($login, $return_field) {
        $sql = $this->stb->where('login', $login)->get('users');
        if ($sql->num_rows() > 0) {
            $user   = $sql->row_array();
            $result = $user[$return_field];
        } else {
            $result = '';
        }
        return $result;
    }

    public function datetime2timestamp($datetime) {

        preg_match("/(\d+)-(\d+)-(\d+) (\d+):(\d+):(\d+)/", $datetime, $arr);
        //return @mktime($arr[4], $arr[5], 0, $arr[2], $arr[3], $arr[1]);
        return @mktime($arr[4], $arr[5], $arr[6], $arr[2], $arr[3], $arr[1]);
    }

    public function check_keep_alive($time) {
        $keep_alive_ts = $this->datetime2timestamp($time);
        $now_ts        = time();
        $dif_ts        = $now_ts - $keep_alive_ts;
        if ($dif_ts > 120 * 2) {
            return 0;
        } else {
            return 1;
        }
    }

    public function check_keep_alive_txt($time) {
        if ($this->check_keep_alive($time)) {
            return '<span class="label label-sm label-success">Online</span>';
        } else {
            return '<span class="label label-sm label-danger">Offline</span>';
        }
    }

    public function account_status($val) {
        if ($val == 1) {
            return '<span class="label label-sm label-success">Active</span>';
        } else {
            return '<span class="label label-sm label-danger">Disable</span>';
        }
    }

    public function receiver_staus($login) {
        $keep_alive = $this->user_info($login, 'keep_alive');
        $result     = $this->check_keep_alive_txt($keep_alive);
        return $result;
    }

    public function expiry_date($expiry) {

        $expired = $this->check_expired($expiry);
        $expiry  = substr($expiry, 0, 10);
        //$exp_dat = date('M j Y, h:i:m a', strtotime($expiry));
        if ($expired == "Expired") {
            $text = '<span class="label label-sm label-danger">' . $expiry . '</span>';
        } else if ($expired <= 7) {
            $text = '<span class="label label-sm label-warning">' . $expiry . '</span>';
        } else {
            $text = '<span class="label  label-sm label-success">' . $expiry . '</span>';
        }
        return $text;

    }

    public function check_expired($exp_date) {

        $d1 = strtotime($exp_date);
        if ($d1 < time()) {
            return 'Expired';
        } else {

            $date1 = date_create(date('Y-m-d H:i:s'));
            $date2 = date_create($exp_date);
            $diff  = date_diff($date1, $date2);
            return $diff->format("%a");
        }

    }

    public function message_status($val) {
        if ($val == 1) {
            return '<span class="label label-success">Send</span>';
        } else {
            return '<span class="label label-warning">Wait</span>';
        }
    }

    public function get_expiry_date($month, $date = null) {
        if ($date == null) {
            $datetime = new DateTime(date('Y-m-d 00:00:00'));
        } else {
            $datetime = new DateTime($date);
        }

        $datetime->modify('+' . $month . ' month');
        $finale = $datetime->format('Y-m-d H:i:s');
        //$cur_date=date('Y-m-d H:i:s', strtotime('-1 day', strtotime($finale)));
        return $finale;
    }

    public function get_tariff_name($login) {
        $tariff_id = $this->user_info($login, 'tariff_plan_id');
        $sql       = $this->stb->where('id', $tariff_id)->get('tariff_plan');

        if (empty($tariff_id) || $sql->num_rows() == 0) {
            return '-';
        } else {
            $tariff_data = $sql->row();
            return $tariff_data->name;
        }
    }

    public function get_tariff() {
        $sql = $this->stb->order_by('name', 'asc')->get('tariff_plan');
        return $sql;
    }

    public function get_last_time($time) {
        $time_ts = $this->datetime2timestamp($time);

        $time_now = time();

        $time_delta_s = $time_now - $time_ts;
        $str          = '';

        $hh = floor($time_delta_s / 3600);
        $ii = floor(($time_delta_s - $hh * 3600) / 60);

        if ($ii < 10) {
            $ii = '0' . $ii;
        }

        $ss = $time_delta_s - $hh * 3600 - $ii * 60;

        if ($ss < 10) {
            $ss = '0' . $ss;
        }

        $str = $hh . ':' . $ii . ':' . $ss;
        return $str;
    }

    public function get_cur_media($media_id) {
        $media = array(
            0  => '--',
            1  => _('TV'),
            2  => _('Video'),
            3  => _('Karaoke'),
            4  => _('Audio'),
            5  => _('Radio'),
            6  => _('My records'),
            7  => _('Records'),
            9  => 'ad',
            10 => _('Media browser'),
            11 => _('Tv archive'),
            12 => _('Records'),
            14 => _('TimeShift'),
            20 => _('Infoportal'),
            21 => _('Infoportal'),
            22 => _('Infoportal'),
            23 => _('Infoportal'),
            24 => _('Infoportal'),
            25 => _('Infoportal'),
        );

        if (!empty($media[$media_id])) {
            return $media[$media_id];
        } else {
            return 'media_id: ' . $media_id;
        }
    }

    public function time_ago($date) {
        if (empty($date)) {
            return "No date provided";
        }

        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");

        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

        $now = time();

        $unix_date = strtotime($date);

        // check validity of date

        if (empty($unix_date)) {
            return "Bad date";
        }

        // is it future date or past date

        if ($now > $unix_date) {
            $difference = $now - $unix_date;
            $tense      = "ago";
        } else {
            $difference = $unix_date - $now;
            $tense      = "from now";
        }

        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if ($difference != 1) {
            $periods[$j] .= "s";
        }

        return "$difference $periods[$j] {$tense}";

    }

    public function stb_actions($uid, $event) {
        $TimeNow   = date('Y-m-d H:i:s');
        $eventtime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " +4 minutes"));
        $options   = array('uid' => $uid,
            'event'                  => $event,
            'priority'               => 1,
            'addtime'                => $TimeNow,
            'eventtime'              => $eventtime);
        if ($this->stb->insert('events', $options)) {
            return true;
        } else {
            return false;
        }
    }

    public function remove_package($uid) {
        return true; // do nothing (replaces the old function that used the gold_users table)
    }

    public function restore_package($uid) {
        return true; // do nothing (replaces the old function that used the gold_users table)
    }

    public function get_user_login($login, $rid = null) {
        if ($rid == null) {
            $sql = $this->stb->where('login', $login)->get('users');
        } else {
            $sql = $this->stb->where('login', $login)->where('rid', $rid)->get('users');
        }

        return $sql;
    }

    public function mac_update($login) {
        $sql  = $this->stb->where('login', $login)->get('users');
        $user = $sql->row();
        if (!empty($user->mac)) {
            $this->db->set('mac', $user->mac);
            $this->db->where('account', $login);
            $this->db->update('accounts');
        }
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
    // Old functions (not in use anymore)

    public function remove_package__original($uid) {

        $sql         = $this->stb->where('id', $uid)->get('users');
        $user        = $sql->row();
        $gold_users  = $this->stb->where('id', $uid)->get('gold_users');
        $insert_data = array(
            'id'             => $user->id,
            'device_id'      => $user->device_id,
            'device_id2'     => $user->device_id2,
            'tariff_plan_id' => $user->tariff_plan_id,
        );
        if ($gold_users->num_rows() > 0) {
            $this->stb->where('id', $uid);
            $execute = $this->stb->update('gold_users', $insert_data);
        } else {
            $execute = $this->stb->insert('gold_users', $insert_data);
        }

        if ($execute) {
            $random_number = strtoupper(uniqid());
            $up_data       = array(
                'device_id'      => $random_number,
                'device_id2'     => $random_number,
                'tariff_plan_id' => 3,
            );
            $this->stb->where('id', $uid);
            $this->stb->update('users', $up_data);
        }
    }

    public function restore_package__original($uid) {
        log_debug_msg("admin/models/Stalker_model.php/restore_package(): [uid: $uid]: entering");

        $sql = $this->stb->where('id', $uid)->get('gold_users');
        if ($sql->num_rows() == 1) {
            $user = $sql->row();

            $update_data = array(
                'device_id'      => $user->device_id,
                'device_id2'     => $user->device_id2,
                'tariff_plan_id' => $user->tariff_plan_id,
            );

            $this->stb->where('id', $uid);
            if ($this->stb->update('users', $update_data)) {
                log_debug_msg("admin/models/Stalker_model.php/restore_package(): [uid: $uid]: \$this->stb->update() has successfully set critical fields");
            } else {
                log_debug_msg("admin/models/Stalker_model.php/restore_package(): [uid: $uid]: \$this->stb->update() has failed to set critical fields");
                // todo: handle this error
            }
        } else {
            log_debug_msg("admin/models/Stalker_model.php/restore_package(): [uid: $uid]: nothing done (user not found in table gold_users)");
        }

        log_debug_msg("admin/models/Stalker_model.php/restore_package(): [uid: $uid]: returning to caller");
    }

    public function cut_off__original($uid) {
        log_debug_msg("admin/models/Stalker_model.php/cut_off(): [uid: $uid]: entering");
        $TimeNow   = date('Y-m-d H:i:s');
        $eventtime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " +4 minutes"));
        $event_options   = array('uid' => $uid,
            'event'                  => 'cut_off',
            'priority'               => 1,
            'addtime'                => $TimeNow,
            'eventtime'              => $eventtime);

        /*
        */
        $users_update_data = array(
            'status'      => ACCOUNT_STATUS_OFF,
        );
        $this->stb->where('id', $uid);
        if ($this->update('users', $users_update_data)) {
            log_debug_msg("admin/models/Stalker_model.php/cut_off(): [uid: $uid]: \$this->stb->update() has successfully set the status field to " . ACCOUNT_STATUS_OFF);
            // todo: handle this error
        } else {
            log_debug_msg("admin/models/Stalker_model.php/cut_off(): [uid: $uid]: \$this->stb->update() has failed to set the status field to " . ACCOUNT_STATUS_OFF);
            // todo: handle this error
        }

        if ($this->stb->insert('events', $event_options)) {
            log_debug_msg("admin/models/Stalker_model.php/cut_off(): [uid: $uid]: has successfully inserted the event data");
            log_debug_msg("admin/models/Stalker_model.php/cut_off(): [uid: $uid]: returning to caller");
            return true;
        } else {
            log_debug_msg("admin/models/Stalker_model.php/cut_off(): [uid: $uid]: \$this->stb->insert() has failed to insert the event data");
            // todo: handle this error
        }
        
        log_debug_msg("admin/models/Stalker_model.php/cut_off(): [uid: $uid]: returning to caller");
        return false;
    }

    public function cut_on__original($uid) {
        log_debug_msg("admin/models/Stalker_model.php/cut_on(): [uid: $uid]: entering");
        $TimeNow   = date('Y-m-d H:i:s');
        $eventtime = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " +4 minutes"));
        $event_options   = array('uid' => $uid,
            'event'                  => 'cut_on',
            'priority'               => 1,
            'addtime'                => $TimeNow,
            'eventtime'              => $eventtime);

        /*
        */
        $users_update_data = array(
            'status'      => ACCOUNT_STATUS_ON,
        );
        $this->stb->where('id', $uid);
        if ($this->stb->update('users', $users_update_data)) {
            log_debug_msg("admin/models/Stalker_model.php/cut_on(): [uid: $uid]: \$this->stb->update() has successfully set the status field to " . ACCOUNT_STATUS_ON);
            // todo: handle this error
        } else {
            log_debug_msg("admin/models/Stalker_model.php/cut_on(): [uid: $uid]: \$this->stb->update() has failed to set the status field to " . ACCOUNT_STATUS_ON);
            // todo: handle this error
        }

        if ($this->stb->insert('events', $event_options)) {
            log_debug_msg("admin/models/Stalker_model.php/cut_on(): [uid: $uid]: successfully inserted the event data");
            log_debug_msg("admin/models/Stalker_model.php/cut_on(): [uid: $uid]: returning to caller");
            return true;
        } else {
            log_debug_msg("admin/models/Stalker_model.php/cut_on(): [uid: $uid]: \$this->stb->insert() has failed to insert the event data");
            // todo: handle this error
        }

        log_debug_msg("admin/models/Stalker_model.php/cut_on(): [uid: $uid]: returning to caller");
        return false;
    }



}
/* End of file Stalker_model.php */
/* Location: ./application/modules/admin/models/Stalker_model.php */
