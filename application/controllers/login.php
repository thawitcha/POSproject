<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class login extends CI_Controller {
	
     public function index()
     {  
         $this->load->view('login_view');
    $User=    $this->db->get('User');

        print_r($User);
     }
     public function process()
    {
        // ประมวลผลการเข้าสู่ระบบ
        // ตรวจสอบข้อมูลที่ส่งมาจากฟอร์ม
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        
        // ตรวจสอบข้อมูลในฐานข้อมูลหรือระบบอื่น ๆ
        // และดำเนินการตามเงื่อนไขต่าง ๆ ตามที่คุณต้องการ
    }
}