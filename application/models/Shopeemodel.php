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
    
    function map_nhanh_id_mysql($shopee_id)
    {
        $query = $this->db->query('SELECT nhanh_id FROM id_map WHERE shopee_id = ' . $shopee_id);
        return $query -> row() -> nhanh_id;
    }
}
?>