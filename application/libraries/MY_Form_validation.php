<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class MY_Form_validation extends CI_Form_validation
{
    public $CI;
    public function __construct()
    {
        parent::__construct();

    }

    public function check_credits($credits)
    {
        $type = $this->CI->input->post('type');
        if ($type == 'CRDT') {
            return true;
        } else {
            $username = $this->CI->uri->segment(4);
            $this->CI->load->model('transaction_model');
            $balance = $this->CI->transaction_model->get_credit_balance($username);
            if ($balance < $credits) {
                $this->set_message('check_credits', "You can't recover credits more than he have!");
                return false;
            } else {
                return true;
            }
        }
    }

    public function is_unique($str, $field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);
        return is_object($this->CI->db)
        ? ($this->CI->db->limit(1)->get_where($table, array($field => $str))->num_rows() === 0)
        : false;
    }

    function valid_mac($mac)
  	{
  	  //return (preg_match('/([a-fA-F0-9]{2}[:|\-]?){6}/', $mac) == 1);
  		if (preg_match('/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/', $mac)){
  			 return true;
  		}else{
  			$this->set_message('valid_mac', 'Please enter a valid MAC Address');
              return FALSE;
  		}

  	}

  	function normalizeMac($mac){

          $mac = iconv("WINDOWS-1251","UTF-8", $mac);
          $mac = strtoupper($mac);

          $pattern = array('?', '?', '?', '?'); // ru
          $replace = array('A', 'B', 'C', 'E'); // en

          $mac = str_replace($pattern, $replace, trim($mac));

          if (strlen($mac)==12){
              $mac = substr($mac, 0,2).":".substr($mac, 2,2).":".substr($mac, 4,2).":".substr($mac, 6,2).":".substr($mac, 8,2).":".substr($mac, 10,2);
          }

          if (strlen($mac)==17){
              return $mac;
          }else{
          	$this->set_message('normalizeMac', 'Invalid MAC Address!');
              return false;

          }
      }

  	function valid_login($login)
  	{

  		$ci = & get_instance();
  		$stb = $ci->load->database('stalker',true);
  		$sql = $stb->where('login',$login)->get('users');
  		if ($sql->num_rows() == 0) {
  			return true;
  		}else{
  			$this->set_message('valid_login', 'Duplicate user! Please use different Login.');
              return FALSE;
  		}
  	}

  	function username($username){
  		$ci = & get_instance();
  		$sql = $ci->db->where('username',$username)->get('users');
  		if ($sql->num_rows() == 0) {
  			return true;
  		}else{
  			$this->set_message('username', 'Duplicate user! Please use different Login.');
              return FALSE;
  		}
  	}

    function is_unique_mac($MAC){
      $ci = & get_instance();
      $stb = $ci->load->database('stalker',true);
  		$sql = $stb->where('mac',$MAC)->get('users');
  		if ($sql->num_rows() == 0) {
  			return true;
  		}else{
  			$this->set_message('is_unique_mac', 'Duplicate MAC Address! Please use different MAC Address.');
        return FALSE;
  		}
    }

}

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */
