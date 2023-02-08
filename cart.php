<?php
    $conn = mysqli_connect('localhost', 'root', '', 'watch-shop') or die('Couldn\'t connect to Database');

    $error = array();
    session_start();
    $sanpham_giohang = array();
    $sum = 0;
    $count = 0;

    if(isset($_SESSION['user_logged'])) {
        $user = $_SESSION['user_logged'];

        include('./libraries/helper.php');

        $cart = sumCart($conn, $user['id']);

        $sql = "SELECT san_pham.id, san_pham.ten_sp, san_pham.hinh_anh, san_pham.don_gia_ban, gio_hang.so_luong, SUM(gio_hang.so_luong * san_pham.don_gia_ban) AS tri_gia 
                FROM gio_hang, san_pham 
                WHERE gio_hang.id_san_pham = san_pham.id AND gio_hang.id_khach_hang = {$user['id']}
                GROUP BY san_pham.id";
        
        $query = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($query)) {
            $sanpham_giohang[] = $row;
            $sum += $row['tri_gia'];
        }

        $count = count($sanpham_giohang);

        

        // Cập nhật giỏ hàng
        if(isset($_POST['submit']) && $_POST['submit'] == 'submit') {
            $id = isset($_POST['id_san_pham']) ? $_POST['id_san_pham'] : ' ';
            $soluong = isset($_POST['so_luong']) ? $_POST['so_luong'] : ' ';

            if(empty($soluong)) {
                $error['soluong'] = 'Lỗi, mảng rỗng';
            }

            if(empty($id)) {
                $error['id'] = 'Lỗi, mảng rỗng';
            }

            if(!($error)) {
                for($i = 0; $i < count($id); $i++) {
                    $sql = "UPDATE `gio_hang` SET `so_luong`='{$soluong[$i]}' WHERE `id_khach_hang`={$user['id']} AND `id_san_pham`={$id[$i]}";
                    $query = mysqli_query($conn, $sql);

                }

                if($query) {
                    echo "<script>
                            alert('Cập nhật giỏ hàng thành công!');
                            window.location.href = 'cart.php';
                        </script>";
                }
            }
        }
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
                    <div class="col-lg-6 col-6 text-left search-inner">
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
                                    <a href="cart.php" class="nav-item nav-link position-relative text-uppercase  mx-4 active">Giỏ hàng</a>
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
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Giỏ hàng</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="">Trang chủ</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Giỏ hàng</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Cart Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <div class="col-lg-8 table-responsive mb-5">
                <table id="table-cart" class="table table-striped table-bordered text-center mb-0 display">
                    <thead class="bg-gray-200 text-dark">
                        <tr>
                            <th>STT</th>
                            <th>Tên sản phẩm</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Trị giá</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        <?php foreach ($sanpham_giohang as $item) { 
                            $newArrImg = explode("||", $item['hinh_anh']);
                        ?>
                            <tr>
                                <td class="align-middle">1</td>
                                <td class="align-middle">
                                    <img src="./storage/uploads/<?=$newArrImg[0]?>" alt="" style="width: 50px;">
                                    <?=$item['ten_sp']?>
                                </td>
                                <td class="align-middle"><?=$item['don_gia_ban'] . " VND"?></td>
                                <td class="align-middle">
                                    <div class="input-group quantity mx-auto" style="width: 100px;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-primary btn-minus" >
                                            <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" id="soluong" name="so_luong[]" form="update-cart" class="form-control form-control-sm bg-gray-200 text-center" value="<?=$item['so_luong']?>">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-primary btn-plus">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_san_pham[]" form="update-cart" value="<?=$item['id']?>">
                                </td>
                                <td class="align-middle"><?=$item['tri_gia']?> VND</td>
                                <td class="align-middle"><a href="./delete-cart.php?id=<?=$item['id']?>" class="btn-delete-cart btn btn-sm btn-primary" > <i class="fa fa-times"></i></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <a href="./shop.php" class="btn btn-primary"><i class="fa-solid fa-arrow-left mr-2"></i> TIẾP TỤC MUA SẮM</a>
                <button type="submit" name="submit" value="submit" form="update-cart" class="btn-update-cart btn btn-primary">CẬP NHẬT GIỎ HÀNG</button>
                <form id="update-cart" action="" method="POST"></form>
            </div>
            <div class="col-lg-4">
                <form class="mb-5" action="">
                    <div class="input-group">
                        <input type="text" class="form-control p-4" placeholder="Mã giảm giá">
                        <div class="input-group-append">
                            <button class="btn btn-primary">Mã giảm giá</button>
                        </div>
                    </div>
                </form>
                <div class="card border-secondary mb-5">
                    <div class="card-header bg-gray-200 border-0">
                        <h4 class="font-weight-semi-bold m-0">Tổng tiền</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 pt-1">
                            <h6 class="font-weight-medium">Tổng số tiền (<?=$count?> sản phẩm) </h6>
                            <h6 class="subtotal-cart font-weight-medium"><?php echo $sum ?> VND</h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Phí vận chuyển</h6>
                            <h6 class="font-weight-medium">30000 VND</h6>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="font-weight-bold">Tổng cộng</h5>
                            <h5 class="total-cart font-weight-bold"><?php echo $sum + 30000 ?> VND</h5>
                        </div>
                        <a href="./checkout.php" class="btn-proceed-ckeckout btn btn-block btn-primary my-3 py-3">Đặt hàng</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart End -->


    <!-- Footer Start -->
    <div class="footer bg-gray-900">
        <div class="container text-light mt-5 pt-5">
            <div class="row  pt-5">
                <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                    <a href="" class="text-decoration-none">
                        <h2 class="mb-4 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border border-white px-3 mr-1">WATCH</span><span class="text-light">SHOP</span></h2>
                    </a>
                    <p>Dolore erat dolor sit lorem vero amet. Sed sit lorem magna, ipsum no sit erat lorem et magna ipsum dolore amet erat.</p>
                    <p class="mb-2"><i class="fa fa-map-marker-alt text-light mr-3"></i>CMT8, Ninh kiều, Cần Thơ</p>
                    <p class="mb-2"><i class="fa fa-envelope text-light mr-3"></i>example@gmail.com</p>
                    <p class="mb-0"><i class="fa fa-phone-alt text-light mr-3"></i>029.382.323</p>
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="row">
                        <div class="col-md-4 mb-5">
                            <h5 class="font-weight-bold text-light mb-4">Liên kết</h5>
                            <div class="d-flex flex-column justify-content-start">
                                <a class="text-light mb-2" href="index.php"><i class="fa fa-angle-right mr-2"></i>Trang chủ</a>
                                <a class="text-light mb-2" href="shop.php"><i class="fa fa-angle-right mr-2"></i>Sản Phẩm</a>
                                <a class="text-light mb-2" href="cart.php"><i class="fa fa-angle-right mr-2"></i>Giỏ hàng</a>
                                <a class="text-light" href="contact.php"><i class="fa fa-angle-right mr-2"></i>Liên hệ</a>
                            </div>
                        </div>
                        <div class="col-md-4 mb-5">
                            <h5 class="font-weight-bold text-light mb-4">Liên kết</h5>
                            <div class="d-flex flex-column justify-content-start">
                                <a class="text-light mb-2" href="index.php"><i class="fa fa-angle-right mr-2"></i>Trang chủ</a>
                                <a class="text-light mb-2" href="shop.php"><i class="fa fa-angle-right mr-2"></i>Sản Phẩm</a>
                                <a class="text-light mb-2" href="cart.php"><i class="fa fa-angle-right mr-2"></i>Giỏ hàng</a>
                                <a class="text-light" href="contact.php"><i class="fa fa-angle-right mr-2"></i>Liên hệ</a>
                            </div>
                        </div>
                        <div class="col-md-4 mb-5">
                            <h5 class="font-weight-bold text-light mb-4">Ưu đãi</h5>
                            <form action="">
                                <div class="form-group">
                                    <input type="text" class="form-control border-0 py-4" placeholder="Your Name" required="required" />
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control border-0 py-4" placeholder="Your Email"
                                        required="required" />
                                </div>
                                <div>
                                    <button class="btn btn-primary btn-block border-0 py-3" type="submit">Đăng ký ngay</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <div class="toast-message">
        <p class="text-light p-3">Vui lòng thêm sản phẩm vào giỏ hàng của bạn</p>
    </div>


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/lib/easing/easing.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.js"></script>
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>

    <!-- Template Javascript -->
    <script src="./assets/js/main.js"></script>

    <script>
        //datatables cart
        $('#table-cart').DataTable({});
    </script>
</body>

</html>