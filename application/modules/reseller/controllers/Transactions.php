<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transactions extends ResellerController {

    public function __construct() {
        parent::__construct();
        //Do your magic here
    }

    public function index() {
        $this->data['title']  = 'My Transactions';
        $this->data['module'] = 'My Transactions';
        $this->data['sql']    = $this->transaction_model->get_all($this->user['username']);
        $this->render('common/transactions');
    }

}

/* End of file Transactions.php */
/* Location: ./application/modules/reseller/controllers/Transactions.php */
