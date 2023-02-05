<?php 
    function reArrayFiles(&$file_post) {

        $file_array = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);
        
        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_array[$i][$key] = $file_post[$key][$i];
            }
        }
        
        return $file_array;
    }

    function sumCart($conn, $id_user) {
        $data = array();
        $sql = "SELECT COUNT(id) AS total FROM gio_hang WHERE id_khach_hang={$id_user}";
        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($query);

        return $data['total'];
    }
?>