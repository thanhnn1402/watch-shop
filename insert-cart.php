<?php
    $conn = mysqli_connect('localhost', 'root', '', 'watch-shop') or die('Couldn\'t connect to Database');

    $error = array();
    session_start();

    if(isset($_SESSION['user_logged'])) {
        $user = $_SESSION['user_logged'];

        $id_san_pham = isset($_GET['id']) ? $_GET['id'] : '';
        $soluong = isset($_GET['soluong']) ? $_GET['soluong'] : '';

        if(!(empty($id_san_pham))) {
            
            $sql = "SELECT * FROM gio_hang WHERE id_khach_hang = {$user['id']} AND id_san_pham = {$id_san_pham}";
            $query = mysqli_query($conn, $sql);

            if(mysqli_num_rows($query) > 0) {
                $row = mysqli_fetch_assoc($query);

                $soluong += $row['so_luong'];

                $sql = "UPDATE `gio_hang` SET `so_luong`='{$soluong}' WHERE id_khach_hang = {$user['id']} AND id_san_pham = {$id_san_pham}";
                $query = mysqli_query($conn, $sql);
                if($query) {
                    echo "<script>
                            alert('Đã thêm vào giỏ hàng thành công!');
                            window.location.href = './index.php';
                         </script>";
                } else {
                    echo "<script>
                            alert('Có lỗi trong quá trình xử lý, Vui lòng thử lại!');
                         </script>";
                }
            } else {
                $sql = "INSERT INTO `gio_hang`(`id`, `id_khach_hang`, `id_san_pham`, `so_luong`) VALUES ('{$user['id']}','{$user['id']}','{$id_san_pham}','{$soluong}')";
                $query = mysqli_query($conn, $sql);
                if($query) {
                    echo "<script>
                            alert('Đã thêm vào giỏ hàng thành công!');
                            window.location.href = './index.php';
                         </script>";
                } else {
                    echo "<script>
                            alert('Có lỗi trong quá trình xử lý, Vui lòng thử lại!');
                         </script>";
                }
            }

        }
    } else {
        $_SESSION['previous-page'] = $_SERVER['HTTP_REFERER'];

        echo "<script>
                alert('Vui lòng đăng nhập để tiếp tục!');
                window.location.href = './login.php';
            </script>";
    }
?> 