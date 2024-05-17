<?php
defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header('Access-Control-Allow-Origin: *');

class FoodGroup extends CI_Controller
{

    //ฟังก์ชันเพ่ิมรายการอาหาร
    public function insertFoodGroup(){

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if ($data === null) {
            echo "Error decoding JSON";
            return;
        }else{

        $fg_name= $data['fg_name']; 
        $id_res_auto= $data['id_res_auto']; 
        $insert = array(
            'fg_name' => $fg_name,
            'id_res_auto' =>$id_res_auto,
        );
        $this->db->insert('food_group', $insert);
        echo 1;
    }
    }

    //ฟังก์ชันเพิ่มหมวดหมู่อาหาร
    public function insertCatagory(){

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if ($data === null) {
            echo "Error decoding JSON";
            return;
        }else{

        $cg_name= $data['cg_name'];
        $cg_img= $data['cg_img']; 
        $fg_id= $data['fg_id'];

        $insert = array(
            'cg_name' => $data['cg_name'],
            'cg_img' =>$cg_img,
            'fg_id' =>$fg_id,
        );
        $this->db->insert('catagory', $insert);
        echo 1;
    }
    }

    //ฟังก์ชันเพิ่มเมนู
    public function insertMenu(){
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if ($data === null) {
            echo "Error decoding JSON";
            return;
        } else {
            foreach ($data as $item) {
                $name= $item['name']; 
                $cg_id= $item['cg_id'];
                $price=$item['price'];
                $detail=$item['detail'];
                $status=$item['status'];
                $is_buffet=$item['is_buffet'];
                $total=$item['total'];
                $pic_url=$item['pic_url'];
                
                $insert = array(
                    'name' => $name,
                    'cg_id' =>$cg_id,
                    'price' =>$price,
                    'detail' =>$detail,
                    'status' =>$status,
                    'is_buffet' =>$is_buffet,
                    'total' =>$total,
                    'pic_url' =>$pic_url,
                );
                $this->db->insert('menu', $insert);
            }
            echo 1;
        }
    }
    

    //ฟังก์ชันแสดงข้อมูลรายการทั้งหมด
        public function getMenuAll() {
        $menuAll =   $this->db->select('m.name, m.price, m.status, m.total, m.pic_url, c.cg_name, f.fg_name')
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
    
   
}
