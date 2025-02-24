<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends ManagerController {

  public function __construct() {
      parent::__construct();
      //Do your magic here
  }

  public function index() {
      $this->session->unset_userdata('auth_info');
      redirect('login','refresh') ;
  }

}

/* End of file Logout.php */
/* Location: ./application/modules/admin/controllers/Logout.php */