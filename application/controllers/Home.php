<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
        $this->db->where('Username',"thawit");
        $this->db->where('password',"a1234");
        $User=    $this->db->get('Users')->result();
echo "<pre>";
print_r($User);

echo "</pre>";

        		// $this->load->view('Home_view');
	}
    public function insert()
    {
        $insert = array('Username'=>'fiwfiw','password'=>'z1212');
        $this->db->insert('Users',$insert);
        $id = $this->db->insert_id();
        echo $id;
    }
    public function login()
	{
		$this->load->view('login_view');
	}
    public function update()
    {
        $update = db->where('Username','fiwfiw','password','z1212');
        
    }
}
