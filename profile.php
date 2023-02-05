<?php
    $conn = mysqli_connect('localhost', 'root', '', 'watch-shop') or die('Couldn\'t connect to Database');

    $error = array();
    session_start();
    $sanpham_giohang = array();


    if(isset($_SESSION['user_logged'])) {
        $error = array();

        $user = $_SESSION['user_logged'];

        include('./libraries/helper.php');

        $cart = sumCart($conn, $user['id']);
        
        $sql = "SELECT * FROM khach_hang WHERE id = '{$user['id']}' "; 

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
            $avatar = $_FILES["avatar"]["name"];
            $tempname = $_FILES["avatar"]["tmp_name"];
            $folder = "./storage/uploads/" . $avatar;

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
                $avatar = $user['avatar'];
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

                    $sql = "";

                    foreach($newData as $key => $val) {
                        $sql .= $key . "='" . addslashes($val) . "'" . ",";
                    }

                    $sql = trim($sql, ',');

                    $newSql = "UPDATE khach_hang SET $sql  WHERE id = '{$user['id']}'";

                    if(mysqli_query($conn, $newSql)) {
                        echo "<script>
                                alert('Cập nhật tài khoản thành công!');
                                window.location.href = 'profile.php';
                            </script>";
                    } else {
                        echo "<script>
                                alert('Có lỗi trong quá trình xử lý, vui lòng thử lại!');
                            </script>";
                    }
                }

            }
        }

    } else {
        echo "<script>
                alert('Vui lòng đăng nhập để tiếp tục!');
                window.location.href = './login.php';
            </script>";
    }
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>EShopper - Bootstrap Shop Template</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="Free HTML Templates" name="keywords">
        <meta content="Free HTML Templates" name="description">
    
        <!-- Favicon -->
        <link href="./assets/img/favicon.ico" rel="icon">
    
        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 
    
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
        
        <!-- Libraries Stylesheet -->
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
        <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    
        
        <!-- Customized Bootstrap Stylesheet -->
        <link href="./assets/css/style.css" rel="stylesheet">
    </head>

