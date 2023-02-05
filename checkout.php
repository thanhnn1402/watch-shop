<?php
    $conn = mysqli_connect('localhost', 'root', '', 'watch-shop') or die('Couldn\'t connect to Database');

    $error = array();
    session_start();
    $sanpham_giohang = array();


    if(isset($_SESSION['user_logged'])) {
        $user = $_SESSION['user_logged'];

        include('./libraries/helper.php');

        $cart = sumCart($conn, $user['id']);

        $sql = "SELECT san_pham.id, san_pham.ten_sp, san_pham.don_gia_ban, gio_hang.so_luong, SUM(gio_hang.so_luong * san_pham.don_gia_ban) AS tri_gia 
                FROM gio_hang, san_pham 
                WHERE gio_hang.id_san_pham = san_pham.id AND gio_hang.id_khach_hang = {$user['id']}
                GROUP BY san_pham.id";
        
        $query = mysqli_query($conn, $sql);
        $sum = 0;
        $count = 0;
        while($row = mysqli_fetch_assoc($query)) {
            $sanpham_giohang[] = $row;
            $sum += $row['tri_gia'];
        }

        $count = count($sanpham_giohang);


        $sql = "SELECT * FROM khach_hang WHERE id = {$user['id']}";
        $query = mysqli_query($conn, $sql);
        $khach_hang = mysqli_fetch_assoc($query);


        // Kiểm tra thông tin đặt hàng
        if(isset($_POST['submit']) && $_POST['submit'] == 'submit') {
            $ho_ten = isset($_POST['ho_ten']) ? addslashes($_POST['ho_ten']) : '';
            $email = isset($_POST['email']) ? addslashes($_POST['email']) : '';
            $so_dien_thoai = isset($_POST['so_dien_thoai']) ? addslashes($_POST['so_dien_thoai']) : '';
            $dia_chi = isset($_POST['dia_chi']) ? addslashes($_POST['dia_chi']) : '';
            $hinh_thuc_thanh_toan = isset($_POST['hinh_thuc_thanh_toan']) ? addslashes($_POST['hinh_thuc_thanh_toan']) : '';

            if(empty($ho_ten)) {
                $error['ho_ten'] = 'Vui lòng nhập trường này!';
            }

            if(empty($email)) {
                $error['email'] = 'Vui lòng nhập trường này!';
            }

            if(empty($so_dien_thoai)) {
                $error['so_dien_thoai'] = 'Vui lòng nhập trường này!';
            }

            if(empty($dia_chi)) {
                $error['dia_chi'] = 'Vui lòng nhập trường này!';
            }

            if(empty($hinh_thuc_thanh_toan)) {
                $error['hinh_thuc_thanh_toan'] = 'Bạn chưa chọn hình thức thanh toán!';
            }

            if(!($error)) {
                $data = array(
                    'ho_ten' => $ho_ten,
                    'email' => $email,
                    'so_dien_thoai' => $so_dien_thoai,
                    'dia_chi' => $dia_chi,
                );

                $newData = array_diff($data, $khach_hang);

                if(isset($newData['email'])) {
                    $sql = "SELECT email FROM khach_hang WHERE email='{$newData['email']}'";
                    $query = mysqli_query($conn, $sql);
                    if(mysqli_num_rows($query) > 0) {
                        $error['email'] = 'Email đã được tài khoản khác sử dụng!';
                    }
                }

                if(isset($newData['so_dien_thoai'])) {
                    $sql = "SELECT so_dien_thoai FROM khach_hang WHERE so_dien_thoai='{$newData['so_dien_thoai']}'";
                    $query = mysqli_query($conn, $sql);
                    if(mysqli_num_rows($query) > 0) {
                        $error['so_dien_thoai'] = 'Số điện thoại đã được tài khoản khác sử dụng!';
                    }
                }

                if(!($error)) {

                    if(!(empty($newData))) {
                        $sql = '';
                        foreach($newData as $key => $val) {
                            $sql .= "'" . $key . "'='" . $val . "',";
                        }

                        $sql = trim($sql, ',');

                        $newSql = "UPDATE khach_hang SET $sql WHERE id={$user['id']}";
                        $query = mysqli_query($conn, $newSql);

                        if($query) {
                            header('location: insert-checkout.php?httt='.$hinh_thuc_thanh_toan);
                        } else {
                            echo "<script>
                                    alert('Có lỗi xảy ra, vui lòng thử lại!');
                                </script>";
                        }
                    } else {
                        header('location: insert-checkout.php?httt='.$hinh_thuc_thanh_toan);
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
                                    <a href="checkout.php" class="nav-item nav-link position-relative text-uppercase  mx-4 active">Checkout</a>
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
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Checkout</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="">Trang chủ</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Checkout</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Checkout Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <div class="col-lg-8">
                <div class="mb-4">
                    <h4 class="font-weight-semi-bold mb-4">Thông tin đặt hàng</h4>
                    <form id="form-checkout" method="POST" action="./checkout.php">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Họ tên</label>
                                <input class="form-control" type="text" name="ho_ten" placeholder="John" value="<?php echo isset($khach_hang['ho_ten']) ? $khach_hang['ho_ten'] : ''; ?>">
                                <p class="form-text ml-3 text-danger"><?php echo !(empty($error['hoten'])) ? $error['hoten'] : ''; ?></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>E-mail</label>
                                <input class="form-control" type="text" name="email" placeholder="example@email.com" value="<?php echo isset($khach_hang['email']) ? $khach_hang['email'] : ''; ?>">
                                <p class="form-text ml-3 text-danger"><?php echo !(empty($error['email'])) ? $error['email'] : ''; ?></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Số điện thoại</label>
                                <input class="form-control" type="text" name="so_dien_thoai" placeholder="+63 456 789" value="<?php echo isset($khach_hang['so_dien_thoai']) ? $khach_hang['so_dien_thoai'] : ''; ?>">
                                <p class="form-text ml-3 text-danger"><?php echo !(empty($error['so_dien_thoai'])) ? $error['so_dien_thoai'] : ''; ?></p>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Địa chỉ 1</label>
                                <textarea class="form-control" name="dia_chi" id="dia_chi" cols="30" rows="1"><?php echo isset($khach_hang['dia_chi']) ? $khach_hang['dia_chi'] : ''; ?></textarea>
                                <p class="form-text ml-3 text-danger"><?php echo !(empty($error['dia_chi'])) ? $error['dia_chi'] : ''; ?></p>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- <div class="collapse mb-4" id="shipping-address">
                    <h4 class="font-weight-semi-bold mb-4">Shipping Address</h4>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>First Name</label>
                            <input class="form-control" type="text" placeholder="John">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Last Name</label>
                            <input class="form-control" type="text" placeholder="Doe">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>E-mail</label>
                            <input class="form-control" type="text" placeholder="example@email.com">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Mobile No</label>
                            <input class="form-control" type="text" placeholder="+123 456 789">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Address Line 1</label>
                            <input class="form-control" type="text" placeholder="123 Street">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Address Line 2</label>
                            <input class="form-control" type="text" placeholder="123 Street">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Country</label>
                            <select class="custom-select">
                                <option selected>United States</option>
                                <option>Afghanistan</option>
                                <option>Albania</option>
                                <option>Algeria</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>City</label>
                            <input class="form-control" type="text" placeholder="New York">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>State</label>
                            <input class="form-control" type="text" placeholder="New York">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>ZIP Code</label>
                            <input class="form-control" type="text" placeholder="123">
                        </div>
                    </div>
                </div> -->
            </div>
            <div class="col-lg-4">
                <div class="card border-secondary mb-5">
                    <div class="card-header bg-gray-200 border-0">
                        <h4 class="font-weight-semi-bold m-0">Đơn hàng</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="font-weight-medium mb-3">Sản phẩm</h5>
                        <div class="order-products">
                            <?php foreach($sanpham_giohang as $item) { ?>
                                <div class="">
                                    <p><?=$item['ten_sp']?></p>
                                    <div class="d-flex justify-content-between">
                                        <p>x<?=$item['so_luong']?></p>
                                        <p><?=$item['don_gia_ban'] . " VND"?></p>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <hr class="mt-0">
                        <div class="d-flex justify-content-between mb-3 pt-1">
                            <h6 class="font-weight-medium">Tổng số tiền (<?=$count?> sản phẩm)</h6>
                            <h6 class="subtotal-checkout font-weight-medium"><?php echo $sum . " VND"?></h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Phí vận chuyển</h6>
                            <h6 class="font-weight-medium">30000 VND</h6>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="font-weight-bold">Tổng cộng</h5>
                            <h5 class="total-checkout font-weight-bold"><?php echo $sum + 30000 . " VND"?></h5>
                        </div>
                    </div>
                </div>
                <div class="card border-secondary mb-5">
                    <div class="card-header bg-gray-200 border-0">
                        <h4 class="font-weight-semi-bold m-0">Thanh toán</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" form="form-checkout" name="hinh_thuc_thanh_toan" value="MoMo" id="paypal">
                                <label class="custom-control-label" for="paypal">Ví điện tử MoMo</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" form="form-checkout" name="hinh_thuc_thanh_toan" value="thanh-toan-khi-nhan-hang" id="directcheck">
                                <label class="custom-control-label" for="directcheck">Thanh toán khi nhận hàng</label>
                            </div>
                        </div>
                        <div class="">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" form="form-checkout" name="hinh_thuc_thanh_toan" value="the-tin-dung" id="banktransfer">
                                <label class="custom-control-label" for="banktransfer">Thẻ tín dụng/Ghi nợ</label>
                            </div>
                        </div>
                        <p class="form-text ml-3 text-danger"><?php echo !(empty($error['hinh_thuc_thanh_toan'])) ? $error['hinh_thuc_thanh_toan'] : ''; ?></p>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <button type="submit" name="submit" value="submit" form="form-checkout" class="btn btn-lg btn-block btn-primary font-weight-bold my-3 py-3">Đặt hàng</button>
                    </div>
                </div>
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
</body>

</html>