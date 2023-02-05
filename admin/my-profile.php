<?php 
    $conn = mysqli_connect('localhost', 'root', '', 'watch-shop') or die('Couldn\'t connect to Database');

    session_start();

    // Nếu không phải admin sẽ trở về trang đăng nhập
    if(!isset($_SESSION['admin_logged'])) {
        header('location: ../login.php');
    }

    $admin_logged = $_SESSION['user_logged'];

    $error = array();
        
    $sql = "SELECT * FROM khach_hang WHERE id = '{$admin_logged['id']}' "; 

    $query = mysqli_query($conn, $sql);

    if(mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);
    }

    if(isset($_POST['submit']) && $_POST['submit'] == 'submit') {
        $account = isset($_POST['account']) ? addslashes($_POST['account']) : '';
        $fullname = isset($_POST['fullname']) ? addslashes($_POST['fullname']) : '';
        $email = isset($_POST['email']) ? addslashes($_POST['email']) : '';
        $password = isset($_POST['password']) ? addslashes($_POST['password']) : '';
        $phone = isset($_POST['phone']) ? addslashes($_POST['phone']) : '';
        $address = isset($_POST['address']) ? addslashes($_POST['address']) : '';
        $level = isset($_POST['level']) ? addslashes($_POST['level']) : 0;
        $avatar = $_FILES["avatar"]["name"];
        $tempname = $_FILES["avatar"]["tmp_name"];
        $folder = "../storage/uploads/" . $avatar;

        if(empty($account)) {
            $error['account'] = 'Bạn chưa nhập tên tài khoản';
        }

        if(empty($fullname)) {
            $error['fullname'] = 'Bạn chưa nhập họ tên';
        }

        if(empty($email)) {
            $error['email'] = 'Bạn chưa nhập email';
        } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ // Kiểm tra định dạng email
            $error['email'] = 'Email chưa đúng định dạng!';
        }

        if(empty($password)) {
            $error['password'] = 'Bạn chưa nhập mật khẩu';
        }

        if(empty($phone)) {
            $error['phone'] = 'Bạn chưa nhập số điện thoại';
        }

        if(empty($address)) {
            $error['address'] = 'Bạn chưa nhập địa chỉ';
        }

        if($_FILES['avatar']['error'] > 0) {
            $avatar = $admin_logged['avatar'];
        } else {
            move_uploaded_file($tempname ,$folder);
        }

        // Nếu không có lỗi
        if(!($error)) {

            $data_update = array(
                'ten_tai_khoan' => $account,
                'ho_ten' => $fullname,
                'email' => $email,
                'mat_khau' => $password,
                'so_dien_thoai' => $phone,
                'dia_chi' => $address,
                'avatar' => $avatar,
            );

            

            $newData = array_diff($data_update, $user);

            // Nếu tồn tại tên tài khoản, email hoặc số điện thoại thì báo lỗi
            if(isset($newData['ten_tai_khoan']) && $newData['ten_tai_khoan'] != '') {
                $sql = "SELECT * FROM khach_hang WHERE ten_tai_khoan = '{$newData['ten_tai_khoan']}' ";

                $query = mysqli_query($conn, $sql);

                if(mysqli_num_rows($query) > 0) {
                    $error['account'] = 'Tài khoản đã tồn tại';
                }
            }
            
            // Nếu tồn tại tên tài khoản, email hoặc số điện thoại thì báo lỗi
            if(isset($newData['email']) && $newData['email'] != '') {
                $sql = "SELECT * FROM khach_hang WHERE email = '{$newData['email']}' ";

                $query = mysqli_query($conn, $sql);

                if(mysqli_num_rows($query) > 0) {
                    $error['email'] = 'Email đã tồn tại';
                }
            }

            // Nếu tồn tại tên tài khoản, email hoặc số điện thoại thì báo lỗi
            if(isset($newData['so_dien_thoai']) && $newData['so_dien_thoai'] != '') {
                $sql = "SELECT * FROM khach_hang WHERE so_dien_thoai = '{$newData['so_dien_thoai']}' ";

                $query = mysqli_query($conn, $sql);

                if(mysqli_num_rows($query) > 0) {
                    $error['phone'] = 'Số điện thoại đã tồn tại';
                }
            }


            if(!($error)) {

                if(!empty($newData)) {
                    $sql = "";

                    foreach($newData as $key => $val) {
                        $sql .= $key . "='" . addslashes($val) . "'" . ",";
                    }

                    $sql = trim($sql, ',');

                    $newSql = "UPDATE khach_hang SET $sql  WHERE id = '{$user['id']}'";

                    if(mysqli_query($conn, $newSql)) {
                        echo "<script>
                                alert('Cập nhật tài khoản thành công!');
                                window.location.href = 'my-profile.php';
                            </script>";
                    } else {
                        echo "<script>
                                alert('Có lỗi trong quá trình xử lý, vui lòng thử lại!');
                            </script>";
                    }
                } else {
                    echo "<script>
                                alert('Cập nhật tài khoản thành công!');
                                window.location.href = 'my-profile.php';
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
    <link rel="stylesheet" href="./assets/css/base.css">

    <!-- Style CSS -->
    <link rel="stylesheet" href="./assets/css/style.css">

    <!-- Responsive CSS -->
    <link rel="stylesheet" href="./assets/css/responsive.css">

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
                <img src="<?php echo !empty($admin_logged['avatar']) ? '../storage/uploads/'. $admin_logged['avatar'] : '../storage/uploads/1.png' ?>" alt="">
                <p> Welcome <?php echo !empty($admin_logged['ten_tai_khoan']) ? $admin_logged['ten_tai_khoan'] : '' ?> </p>
            </div>

            <button type="button" class="btn-close-nav-mobile" data-bs-dismiss="offcanvas"><i class="fa-solid fa-angle-left"></i></button>


            <!-- Menu inner -->
            
            <ul class="menu-inner">
                <li class="menu-item active">
                    <a href="./index.php" class="menu-link active">
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
                            <a href="./users/list.php" class="menu-link">
                                <span>Danh sách</span>              
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="./users/add.php" class="menu-link">
                                <span>Thêm</span>              
                            </a>
                        </li>
                    </ul>
                </li>


                <!-- Menu products -->

                <li class="menu-header">
                    <span>Products</span>
                </li>

                <li class="menu-item">
                    <a href="" class="menu-link menu-toggle">
                        <i class="fa-solid fa-coins menu-icon"></i>                     
                        <span>Sản phẩm</span>              
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="./products/list.php" class="menu-link">
                                <span>Danh sách</span>              
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="./products/add.php" class="menu-link">
                                <span>Thêm sản phẩm</span>              
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="./products/add-detail.php" class="menu-link">
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
                            <a href="./orders/list.php" class="menu-link">
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
                                        <img src="<?php echo !empty($admin_logged['avatar']) ? '../storage/uploads/'. $admin_logged['avatar'] : '../storage/uploads/1.png' ?>" alt="avatar" class="avatar">
                                    </button>
                                    <ul class="dropdown">
                                        <li class="dropdown-item">
                                            <img src="<?php echo !empty($admin_logged['avatar']) ? '../storage/uploads/'. $admin_logged['avatar'] : '../storage/uploads/1.png' ?>" alt="avatar" class="avatar">
                                            <div class="dropdown-content">
                                                <p><?php echo !empty($admin_logged['ten_tai_khoan']) ? $admin_logged['ten_tai_khoan'] : '' ?></p>
                                                <span>Admin</span>
                                            </div>
                                        </li>

                                        <li class="divider"></li>

                                        <li class="dropdown-item">
                                            <a href="" class="dropdown-link">
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
                                            <a href="../logout.php" class="dropdown-link">
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-content bg-white rounded-3">
                            <div class="page-content-header py-4">
                                <h5>Thông tin tài khoản</h5>
                                <div class="d-flex mt-4 upload-img">
                                    <img src="<?php echo !empty($admin_logged['avatar']) ? '../storage/uploads/'. $admin_logged['avatar'] : '../storage/uploads/1.png' ?>" alt="">
                                    <div>
                                        <div>
                                            <label for="avatar" class="form-label btn btn-upload"></label>
                                            <input type="file" class="form-control" id="avatar" name="avatar" form="form-profile">

                                            <a href="" class="btn btn-outline-secondary btn-reset"></a>
                                        </div>

                                        <p class="mt-4">Allowed JPG, JPEG or PNG. Max size of 800K</p>
                                    </div>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <form id="form-profile" class="" method="POST" action="" enctype="multipart/form-data">
                                <div class="form-group-flex">
                                    <div class="form-group">
                                        <label for="fullname" class="form-label">Họ tên</label>
                                        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="VD: Nguyễn Văn A" value="<?php echo isset($user['ho_ten']) ? $user['ho_ten'] : ''; ?>">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['fullname'])) ? $error['fullname'] : ''; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="VD: example@gmail.com" value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['email'])) ? $error['email'] : ''; ?></span>
                                    </div>
                                </div>

                                <div class="form-group-flex">
                                    <div class="form-group">
                                        <label for="account" class="form-label">Tên tài khoản</label>
                                        <input type="text" class="form-control" id="account" name="account" placeholder="VD: abc123" value="<?php echo isset($user['ten_tai_khoan']) ? $user['ten_tai_khoan'] : ''; ?>">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['account'])) ? $error['account'] : ''; ?></span>
                                    </div>
                                    <div class="form-group position-relative">
                                        <label for="password" class="form-label">Mật khẩu</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="******" value="<?php echo isset($user['mat_khau']) ? $user['mat_khau'] : ''; ?>">
                                        <span class="show-password hide" style="top: 70%"><i class="fa-regular fa-eye"></i></span>
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['password'])) ? $error['password'] : ''; ?></span>
                                    </div>
                                </div>

                                <div class="form-group-flex">
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Số điện thoại</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="VD: 0123456789" value="<?php echo isset($user['so_dien_thoai']) ? $user['so_dien_thoai'] : ''; ?>">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['phone'])) ? $error['phone'] : ''; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="address" class="form-label">Địa chỉ</label>
                                        <textarea class="form-control" name="address" id="address" name="address" cols="30" rows="1"><?php echo isset($user['dia_chi']) ? $user['dia_chi'] : ''; ?></textarea>
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['address'])) ? $error['address'] : ''; ?></span>
                                    </div>
                                </div>
                                <div class="form-group-flex">
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Level</label>
                                        <select class="form-select" name="level" aria-label="Default select example">
                                            <option value="0">0 - User</option>
                                            <option value="1" selected>1 - Admin</option>
                                          </select>
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

    <script src="./assets/js/main.js"></script>

    <script>

        // change inner html input avatar, button reset
        if(window.innerWidth < 576) {
            document.querySelector('.btn-upload').innerHTML = '<i class="fa-solid fa-upload"></i>';
            document.querySelector('.btn-reset').innerHTML = '<i class="fa-solid fa-rotate-right"></i>';
        } else {
            document.querySelector('.btn-upload').innerHTML = 'Upload new photo';
            document.querySelector('.btn-reset').innerHTML = 'Reset';
        }


        const inputFile = document.querySelector('.upload-img input[name="avatar"]');
        const gridImg = document.querySelector('.upload-img');

        uploadFile(inputFile, gridImg);
    </script>

</body>
</html>