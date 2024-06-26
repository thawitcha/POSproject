<?php
defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header('Access-Control-Allow-Origin: *');

class Sale extends CI_Controller{
public function getOrder() {
      
      $menuAll =   $this->db->select('m.price,rd.total,rm.date_time')
      ->from('menu m')
      ->join('order_detail rd', 'rd.m_id = m.m_id')
      ->join('order_main rm', 'rm.order_id_main = rd.order_id_main')
      ->where('rm.id_res_auto', 1)
      ->where('rd.order_id_main',1)
      ->order_by('rm.order_id_main', 'asc');
      
      $menuAll = $this->db->get();
  
      if ($menuAll->num_rows() > 0) {
          $menuData = $menuAll->result(); // ดึงข้อมูลเมนูทั้งหมดเป็น array ของ objects
          echo json_encode($menuData); // แปลงข้อมูลเป็น JSON และส่งออก
      } else {
          echo json_encode(0); // ส่งค่า 0 ในกรณีที่ไม่มีข้อมูล
      }
  }    
  public function insertOrder()
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
          if(!$id){
            echo "Error don't have id";
            return;
          }
          foreach ($data['data_menu'] as $item) {
            $m_id = $item['m_id'];
            $total = $item['total'];
            $insert_detail = array(
                'm_id' => $m_id,
                'total' => $total,
                'order_id_main' => $id,
                'id_res_auto' => $id_res_auto,
            );
            $this->db->insert('order_detail', $insert_detail);
        }
          echo 1;
      }
  }

  public function addOrder(){
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if($data === null){
        echo "Error decoding JSON";
        return;
    } else {
        $date_time = date('Y-m-d H;i;s');
        $id_res_auto = $data['id_res_auto'];

        $insert = array(
            'date_time' => $date_time,
            'id_res_auto'=> $id_res_auto,

        );
        $this->db->insert('order_main',$insert);
        $id=$this->db->insert_id();
        if(!$id){
            echo "Error don't have id";
            return;
        } 

        foreach($data['data_menu'] as $item){
            $m_id = $item['m_id'];
            $total = $item['total'];

            $insert_detail = array(
                'm_id'=> $m_id,
                'total'=> $total,
                'order_id_main'=> $id,
                'id_res_auto'=> $id_res_auto,
            );

            $this->db->insert('order_detail',$insert_detail);
        }
        echo 1;
    }
  }



























}
