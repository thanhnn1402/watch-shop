<?php 
    $conn = mysqli_connect('localhost', 'root', '', 'watch-shop') or die('Couldn\'t connect to Database');

    session_start();

    if(!isset($_SESSION['admin_logged'])) {
        header('location: ../../login.php');
    }

    $admin_logged = $_SESSION['user_logged'];

    include('../../libraries/helper.php');

    $error = array();

    $sql = "SELECT id, ten_sp FROM san_pham";
    $query = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($query)) {
        $sanpham[] = $row;
    }

    if(isset($_POST['submit']) && $_POST['submit'] == 'submit') {
        $duong_kinh = isset($_POST['duong_kinh']) ? addslashes($_POST['duong_kinh']) : '';
        $chat_lieu_mat = isset($_POST['chat_lieu_mat']) ? addslashes($_POST['chat_lieu_mat']) : '';
        $chat_lieu_day = isset($_POST['chat_lieu_day']) ? addslashes($_POST['chat_lieu_day']) : '';
        $bo_may = isset($_POST['bo_may']) ? addslashes($_POST['bo_may']) : '';
        $chong_nuoc = isset($_POST['chong_nuoc']) ? addslashes($_POST['chong_nuoc']) : '';
        $thuong_hieu = isset($_POST['thuong_hieu']) ? addslashes($_POST['thuong_hieu']) : '';
        $hang = isset($_POST['hang']) ? addslashes($_POST['hang']) : '';
        $id_san_pham = isset($_POST['id_san_pham']) ? $_POST['id_san_pham'] : '';

        if(empty($duong_kinh)) {
            $error['duong_kinh'] = 'Bạn chưa nhập thông số đường kính mặt';
        }

        if(empty($chat_lieu_day)) {
            $error['chat_lieu_day'] = 'Bạn chưa nhập thông số chất liệu dây đeo';
        }

        if(empty($bo_may)) {
            $error['bo_may'] = 'Bạn chưa nhập thông số bộ máy';
        }

        if(empty($chong_nuoc)) {
            $error['chong_nuoc'] = 'Bạn chưa nhập thông số chống nước';
        }

        if(empty($chat_lieu_mat)) {
            $error['chat_lieu_mat'] = 'Bạn chưa nhập thông số chất liệu mặt kính';
        }

        if(empty($thuong_hieu)) {
            $error['thuong_hieu'] = 'Bạn chưa nhập thương hiệu';
        }

        if(empty($hang)) {
            $error['hang'] = 'Bạn chưa nhập tên hãng';
        }

        if(empty($id_san_pham)) {
            $error['id_san_pham'] = 'Bạn chưa chọn sản phẩm';
        }

        if(!($error)) {
            $sql = "SELECT * FROM chi_tiet_san_pham WHERE id_san_pham = {$id_san_pham}";
            $query = mysqli_query($conn, $sql);

            // Nếu id sản phẩm đã tồn tại dữ liệu trong bảng chi tiết -> tiến hành cập nhật
            if(mysqli_num_rows($query) > 0) {
                $sql = "UPDATE `chi_tiet_san_pham` SET `duong_kinh`='{$duong_kinh}',`chat_lieu_mat`='{$chat_lieu_mat}',`chat_lieu_day`='{$chat_lieu_day}',`bo_may`='{$bo_may}',`chong_nuoc`='{$chong_nuoc}',`thuong_hieu`='{$thuong_hieu}',`hang`='{$hang}' WHERE `id_san_pham`= {$id_san_pham}";

                if(mysqli_query($conn, $sql)) {
                    echo "<script>
                        alert('Cập nhật chi tiết sản phẩm thành công!');
                     </script>";
                } else {
                    echo "<script>
                        alert('Có lỗi trong quá trình xử lý, Vui lòng thử lại!');
                     </script>";
                }

            } else { // Ngược lại -> tiến hành thêm chi tiết cho sản phẩm
                $sql = "INSERT INTO `chi_tiet_san_pham`(`duong_kinh`, `chat_lieu_mat`, `chat_lieu_day`, `bo_may`, `chong_nuoc`, `thuong_hieu`, `hang`, `id_san_pham`) VALUES ('{$duong_kinh}','{$chat_lieu_mat}','{$chat_lieu_day}','{$bo_may}','{$chong_nuoc}','{$thuong_hieu}','{$hang}','{$id_san_pham}')";

                if(mysqli_query($conn, $sql)) {
                    echo "<script>
                            alert('Thêm chi tiết sản phẩm thành công!');
                         </script>";
                } else {
                    echo "<script>
                            alert('Có lỗi trong quá trình xử lý, Vui lòng thử lại!');
                         </script>";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">


    <!-- Base CSS -->
    <link rel="stylesheet" href="../assets/css/base.css">

    <!-- Style CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Responsive CSS -->
    <link rel="stylesheet" href="../assets/css/responsive.css">

    <title>ADMIN</title>
</head>
<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper">

        <!-- Layout menu -->
        <div class="layout-menu layout-menu-fixed offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">

            <!-- Logo brand -->
            <div class="app-brand">
                <a href="" class="">
                    <h5>ADMIN WEBSITE</h5>
                </a>
            </div>

            <div class="menu-profile">
                <img src="<?php echo !empty($admin_logged['avatar']) ? '../../storage/uploads/'. $admin_logged['avatar'] : '../../storage/uploads/1.png' ?>" alt="">
                <p> Welcome <?php echo !empty($admin_logged['ten_tai_khoan']) ? $admin_logged['ten_tai_khoan'] : '' ?> </p>
            </div>

            <button type="button" class="btn-close-nav-mobile" data-bs-dismiss="offcanvas"><i class="fa-solid fa-angle-left"></i></button>


            <!-- Menu inner -->
            
            <ul class="menu-inner">
                <li class="menu-item">
                    <a href="../index.php" class="menu-link">
                        <i class="fa-solid fa-house menu-icon"></i>                        
                        <span>Trang chủ</span>              
                    </a>
                </li>

                <!-- Menu users -->

                <li class="menu-header">
                    <span>Users</span>
                </li>

                <li class="menu-item">
                    <a href="" class="menu-link menu-toggle">
                        <i class="fa-solid fa-users-line menu-icon"></i>                      
                        <span>Khách hàng</span>              
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="../users/list.php" class="menu-link">
                                <span>Danh sách</span>              
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="../users/add.php" class="menu-link">
                                <span>Thêm</span>              
                            </a>
                        </li>
                    </ul>
                </li>


                <!-- Menu products -->

                <li class="menu-header">
                    <span>Products</span>
                </li>

                <li class="menu-item active">
                    <a href="" class="menu-link menu-toggle active">
                        <i class="fa-solid fa-coins menu-icon"></i>                     
                        <span>Sản phẩm</span>              
                    </a>

                    <ul class="menu-sub open">
                        <li class="menu-item">
                            <a href="./list.php" class="menu-link">
                                <span>Danh sách</span>              
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="./add.php" class="menu-link">
                                <span>Thêm sản phẩm</span>              
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="./add-detail.php" class="menu-link active">
                                <span>Thêm chi tiết sản phẩm</span>              
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Menu orders -->

                <li class="menu-header">
                    <span>orders</span>
                </li>

                <li class="menu-item">
                    <a href="" class="menu-link menu-toggle">
                        <i class="fa-solid fa-cart-shopping menu-icon"></i>                     
                        <span>Đơn hàng</span>              
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="../orders/list.php" class="menu-link">
                                <span>Danh sách</span>              
                            </a>
                        </li>

                        
                    </ul>
                </li>
            </ul>

        </div>

        <!-- Layout page inner -->
        <div class="layout-page">
            <!-- Page header -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header">
                            <div class="page-header-left">
                                <button class="btn btn-nav-mobile" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                                    <i class="fa-solid fa-bars"></i>
                                </button>
                                <form class="form-search" role="search">
                                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                                    <input type="search" placeholder="Search..." aria-label="Search">
                                </form>
                            </div>

                            <div class="page-header-right">
                                <div class="notifycation">
                                    <button>
                                        <i class="fa-regular fa-bell"></i>
                                        <span>3</span>
                                    </button>
                                </div>
                                <div class="message">
                                    <button>
                                        <i class="fa-regular fa-envelope"></i>
                                        <span>6</span>
                                    </button>
                                </div>
                                <div class="profile">
                                    <button class="dropdown-btn">
                                        <img src="<?php echo !empty($admin_logged['avatar']) ? '../../storage/uploads/'. $admin_logged['avatar'] : '../../storage/uploads/1.png' ?>" alt="avatar" class="avatar">
                                    </button>
                                    <ul class="dropdown">
                                        <li class="dropdown-item">
                                            <img src="<?php echo !empty($admin_logged['avatar']) ? '../../storage/uploads/'. $admin_logged['avatar'] : '../../storage/uploads/1.png' ?>" alt="avatar" class="avatar">
                                            <div class="dropdown-content">
                                                <p><?php echo !empty($admin_logged['ten_tai_khoan']) ? $admin_logged['ten_tai_khoan'] : '' ?></p>
                                                <span>Admin</span>
                                            </div>
                                        </li>

                                        <li class="divider"></li>

                                        <li class="dropdown-item">
                                            <a href="../my-profile.php" class="dropdown-link">
                                                <i class="fa-regular fa-address-card"></i>
                                                <span>Thông tin tài khoản</span>
                                            </a>
                                        </li>

                                        <li class="dropdown-item">
                                            <a href="" class="dropdown-link">
                                                <i class="fa-regular fa-bell"></i>
                                                <span>Thông báo</span>
                                                <span class="badge bg-danger text-light float-end">3</span>
                                            </a>
                                        </li>

                                        <li class="dropdown-item">
                                            <a href="" class="dropdown-link">
                                                <i class="fa-solid fa-gear"></i>
                                                <span>Cài đặt</span>
                                            </a>
                                        </li>

                                        <li class="divider"></li>

                                        <li class="dropdown-item">
                                            <a href="../../logout.php" class="dropdown-link">
                                                <i class="fa-solid fa-power-off"></i>
                                                <span>Đăng xuất</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="page-title">
                            <P>TRANG CHỦ</P>
                        </div>
                    </div>
                </div>
            </div>
            

            <!-- Page content -->
            <div class="container-fluid">
                <!-- <div class="row mt-4">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb ms-4">
                                <li class="breadcrumb-item"><a href="../index.php" class="text-decoration-none fs-5">Trang chủ</a></li>
                                <li class="breadcrumb-item"><a href="./add.php" class="text-decoration-none fs-5">Khách hàng</a></li>
                                <li class="breadcrumb-item active fs-5" aria-current="page">Thêm tài khoản</li>
                            </ol>
                        </nav>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-content bg-white rounded-3">
                            <form class="" method="POST" action="add-detail.php" enctype="multipart/form-data">
                                <div class="form-group-flex">
                                    <div class="form-group">
                                        <label for="id_san_pham" class="form-label">Sản phẩm</label>
                                        <select class="form-select" id="id_san_pham" name="id_san_pham" aria-label="Default select example">
                                            <?php foreach ($sanpham as $item) { ?>
                                                <option value="<?=$item['id']?>">
                                                    <?=$item['id'] . " - " . $item['ten_sp']?> 
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['id_san_pham'])) ? $error['id_san_pham'] : ''; ?></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="hang" class="form-label">Hãng</label>
                                        <input type="text" class="form-control" id="hang" name="hang" placeholder="VD: MVW">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['hang'])) ? $error['hang'] : ''; ?></span>
                                    </div>
                                </div>

                                <div class="form-group-flex">
                                    <div class="form-group">
                                        <label for="duong_kinh" class="form-label">Đường kính mặt</label>
                                        <input type="text" class="form-control" id="duong_kinh" name="duong_kinh" placeholder="VD: 41 mm">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['duong_kinh'])) ? $error['duong_kinh'] : ''; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="chat_lieu_mat" class="form-label">Chất liệu mặt kính</label>
                                        <input type="text" class="form-control" id="chat_lieu_mat" name="chat_lieu_mat" placeholder="VD: Kính Sapphire">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['chat_lieu_mat'])) ? $error['chat_lieu_mat'] : ''; ?></span>
                                    </div>
                                </div>

                                <div class="form-group-flex">
                                    <div class="form-group">
                                        <label for="chat_lieu_day" class="form-label">Chất liệu dây</label>
                                        <input type="text" class="form-control" id="chat_lieu_day" name="chat_lieu_day" placeholder="VD: Thép không gỉ 304">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['chat_lieu_day'])) ? $error['chat_lieu_day'] : ''; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="bo_may" class="form-label">Bộ máy</label>
                                        <input type="text" class="form-control" id="bo_may" name="bo_may" placeholder="VD: Cơ tự động (Automatic)">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['bo_may'])) ? $error['bo_may'] : ''; ?></span>
                                    </div>
                                </div>

                                <div class="form-group-flex align-items-start">
                                    <div class="form-group">
                                        <label for="chong_nuoc" class="form-label">Chống nước</label>
                                        <input type="text" class="form-control" id="chong_nuoc" name="chong_nuoc" placeholder="VD: 5 ATM - Đi mưa, tắm">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['chong_nuoc'])) ? $error['chong_nuoc'] : ''; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="thuong_hieu" class="form-label">Thương hiệu</label>
                                        <input type="text" class="form-control" id="thuong_hieu" name="thuong_hieu" placeholder="VD: Việt Nam">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['thuong_hieu'])) ? $error['thuong_hieu'] : ''; ?></span>
                                    </div>
                                </div>

                                <button type="submit" name="submit" value="submit" class="btn-submit-form">Xác nhận</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery 3.6.3 -->
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <script src="../assets/js/main.js"></script>

    <script>
        const inputFile = document.querySelector('input[name="hinh_anh[]"]');
        const gridImg = document.querySelector('.grid-img');

        uploadMultipleFile(inputFile, gridImg);


    </script>
</body>
</html>