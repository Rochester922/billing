<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tickets extends AdminController {

	protected $module_name = 'Tickets';

	public function __construct() {
		parent::__construct();
		$this->load->helper('ticket_helper');
	}

	public function index() {
		$this->data['title'] = 'Tickets';
		$this->data['module'] = $this->module_name;
		$did = 0;
		$this->data['dealer_id'] = $did;
		$this->data['tickets_enabled'] = 1;


		$tickets_query = "SELECT id, subject, status_id, priority_id, category_id, updated_at, user_id, agent_id FROM ".$this->db->dbprefix('tickets')." WHERE status_id<>'2'";


		$this->data['sql'] = $this->db->query($tickets_query);

		$stalker_db = $this->load->database('stalker', TRUE);
		$stalker_category_query = "SELECT id, title FROM tv_genre ORDER by id";
		$stalker_category_result = $stalker_db->query($stalker_category_query);
		$category_array = array();
		foreach ($stalker_category_result->result() as $item) {
			$category_array[$item->id] = $item->title;
		}
		$this->data['category'] = $category_array;

		$this->render('tickets/index');
	}

	public function create() {
		$this->data['title'] = 'Create New Ticket';
		$this->data['module'] = $this->module_name;

		$did = 0;

		$this->form_validation->set_rules('priority', 'Priority', 'trim|required');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required');
		$this->form_validation->set_rules('priority', 'Priority', 'trim|required|numeric');
		$this->form_validation->set_rules('channel_number', 'Channel Number', 'trim|required|numeric');
		$this->form_validation->set_rules('category', 'Category', 'trim|required|numeric');
		$this->form_validation->set_rules('channel', 'Channel', 'trim|required|numeric');

		if ($this->form_validation->run() == TRUE) {

			$tickets_data = array(
				'subject' => $this->input->post('subject'),
				'html' => $this->input->post('description'),
				'content' => str_replace(array("\r\n", "\r", "\n"), ' ', strip_tags($this->input->post('description'))),
				'status_id' => 1,
				'user_id' => $did,
				'created_at' => time(),
				'updated_at' => time(),
				'priority_id' => $this->input->post('priority'),
				'channel_number' => $this->input->post('channel_number'),
				'category_id' => $this->input->post('category'),
				'channel_id' => $this->input->post('channel'),
				'no_audio' => is_null($this->input->post('no_audio')) ? 0 : 1,
				'no_video' => is_null($this->input->post('no_video')) ? 0 : 1,
				'stream_error' => is_null($this->input->post('stream_error')) ? 0 : 1,
				'no_epg' => is_null($this->input->post('no_epg')) ? 0 : 1,
				'catch_up_needed' => is_null($this->input->post('catch_up_needed')) ? 0 : 1,
				'epg_needed' => is_null($this->input->post('epg_needed')) ? 0 : 1,
				'file_missing' => is_null($this->input->post('file_missing')) ? 0 : 1,
				'wrong_channel_name' => is_null($this->input->post('wrong_channel_name')) ? 0 : 1
			);


			if ($this->db->insert('tickets', $tickets_data)) {
				$this->session->set_flashdata('success_msg', '<div class="alert alert-success">Ticket was successfully created.</div>');
			}

			redirect(site_url('admin/tickets'), 'refresh');

		}
		$stalker_db = $this->load->database('stalker', TRUE);
		$stalker_category_query = "SELECT id, title FROM tv_genre ORDER by id";
		$stalker_category_result = $stalker_db->query($stalker_category_query);
		$category_array = array();
		foreach ($stalker_category_result->result() as $item) {
			$category_array[$item->id] = $item->title;
		}
		$this->data['category'] = $category_array;

		$this->render('tickets/create');
	}

	public function channel_category_and_id() {
		if (isset($_GET["number"])) {
			$number = $this->input->get('number');
			$stalker_db = $this->load->database('stalker', TRUE);
			$stalker_channels_query = "SELECT id, name, number, tv_genre_id FROM itv  WHERE number='" . intval($number) . "' LIMIT 1";
			$stalker_channels_result = $stalker_db->query($stalker_channels_query);
			$channels_array = array();
			foreach ($stalker_channels_result->result() as $item) {
				array_push($channels_array, $item);
			}
			echo json_encode($channels_array);
		}

	}

	public function channels() {
		if (isset($_GET["id"])) {
			$category_id = $this->input->get('id');
			$stalker_db = $this->load->database('stalker', TRUE);
			$stalker_channels_query = "SELECT id, name, number, tv_genre_id FROM itv  WHERE tv_genre_id='" . intval($category_id) . "' ORDER by id";
			$stalker_channels_result = $stalker_db->query($stalker_channels_query);
			$channels_array = array();
			foreach ($stalker_channels_result->result() as $item) {
				array_push($channels_array, $item);
			}
			echo json_encode($channels_array);
		}
	}

	public function complete() {
		$this->data['title'] = 'Tickets';
		$this->data['module'] = $this->module_name;
		$did = 0;
		$this->data['dealer_id'] = $did;
		$this->data['tickets_enabled'] = 1;


		$tickets_query = "SELECT id, subject, status_id, priority_id, category_id, updated_at, user_id, agent_id FROM ".$this->db->dbprefix('tickets')." WHERE status_id='2'";


		$this->data['sql'] = $this->db->query($tickets_query);

		$stalker_db = $this->load->database('stalker', TRUE);
		$stalker_category_query = "SELECT id, title FROM tv_genre ORDER by id";
		$stalker_category_result = $stalker_db->query($stalker_category_query);
		$category_array = array();
		foreach ($stalker_category_result->result() as $item) {
			$category_array[$item->id] = $item->title;
		}
		$this->data['category'] = $category_array;

		$this->render('tickets/complete');
	}


	function view($id) {

		$id = base64_decode($id);
		$this->data['title'] = 'Ticket Info';
		$this->data['module'] = $this->module_name;

		if (empty($id) || !is_numeric($id)) {
			show_404();
		} else {
			$did = 0;
			$this->data['tickets_enabled'] = 1;


			$sql = $this->db->where('id', $id)->get('tickets');


			if ($sql->num_rows() == 0) {
				show_404();
				exit();
			} else {

				$sql_ticket = $this->db->where('id', $id)->get('tickets');
				$this->data['sql_ticket'] = $sql_ticket;
				$this->data['did'] = 0;

				$ticket_id = $sql_ticket->result()[0]->id;

				$stalker_db = $this->load->database('stalker', TRUE);
				$stalker_category_query = "SELECT id, title FROM tv_genre ORDER by id";
				$stalker_category_result = $stalker_db->query($stalker_category_query);
				$category_array = array();
				foreach ($stalker_category_result->result() as $item) {
					$category_array[$item->id] = $item->title;
				}
				$this->data['category'] = $category_array;
				$ticket_comment_query = "SELECT * FROM " . $this->db->dbprefix('tickets_comments') . " WHERE ticket_id='" . $ticket_id . "' ORDER by created_at ";
				$ticket_comment_result = $this->db->query($ticket_comment_query);
				$this->data['sql_comments'] = $ticket_comment_result;
				$this->data['tickets_enabled'] = 1;
				$this->render('tickets/view');
			}
		}
	}

	public function comment() {

		$id = $this->input->post('ticket_id');
		$redirect = $this->input->post('redirect');
		$did = 0;
		$this->form_validation->set_rules('comment', 'Comment', 'trim|required');
		$this->form_validation->set_rules('ticket_id', 'Ticket ID', 'trim|required');
		if ($this->form_validation->run() == TRUE) {

			$comment_data = array(
				'ticket_id' => $id,
				'content' => str_replace(array("\r\n", "\r", "\n"), ' ', strip_tags($this->input->post('comment'))),
				'html' => $this->input->post('comment'),
				'user_id' => 0,
				'created_at' => time(),
				'updated_at' => time()
			);
			if ($this->db->insert('tickets_comments', $comment_data)) {
				$this->session->set_flashdata('success_msg', '<div class="alert alert-success">Comment has been added successfully.</div>');
				redirect(site_url('admin/tickets/view/' . $redirect), 'refresh');
			}
		}
	}

	public function reopen() {

		$id = base64_decode($this->input->post('ticket_id'));
		$redirect = $this->input->post('redirect');
		$did = 0;
		$this->form_validation->set_rules('ticket_id', 'Ticket ID', 'trim|required');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required');
		if ($this->form_validation->run() == TRUE) {
			if ($this->db->set('status_id', '3', FALSE)->set('updated_at', time(), FALSE)->set('agent_id', $did, FALSE)->where('id', $id)->update('tickets')) {
				$this->session->set_flashdata('success_msg', '<div class="alert alert-success">The ticket ' . $this->input->post('subject') . ' has been reopened!</div>');
				redirect(site_url('admin/tickets'), 'refresh');
			}
		}
	}

	public function markcomplete() {

		$id = base64_decode($this->input->post('ticket_id'));
		$redirect = $this->input->post('redirect');
		$did = 0;
		$this->form_validation->set_rules('ticket_id', 'Ticket ID', 'trim|required');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required');
		if ($this->form_validation->run() == TRUE) {
			if ($this->db->set('status_id', '2', FALSE)->set('updated_at', time(), FALSE)->set('agent_id', $did, FALSE)->where('id', $id)->update('tickets')) {
				$this->session->set_flashdata('success_msg', '<div class="alert alert-success">The ticket ' . $this->input->post('subject') . ' has been completed!</div>');
				redirect(site_url('admin/tickets'), 'refresh');
			}
		}
	}

	public function updateticket() {

		$id = base64_decode($this->input->post('ticket_id'));
		$redirect = $this->input->post('redirect');
		$did = 0;
		$this->form_validation->set_rules('ticket_id', 'Ticket ID', 'trim|required');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required');
		$this->form_validation->set_rules('status', 'Subject', 'trim|required|numeric');
		$this->form_validation->set_rules('priority', 'Subject', 'trim|required|numeric');
		if ($this->form_validation->run() == TRUE) {
			$priority_id = intval($this->input->post('priority'));
			$status_id = intval($this->input->post('status'));
			if ($this->db->set('status_id', '2', FALSE)->set('updated_at', time(), FALSE)->set('priority_id', $priority_id, FALSE)->set('status_id', $status_id, FALSE)->set('agent_id', $did, FALSE)->where('id', $id)->update('tickets')) {
				$this->session->set_flashdata('success_msg', '<div class="alert alert-success">The ticket ' . $this->input->post('subject') . ' has been edited!</div>');
				redirect(site_url('admin/tickets'), 'refresh');
			}
		}
	}

	public function delete() {

		$id = base64_decode($this->input->post('ticket_id'));
		$redirect = $this->input->post('redirect');
		$this->form_validation->set_rules('ticket_id', 'Ticket ID', 'trim|required');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required');
		if ($this->form_validation->run() == TRUE) {
			if ($this->db->where('id', $id)->delete('tickets')) {
				$this->session->set_flashdata('success_msg', '<div class="alert alert-success">The ticket ' . $this->input->post('subject') . ' has been deleted!</div>');
				redirect(site_url('admin/tickets'), 'refresh');
			}
		}
	}

	public function dashboard() {
		$data['title'] = 'Dashboard';

		$sql_close = $this->db->where('status_id', 2)->get('tickets');
		$data['close_tickets'] = 0;
		if ($sql_close->num_rows() > 0) {
			$data['close_tickets'] = $sql_close->num_rows();
		}

		$sql_open = $this->db->where('status_id != ', 2)->get('tickets');
		$data['open_tickets'] = 0;
		if ($sql_open->num_rows() > 0) {
			$data['open_tickets'] = $sql_open->num_rows();
		}

		$data['total_tickets'] = $data['close_tickets'] + $data['open_tickets'];

		$category_table_array = array();

		$sql_table = $this->db->select('status_id, category_id')->get('tickets');

		foreach ($sql_table->result() as $key => $value) {
			if (!isset($category_table_array[$value->category_id])) {
				$category_table_array[$value->category_id] = array("category_id" => $value->category_id, "open" => 0, "close" => 0);
			}
			if ($value->status_id == "1" || $value->status_id == "3") $category_table_array[$value->category_id]["open"] += 1;
			if ($value->status_id == "2") $category_table_array[$value->category_id]["close"] += 1;
		}

		$data['category_table_array'] = $category_table_array;

		$stalker_db = $this->load->database('stalker', TRUE);
		$stalker_category_query = "SELECT id, title FROM tv_genre ORDER by id";
		$stalker_category_result = $stalker_db->query($stalker_category_query);
		$category_array = array();
		foreach ($stalker_category_result->result() as $item) {
			$category_array[$item->id] = $item->title;
		}
		$data['category'] = $category_array;

		$this->load->view('admin/tickets_dashboard', $data);
	}

}
