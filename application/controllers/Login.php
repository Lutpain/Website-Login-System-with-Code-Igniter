<?php defined('BASEPATH') OR exit ('No direct script access allowed');

class Login extends CI_Controller {

    public function index()
    {
        $this->load->view('login_view');
    }

    public function process()
    {
    	$post = $this->input->post(null, TRUE);
    	if (isset($post['login_view']))	{
    		$this->load->model('User_m');
    		$query = $this->User_m->login($post);
    		if ($query->num_rows() > 0) {
    			echo "Login Berhasil";
    		}
    	}
    }

}



?>