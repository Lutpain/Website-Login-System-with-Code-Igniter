<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __constract()
	{
		parent::__constract();
		$this->load->library('form_validation');
	}

	public function index()
	{
		if($this->session->userdata('email')){
			redirect('user');
		}
		
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		if ($this->form_validation->run() == false){
			$data['title'] = 'GOSU Login Page';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('login/login_v');
			$this->load->view('templates/auth_footer');	
		} else {
			//validasi success
			$this->_login();
		}
		
	}

	private function _login(){
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$user = $this->db->get_where('user', ['email' => $email])->row_array();
		//jika usernya ada
		if ($user) {
			//usernya aktif
			if($user['is_active'] == 1){
				//cek password
				if(password_verify($password, $user['password'])){
					$data = [
						'email' =>$user['email'],
						'role_id' =>$user['role_id']
					];
					$this->session->set_userdata($data);
					if($user['role_id'] == 1){
						redirect('admin');
					} 
					redirect('user');
				} else {
					$this->session->set_flashdata('message', '<div class=" alert alert-danger" role="alert">Wrong Password!</div>');		
					redirect('auth');
				}
			} else {
				$this->session->set_flashdata('message', '<div class=" alert alert-danger" role="alert">This email has not been activated. Please actived your email first</div>');	
				redirect('auth');
			}
		} else {
			$this->session->set_flashdata('message', '<div class=" alert alert-danger" role="alert">Email is not registered. Please register your email first</div>');			
			redirect('auth');
		}
	}

	public function registration()
	{
		if($this->session->userdata('email')){
			reirect('user');
		}

		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
				'is_unique' => 'This email already registered!'
		]);
		$this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
				'matches' => 'Password dont match!',
				'min_length' => 'Password to short!'
		]);
		$this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');


		if($this->form_validation->run() == false)	{
			$data['title'] = 'GOSU User Registration';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('login/registration_v');
			$this->load->view('templates/auth_footer');
		} else {
			$data = [
				'name' => htmlspecialchars($this->input->post('name', true)),
				'email' => htmlspecialchars($this->input->post('email', true)),
				'image' => 'default.jpg',
				'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
				'role_id' => 2,
				'is_active' => 1,
				'date_created' => time()
			];
			$this->db->insert('user', $data);

			//Verifikasi Email
			// $this->_sendEmail();

			$this->session->set_flashdata('message', '<div class=" alert alert-success" role="alert">Your account has been created. Please login</div>');
			redirect('auth');
		}
	}

	// private function _sendEmail()
	// {
	// 	$config = [
	// 		'protocol' => 'smtp',
	// 		'smtp_host' => 'ssl://smtp.googlemail.com',
	// 		'smtp_user' => 'lutfihidayanto10@gmail.com',
	// 		'smtp_pass' => '5penderitaan',
	// 		'smtp_port' => 465,
	// 		'mailtype' => 'html',
	// 		'charset' => 'utf-8',
	// 		'newline' => "\r\n"
	// 	];
	// 	$this->load->library('email',$config);  
	// 	$this->email->initialize($config);

	// 	$this->email->from('lutfihidayanto10@gmail.com', 'LUTFI HIDAYANTO');
	// 	$this->email->to('lutpain26@gmail.com');
	// 	$this->email->subject('Testing');
	// 	$this->email->message('Hello World!');

	// 	if($this->email->send()){
	// 		return true;
	// 	} else {
	// 		echo $this->email->print_debugger();
	// 		die;
	// 	}
	// }

	public function logout()
	{
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('role_id');
		$this->session->set_flashdata('message', '<div class=" alert alert-success" role="alert">You have been logged out!</div>');
			redirect('auth');
	}

	public function blocked()
	{
		$this->load->view('login/blocked');
	}
}
