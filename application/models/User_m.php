<?php defined('BASEPATH') OR exit ('No direct script access allowed');

class User_m extends CI_Model {

    public function login($post)
    {
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('username', $post['username']);
        $this->db->where('password', $post['password']);
        $query = $this->db->get();
        return $query;

    }





}

?>