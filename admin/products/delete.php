<?php 
     $conn = mysqli_connect('localhost', 'root', '', 'watch-shop') or die('Couldn\'t connect to Database');

     session_start();
 
     if(!isset($_SESSION['admin_logged'])) {
         header('location: ../../login.php');
     }
 
     $id_san_pham = isset($_GET['id']) ? $_GET['id'] : '';

     if(!empty($id_san_pham)) {
        $sql = "DELETE FROM san_pham WHERE id = $id_san_pham";

        if(mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('Xóa sản phẩm thành công!');
                    window.location.href = 'list.php';
                </script>";
        } else {
            echo "<script>
                    alert('Có lỗi trong quá trình xử lý, vui lòng thử lại!');
                    window.location.href = 'list.php';
                </script>";
        }
     }
?>