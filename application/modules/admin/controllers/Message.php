<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Message extends AdminController {
  protected $module_name = 'Send Message';

    public function __construct() {
        parent::__construct();
        //Do your magic here
    }

    public function index() {
        $stalker = $this->load->database('stalker', true);
        $sql     = $stalker->order_by('fname', 'asc')->get('users');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        if ($this->form_validation->run() == true) {
            $date        = date('Y-m-d H:i:s');
            $currentDate = strtotime($date);
            $futureDate  = $currentDate + (60 * 24);
            $formatDate  = date("Y-m-d H:i:s", $futureDate);
            $type        = $this->input->post('type');
            if ($type == 'All') {
                foreach ($sql->result() as $us_dt) {
                    # code...
                    $insert_Data = array(
                        'uid'          => $us_dt->id,
                        'event'        => 'send_msg',
                        'msg'          => $this->input->post('message'),
                        'priority'     => 2,
                        'addtime'      => $date,
                        'need_confirm' => 1,
                        'eventtime'    => $formatDate);
                    $stalker->insert('events', $insert_Data);
                }
                $this->msg('Message was Send!');
            } else {
                $users_cs = $this->input->post('users');
                foreach ($users_cs as $usd) {
                    # code...
                    $insert_Data = array(
                        'uid'          => $usd,
                        'event'        => 'send_msg',
                        'msg'          => $this->input->post('message'),
                        'priority'     => 2,
                        'addtime'      => $date,
                        'need_confirm' => 1,
                        'eventtime'    => $formatDate);
                    $stalker->insert('events', $insert_Data);
                }
                $this->msg('Message was Send!');
            }
        }
        $this->data['title']  = $this->module_name;
        $this->data['module'] = $this->module_name;
        $this->data['user_details'] = $sql;
        $this->render('common/message');
    }
    
}
/* End of file Message.php */
/* Location: ./application/modules/admin/controllers/Message.php */
