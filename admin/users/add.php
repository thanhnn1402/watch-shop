<?php 
    $conn = mysqli_connect('localhost', 'root', '', 'watch-shop') or die('Couldn\'t connect to Database');

    session_start();

    if(!isset($_SESSION['admin_logged'])) {
        header('location: ../../login.php');
    }

    $admin_logged = $_SESSION['user_logged'];

    $error = array();

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
        $folder = "../../storage/uploads/" . $avatar;

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
            $error['avatar'] = 'Bạn chọn ảnh đại diện';
        } else {
            move_uploaded_file($tempname ,$folder);
        }

        // Nếu không có lỗi
        if(!($error)) {

            $sql = "SELECT * FROM khach_hang WHERE ten_tai_khoan = '{$account}' OR email = '{$email}' OR so_dien_thoai = '{$phone}'";
            $query = mysqli_query($conn, $sql);

            // Nếu tồn tại tên tài khoản hoặc email thì báo lỗi
            if(mysqli_num_rows($query) > 0) {
                $user = mysqli_fetch_assoc($query);

                if($account == $user['ten_tai_khoan']) {
                    $error['account'] = 'Tài khoản đã tồn tại!';
                }

                if($email == $user['email']) {
                    $error['email'] = 'Email đã tồn tại!';
                }

                if($phone == $user['so_dien_thoai']) {
                    $error['phone'] = 'Số điện thoại đã tồn tại!';
                }
            } else { // Ngược lại thêm thông tin tài khoản vào database

                $sql = "INSERT INTO `khach_hang`(`ten_tai_khoan`, `ho_ten`, `email`, `mat_khau`, `so_dien_thoai`, `dia_chi`, `level`, `avatar`) VALUES ('{$account}','{$fullname}','{$email}','{$password}','{$phone}','{$address}',{$level},'{$avatar}')";

                if(mysqli_query($conn, $sql)) {
                    echo "<script>
                            alert('Tạo tài khoản thành công!');
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

                <li class="menu-item active">
                    <a href="" class="menu-link menu-toggle active">
                        <i class="fa-solid fa-users-line menu-icon"></i>                      
                        <span>Khách hàng</span>              
                    </a>

                    <ul class="menu-sub open">
                        <li class="menu-item">
                            <a href="./list.php" class="menu-link">
                                <span>Danh sách</span>              
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="./add.php" class="menu-link active">
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
                            <a href="../products/list.php" class="menu-link">
                                <span>Danh sách</span>              
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="../products/add.php" class="menu-link">
                                <span>Thêm sản phẩm</span>              
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="../products/add-detail.php" class="menu-link">
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
                            <P>TRANG CHỦ / THÊM TÀI KHOẢN</P>
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
                        <div class="page-content bg-white">
                            <form class="" method="POST" action="add.php" enctype="multipart/form-data">
                                <div class="form-group-flex">
                                    <div class="form-group">
                                        <label for="fullname" class="form-label">Họ tên</label>
                                        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="VD: Nguyễn Văn A">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['fullname'])) ? $error['fullname'] : ''; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="VD: example@gmail.com">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['email'])) ? $error['email'] : ''; ?></span>
                                    </div>
                                </div>

                                <div class="form-group-flex">
                                    <div class="form-group">
                                        <label for="account" class="form-label">Tên tài khoản</label>
                                        <input type="text" class="form-control" id="account" name="account" placeholder="VD: abc123">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['account'])) ? $error['account'] : ''; ?></span>
                                    </div>
                                    <div class="form-group position-relative">
                                        <label for="password" class="form-label">Mật khẩu</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="******">
                                        <span class="show-password hide"><i class="fa-regular fa-eye"></i></span>
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['password'])) ? $error['password'] : ''; ?></span>
                                    </div>
                                </div>

                                <div class="form-group-flex">
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Số điện thoại</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="VD: 0123456789">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['phone'])) ? $error['phone'] : ''; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="address" class="form-label">Địa chỉ</label>
                                        <textarea class="form-control" name="address" id="address" name="address" cols="30" rows="1"></textarea>
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['address'])) ? $error['address'] : ''; ?></span>
                                    </div>
                                </div>
                                <div class="form-group-flex align-items-start">
                                    <div class="form-group">
                                        <label for="level" class="form-label">Level</label>
                                        <select class="form-select" id="level" name="level" aria-label="Default select example">
                                            <option value="0">0 - User</option>
                                            <option value="1">1 - Admin</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="avatar" class="form-label">Avatar</label>
                                        <input type="file" class="form-control" id="avatar" name="avatar">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['avatar'])) ? $error['avatar'] : ''; ?></span>
                                        <p class="form-text">Allowed JPG, JPEG or PNG. Max size of 800K</p>
                                        <div class="grid-img">
                                            <img src="../assets/img/avatar-default.jpg" alt="upload-hinh-anh-san-pham">
                                        </div>
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
        const inputFile = document.querySelector('input[name="avatar"]');
        const gridImg = document.querySelector('.grid-img');

        uploadFile(inputFile, gridImg);
    </script>

</body>
</html>