<body>
    <header id="header">
        <!-- Topbar Start -->
        <div class="header-top bg-gray-900 py-2">
            <div class="container">
                <div class="row py-2">
                    <div class="col-lg-6 d-none d-lg-block">
                        <div class="d-inline-flex align-items-center">
                            <a class="text-light" href="">FAQs</a>
                            <span class="text-muted px-2">|</span>
                            <a class="text-light" href="">Help</a>
                            <span class="text-muted px-2">|</span>
                            <a class="text-light" href="">Support</a>
                        </div>
                    </div>
                    <div class="col-lg-6 text-center text-lg-right">
                        <div class="d-inline-flex align-items-center">
                            <a class="text-light px-2" href="">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a class="text-light px-2" href="">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a class="text-light px-2" href="">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a class="text-light px-2" href="">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a class="text-light pl-2" href="">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Topbar End -->

        <div class="header-inner py-4 bg-gray-900">
            <div class="container">
                <div class="row align-items-center py-3">
                    <div class="col-lg-3 d-none d-lg-block">
                        <a href="" class="text-decoration-none">
                            <h2 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1">WATCH</span><span class="text-light">SHOP</span></h2>
                        </a>
                    </div>
                    <div class="col-lg-6 col-12 text-left search-inner">
                        <form method="POST" action="shop.php" class="form-search w-100 position-relative">
                            <div class="input-group">
                                <input type="search" class="form-control" name="search" placeholder="Tìm kiếm tên sản phẩm...">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-primary text-light">
                                        <i class="fa fa-search"></i>
                                    </span>
                                </div>

                                <div class="search-history w-100 bg-light mt-2 rounded">
                                    
                                    <ul class="search-history-list list-group">
                                        
                                    </ul>

                                    <a href="" class="btn text-center w-100 p-2 btn-show-all">All</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-3 col-12 d-flex align-items-center justify-content-lg-end justify-content-sm-between">
                        <a href="./cart.php" class="btn" style="font-size: 20px">
                            <i class="fas fa-shopping-cart text-primary"></i>
                            <span class="badge sum-cart text-light"><?php echo $cart ?? 0?></span>
                        </a>
                        <?php if(isset($user) && !(empty($user))) { ?>
                            <button class="btn btn-show-nav-sub" style="font-size: 20px">
                                <i class="fa-solid fa-user text-primary"></i>
                                <span class="badge text-light"><?=$user['ten_tai_khoan']?></span>
                            </button>
                            <div class="nav-sub">
                                <ul>
                                    <li>
                                        <img src="./storage/uploads/avatar-default.jpg" alt="" style="width: 30px; height: 30px; border-radius: 50%">
                                        <span class="ml-2"><?=$user['ten_tai_khoan']?></span>
                                    </li>
                                    <li class="divider"></li>
                                    <li><a href="profile.php"><i class="fa-solid fa-id-card"></i> Thông tin tài khoản</a></li>
                                    <li><a href="order.php"><i class="fa-regular fa-clock"></i> Lịch sử đơn hàng</a></li>
                                    <li class="divider"></li>
                                    <li><a href="./logout.php"><i class="fa-solid fa-power-off"></i> Đăng xuất</a></li>
                                </ul>
                            </div>
                        <?php } else { ?>
                            <a href="./register.php" class="btn" style="font-size: 20px">
                                <i class="fa-solid fa-user text-primary"></i>
                                <span class="badge text-light">Đăng nhập</span>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>


        <!-- Navbar Start -->
        <div class="header-nav bg-gray-900">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <nav class="navbar navbar-expand-lg bg-gray-900 navbar-light py-3 py-lg-0 px-0">
                            <a href="" class="text-decoration-none d-block d-lg-none">
                                <h2 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1">WATCH</span>SHOP</h2>
                            </a>
                            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarCollapse">
                                <div class="navbar-nav mr-auto py-0 w-100 d-flex justify-content-center">
                                    <a href="index.php" class="nav-item nav-link position-relative text-uppercase mx-4">Trang chủ</a>
                                    <a href="shop.php" class="nav-item nav-link position-relative text-uppercase  mx-4">Sản Phẩm</a>
                                    <a href="cart.php" class="nav-item nav-link position-relative text-uppercase  mx-4">Giỏ hàng</a>
                                    <a href="contact.php" class="nav-item nav-link position-relative text-uppercase  mx-4">Liên hệ</a>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navbar End -->
    </header>


    <!-- Page Header Start -->
    <div class="container-fluid bg-gray-200 mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Thông tin tài khoản</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="">Trang chủ</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Thông tin tài khoản</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Checkout Start -->
    <div class="container-fluid pt-5 profile-content">
        <div class="row px-xl-5">
            <div class="col-lg-3 col-md-4 col-12">
                <div class="grid-img text-center">
                    <img src="<?php echo !empty($user['avatar']) ? "./storage/uploads/" . $user['avatar'] : './storage/uploads/avatar-default.jpg';?>" alt="" style="width: 100px; height: 100px">
                    <span class="form-text text-sm my-3" style="font-size: 14px">Allowed JPG, JPEG or PNG. Max size of 800K</span>
                    <label for="avatar"><i class="fa-solid fa-arrow-up-from-bracket"></i> Upload new avatar</label>
                    <input type="file" class="form-control" name="avatar" id="avatar" form="form-update-profile">
                </div>
            </div>
            <div class="col-lg-9 col-md-8 col-12">
                <form id="form-update-profile" method="POST" action="./profile.php?id=<?php echo $user['id']; ?>" enctype="multipart/form-data">
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
                    <button type="submit" name="submit" value="submit" class="btn btn-primary text-light">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Checkout End -->


    <!-- Footer Start -->
    <div class="footer bg-gray-900">
        <div class="container text-light mt-5 pt-5">
            <div class="row  pt-5">
                <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                    <a href="" class="text-decoration-none">
                        <h2 class="mb-4 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border border-white px-3 mr-1">WATCH</span><span class="text-light">SHOP</span></h2>
                    </a>
                    <p>Dolore erat dolor sit lorem vero amet. Sed sit lorem magna, ipsum no sit erat lorem et magna ipsum dolore amet erat.</p>
                    <p class="mb-2"><i class="fa fa-map-marker-alt text-light mr-3"></i>123 Street, New York, USA</p>
                    <p class="mb-2"><i class="fa fa-envelope text-light mr-3"></i>info@example.com</p>
                    <p class="mb-0"><i class="fa fa-phone-alt text-light mr-3"></i>+012 345 67890</p>
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="row">
                        <div class="col-md-4 mb-5">
                            <h5 class="font-weight-bold text-light mb-4">Quick Links</h5>
                            <div class="d-flex flex-column justify-content-start">
                                <a class="text-light mb-2" href="index.php"><i class="fa fa-angle-right mr-2"></i>Trang chủ</a>
                                <a class="text-light mb-2" href="shop.php"><i class="fa fa-angle-right mr-2"></i>Our Sản Phẩm</a>
                                <a class="text-light mb-2" href="detail.php"><i class="fa fa-angle-right mr-2"></i>Shop Detail</a>
                                <a class="text-light mb-2" href="cart.php"><i class="fa fa-angle-right mr-2"></i>Giỏ hàng</a>
                                <a class="text-light mb-2" href="checkout.php"><i class="fa fa-angle-right mr-2"></i>Checkout</a>
                                <a class="text-light" href="contact.php"><i class="fa fa-angle-right mr-2"></i>Contact Us</a>
                            </div>
                        </div>
                        <div class="col-md-4 mb-5">
                            <h5 class="font-weight-bold text-light mb-4">Quick Links</h5>
                            <div class="d-flex flex-column justify-content-start">
                                <a class="text-light mb-2" href="index.php"><i class="fa fa-angle-right mr-2"></i>Trang chủ</a>
                                <a class="text-light mb-2" href="shop.php"><i class="fa fa-angle-right mr-2"></i>Our Sản Phẩm</a>
                                <a class="text-light mb-2" href="detail.php"><i class="fa fa-angle-right mr-2"></i>Shop Detail</a>
                                <a class="text-light mb-2" href="cart.php"><i class="fa fa-angle-right mr-2"></i>Giỏ hàng</a>
                                <a class="text-light mb-2" href="checkout.php"><i class="fa fa-angle-right mr-2"></i>Checkout</a>
                                <a class="text-light" href="contact.php"><i class="fa fa-angle-right mr-2"></i>Contact Us</a>
                            </div>
                        </div>
                        <div class="col-md-4 mb-5">
                            <h5 class="font-weight-bold text-light mb-4">Newsletter</h5>
                            <form action="">
                                <div class="form-group">
                                    <input type="text" class="form-control border-0 py-4" placeholder="Your Name" required="required" />
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control border-0 py-4" placeholder="Your Email"
                                        required="required" />
                                </div>
                                <div>
                                    <button class="btn btn-primary btn-block border-0 py-3" type="submit">Subscribe Now</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/lib/easing/easing.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.js"></script>
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>

    <!-- Template Javascript -->
    <script src="./assets/js/main.js"></script>

    <script>
        const inputFile = document.querySelector('input[name="avatar"]');
        const gridImg = document.querySelector('.grid-img');

        uploadFile(inputFile, gridImg);
    </script>
</body>

</html>