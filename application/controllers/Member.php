<?php
defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header('Access-Control-Allow-Origin: *');

class Member extends CI_Controller
{
    public function insertMember()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json,true);
        if($data === null ){
            echo "Error decoding JSON";
            return;
        } else {
            
            $member_name = $data['member_name'];
            $member_phone = $data['member_phone'];
            $member_type = $data['member_type'];
            $member_points = $data['member_points'];

            $insert = array(
                
                'member_name' => $member_name,
                'member_phone' => $member_phone,
                'member_type' => $member_type,
                'member_points' => $member_points,
            );
            $this->db->insert('member',$insert);
            echo 1;
        }

    }
    public function deleteMember() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
    
        if ($data === null) {
            echo json_encode(array("error" => "Error decoding JSON"));
            return;
        } else {
            $member_id = $data['member_id'];
            $this->db->where('member_id', $member_id);
            // ทำการลบข้อมูลในฐานข้อมูล
            $this->db->delete('member');
            // ตรวจสอบว่ามีการลบข้อมูลหรือไม่
            if ($this->db->affected_rows() > 0) {
                // ส่งข้อมูลกลับเป็น JSON บอกว่าลบสำเร็จ
                echo json_encode(array("success" => true));
            } else {
                // ส่งข้อมูลกลับเป็น JSON บอกว่าไม่พบข้อมูลที่ต้องการลบ
                echo json_encode(array("error" => "Data not found"));
            }
        }
    }
    public function getMembersBySearch($searchTerm) {
        $this->db->select('id, member_name, member_phone, member_type, member_points');
        $this->db->from('member');
        $this->db->like('member_name', $searchTerm);
        $this->db->or_like('member_phone', $searchTerm);
        $this->db->or_like('member_type', $searchTerm);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }
}