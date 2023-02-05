<?php 
    $conn = mysqli_connect('localhost', 'root', '', 'watch-shop') or die('Couldn\'t connect to Database');

    session_start();
 
    if(isset($_SESSION['user_logged'])) {
        $user = $_SESSION['user_logged'];

        $id_san_pham = isset($_GET['id']) ? $_GET['id'] : '';

        if(!empty($id_san_pham)) {
            $sql = "DELETE FROM gio_hang WHERE id_khach_hang={$user['id']} AND id_san_pham={$id_san_pham}";
    
            if(mysqli_query($conn, $sql)) {
                echo "<script>
                        alert('Xóa sản phẩm thành công!');
                        window.location.href = './cart.php';
                    </script>";
            } else {
                echo "<script>
                        alert('Có lỗi trong quá trình xử lý, vui lòng thử lại!');
                        window.location.href = './cart.php';
                    </script>";
            }
         }

    } else {
        header('location: ./login.php');
    }
?>