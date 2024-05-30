<?php
defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header('Access-Control-Allow-Origin: *');

class menu extends CI_Controller
{


    public function getListMenu()
{
    $query = $this->db->get('menu');
    $menu_array = array(); 

    foreach ($query->result() as $row) {
        // เพิ่มข้อมูลจากฐานข้อมูลลงใน array
        $menu_array[] = array(
            'm_id' => $row->m_id,
            'name' => $row->name,
            'price'=> $row->price,
            'pic_url'=> $row->pic_url,
        );
    }
    echo json_encode($menu_array);
    return $menu_array; 
}
// insert เพิ่มข้อมูล
public function insertData(){
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    
    if ($data === null) {
        echo "Error decoding JSON";
        return;
    }else{
        $name= $data['name']; 
        $price= $data['price']; 
        $pic_url= $data['pic_url']; 
        
        $insert = array(
            'name' => $name,
            'price'=> $price,
            'pic_url'=> $pic_url,
        );
     
            
            $this->db->insert('menu', $insert);
      echo 1;
    }

}
// 
    public function updateData(){
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
    
        if ($data === null) {
            echo "Error decoding JSON";
            return;
        }else{
            $m_id= $data['m_id'];
            $name= $data['name']; 
            $price= $data['price']; 
            $pic_url= $data['pic_url']; 
        $update_data = array(
            
            'name' => $name,
            'price' => $price,
            'pic_url' => $pic_url,
        );
        $this->db->where('m_id', $m_id);
        $this->db->update('menu', $update_data);
        
          echo 1;
        }
    }
    public function delete() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
    
        if ($data === null) {
            echo json_encode(array("error" => "Error decoding JSON"));
            return;
        } else {
            $m_id = $data['m_id'];                                         // รับค่า f_id ที่ต้องการลบ
            $this->db->where('m_id', $m_id);                               // กำหนดเงื่อนไขสำหรับการลบ (โดยใช้ f_id เพื่อหาข้อมูลที่ต้องการลบ)
            $this->db->delete('menu');                                     // ทำการลบข้อมูลในฐานข้อมูล
            if ($this->db->affected_rows() > 0) {                          // ตรวจสอบว่ามีการลบข้อมูลหรือไม่
                echo json_encode(array("success" => true));                // ส่งข้อมูลกลับเป็น JSON บอกว่าลบสำเร็จ
            } else {
                echo json_encode(array("error" => "Data not found"));      // ส่งข้อมูลกลับเป็น JSON บอกว่าไม่พบข้อมูลที่ต้องการลบ
            }
        }
    }
    

}
