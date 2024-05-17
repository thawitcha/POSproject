<?php
defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header('Access-Control-Allow-Origin: *');

class Bill extends CI_Controller
{
    public function insertOrderDetail()
    {

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if ($data === null) {
            echo "Error decoding JSON";
            return;
        } else {
            $date_time = date('Y-m-d H:i:s');
            $id_res_auto = $data['id_res_auto'];

            $insert = array(
                'date_time' => $date_time,
                'id_res_auto' => $id_res_auto,
            );
            $this->db->insert('order_main', $insert);
            $id=$this->db->insert_id();
            
            $m_id = $data['m_id'];
            $total = $data['total'];
            $order_id_main = $id;
            $id_res_auto = $data['id_res_auto'];

            $insert = array(
                'm_id' => $m_id,
                'total' => $total,
                'order_id_main' => $order_id_main,
                'id_res_auto' => $id_res_auto,
            );
            $this->db->insert('order_detail', $insert);
            echo 1;
        }
    }
    public function getOrderBill() {
      
        $menuAll =   $this->db->select('rm.order_id_main, m.price,rd.total,rm.date_time')
        ->from('menu m')
        ->join('order_detail rd', 'rd.m_id = m.m_id')
        ->join('order_main rm', 'rm.order_id_main = rd.order_id_main')
        ->where('rm.id_res_auto', 1)
        ->order_by('rm.order_id_main', 'asc');
        
        $menuAll = $this->db->get();
    
        if ($menuAll->num_rows() > 0) {
            $menuData = $menuAll->result(); // ดึงข้อมูลเมนูทั้งหมดเป็น array ของ objects
            echo json_encode($menuData); // แปลงข้อมูลเป็น JSON และส่งออก
        } else {
            echo json_encode(0); // ส่งค่า 0 ในกรณีที่ไม่มีข้อมูล
        }
    }
    public function getBillHistory() {
        $this->db->where('id_res_auto', 1);
        $query = $this->db->get('order_main');
        $rm_array = array();
    ///////////// 2 ข้อมูล
        foreach ($query->result() as $row) {
       
            $this->db->select('m_id, total');
            $this->db->where('order_id_main', $row->order_id_main);
             $order_detail_query= $this->db->get('order_detail');
            ///////////// 4 ข้อมูล
            $total_price = 0;
            
            foreach ($order_detail_query->result() as $detail_row) {
          
                $this->db->select('price');
                $this->db->where('m_id', $detail_row->m_id);
                $menu_query = $this->db->get('menu');
                ///////////// 4 ข้อมูล
                if ($menu_query->num_rows() > 0) {
                    $menu_row = $menu_query->row();
                    // คูณราคากับจำนวน total
                    $total_price += $menu_row->price * $detail_row->total;
                }
            
            }
    
            $rm_array[] = array(
                'order_id_main' => $row->order_id_main,
                'date_time' => $row->date_time,
                'total_price' => $total_price
                // 'payment_type' => $payment_type
            );
        }
    
        echo json_encode($rm_array);
        return $rm_array;
    }
    
    
}
