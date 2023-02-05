<?php 
     $conn = mysqli_connect('localhost', 'root', '', 'watch-shop') or die('Couldn\'t connect to Database');

     session_start();
 
     if(!isset($_SESSION['admin_logged'])) {
         header('location: ../../login.php');
     }
 
     $id_user = isset($_GET['id']) ? $_GET['id'] : '';

     if(!empty($id_user)) {
        $sql = "DELETE FROM khach_hang WHERE id = $id_user";

        if(mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('Xóa tài khoản thành công!');
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