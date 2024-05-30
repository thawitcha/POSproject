<?php
defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header('Access-Control-Allow-Origin: *');

class Product extends CI_Controller
{
    public function getProduct() {
        $menuAll =   $this->db->select('m.name, m.price, m.status, m.total, m.pic_url, c.cg_name, f.fg_name, m.barcode, m.menu_code')
                 ->from('menu m')
                 ->join('catagory c', 'm.cg_id = c.cg_id')
                 ->join('food_group f', 'c.fg_id = f.fg_id')
                 ->join('store s', 's.id_res_auto = f.id_res_auto')
                 ->where('s.id_res_auto', 1)
                 ->order_by('m.m_id', 'asc');
        
        $menuAll = $this->db->get();
    
        if ($menuAll->num_rows() > 0) {
            $menuData = $menuAll->result(); // ดึงข้อมูลเมนูทั้งหมดเป็น array ของ objects
            echo json_encode($menuData); // แปลงข้อมูลเป็น JSON และส่งออก
        } else {
            echo json_encode(0); // ส่งค่า 0 ในกรณีที่ไม่มีข้อมูล
        }
    }

    public function updateProduct(){
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
            $menu_code = $data['menu_code'];

        $update_data = array(
            
            'name' => $name,
            'price' => $price,
            'pic_url' => $pic_url,
            'menu_code' => $menu_code,
        );
        $this->db->where('m_id', $m_id);
        $this->db->update('menu', $update_data);
        
          echo 1;
        }
    }

    public function deleteFood_Group() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
    
        if ($data === null) {
            echo json_encode(array("error" => "Error decoding JSON"));
            return;
        } else {
            $fg_id = $data['fg_id'];                                         // รับค่า f_id ที่ต้องการลบ
            $this->db->where('fg_id', $fg_id);                               // กำหนดเงื่อนไขสำหรับการลบ (โดยใช้ f_id เพื่อหาข้อมูลที่ต้องการลบ)
            $this->db->delete('food_group');                                     // ทำการลบข้อมูลในฐานข้อมูล
            if ($this->db->affected_rows() > 0) {                          // ตรวจสอบว่ามีการลบข้อมูลหรือไม่
                echo json_encode(array("success" => true));                // ส่งข้อมูลกลับเป็น JSON บอกว่าลบสำเร็จ
            } else {
                echo json_encode(array("error" => "Data not found"));      // ส่งข้อมูลกลับเป็น JSON บอกว่าไม่พบข้อมูลที่ต้องการลบ
            }
        }
    }

}