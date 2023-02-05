<?php 
    $conn = mysqli_connect('localhost', 'root', '', 'watch-shop') or die('Couldn\'t connect to Database');

    $error = array();
    session_start();

    $cart = 0;

    if(isset($_SESSION['user_logged'])) {
        $user = $_SESSION['user_logged'];
    }

    $noi_dung = isset($_POST['content']) ? $_POST['content'] : '';
    $html = '';

    $sql = "SELECT san_pham.ten_sp, loai_hang.ten_loai
            FROM san_pham, loai_hang
            WHERE san_pham.id_loai_hang = loai_hang.id AND san_pham.ten_sp LIKE '%{$noi_dung}%' OR loai_hang.ten_loai LIKE '%{$noi_dung}%'";

    $query = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($query)) {
        $html .= '<li class="list-group-item">
                        <a href="shop.php?search=' .$row['ten_sp']. '">
                            ' .$row['ten_sp']. '
                        </a>
                    </li>';
    }

    echo $html;

    
?>