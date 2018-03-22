<?php

class ShopeeModel extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function map_nhanh_id($shopee_id)
    {
        $filename = dirname(__FILE__) . "/idmap.csv";
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 1600, ',')) !== FALSE)
            {
                if ($row[0] == $shopee_id)
                    return $row[1];
            }
            fclose($handle);
        }
        return -1;
    }
    
    function map_nhanh_id_mysql($shopee_id, $sku)
    {
        if ($sku == 'H0')
        {
            $sql = 'SELECT nhanh_id FROM id_map WHERE shopee_id = ? ';
            $query = $this->db->query($sql, array( $shopee_id) );
        }
        else{
            $sql = 'SELECT nhanh_id FROM id_map WHERE shopee_id = ? AND sku = ?';
            $query = $this->db->query($sql, array( $shopee_id, $sku) );
        }
        if ($query -> num_rows() > 0)
            return $query -> row() -> nhanh_id;
        else
            return 0;
    }
    
    function check_order_pushed($shopee_order_id)
    {
        $sql = 'SELECT nhanh_order_id FROM shopee_nhanh_order WHERE shopee_order_id = ? ';
        $query = $this->db->query($sql, array( $shopee_order_id) );
        
        if ($query -> num_rows() > 0)
            return $query -> row() -> nhanh_order_id;
        else
            return 0;
    }
    
    function store_pushed_order($shopee_order_id, $nhanh_order_id)
    {
        $data = array(
            'shopee_order_id' => $shopee_order_id,
            'nhanh_order_id' => $nhanh_order_id,
            'shopee_order_serialnumber' => ''
        );
        
        $this->db->insert('shopee_nhanh_order', $data);
    }
}
?>