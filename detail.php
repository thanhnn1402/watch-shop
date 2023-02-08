<?php 
    $conn = mysqli_connect('localhost', 'root', '', 'watch-shop') or die('Couldn\'t connect to Database');

    $error = array();
    session_start();

    if(isset($_SESSION['user_logged'])) {
        $user = $_SESSION['user_logged'];

        include('./libraries/helper.php');

        $cart = sumCart($conn, $user['id']);
    }

    $id_san_pham = isset($_GET['id']) ? $_GET['id'] : '';

    if(!(empty($id_san_pham))) {
        $sql = "SELECT * FROM san_pham, chi_tiet_san_pham WHERE san_pham.id = chi_tiet_san_pham.id_san_pham AND san_pham.id = {$id_san_pham}";
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) > 0) {
            $sanpham = mysqli_fetch_assoc($query);

            if(!(empty($sanpham['hinh_anh']))) {
                $newArr = explode("||", $sanpham['hinh_anh']) ?? ' ';
            }

            $sql = "SELECT * FROM san_pham WHERE id_loai_hang = {$sanpham['id_loai_hang']} AND id NOT IN({$id_san_pham})";
            $query = mysqli_query($conn, $sql);
            if($row = mysqli_fetch_assoc($query)) {
                $sanpham_cungloai[] = $row;
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
        <link href="./assets/./assets/img/favicon.ico" rel="icon">
    
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
                            <a href="./logout.php" class="btn" style="font-size: 20px">
                                <i class="fa-solid fa-user text-primary"></i>
                                <span class="badge text-light"><?=$user['ten_tai_khoan']?></span>
                            </a>
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
                                    <a href="detail.php" class="nav-item nav-link position-relative text-uppercase  mx-4 active">Shop Detail</a>
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
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Shop Detail</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="">Trang chủ</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Shop Detail</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Shop Detail Start -->
    <div class="container-fluid py-5">
        <div class="row px-xl-5 product-item">
            <div class="col-lg-5 pb-5">
                <div id="product-slick-slide" class="slick slide">
                    <div class="product-slick-inner border position-relative">
                        <?php foreach($newArr as $item) { ?>
                            <div class="product-slick-item">
                                <img class="product-thumbnail w-100 h-100" src="./storage/uploads/<?=$item?>" alt="Image">
                            </div>
                        <?php } ?>
                    </div>

                    <div class="product-navfor-slick-inner border">
                        <div class="row w-100" id="slick-slide-navfor">
                            <?php foreach($newArr as $item) { ?>
                                <div class="col-lg-3 mw-100">
                                    <div class="product-slick-item">
                                        <img class="w-100 h-100" src="./storage/uploads/<?=$item?>" alt="Image">
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="col-lg-7 pb-5">
                <h3 class="product-name font-weight-semi-bold"><?php echo isset($sanpham['ten_sp']) ? $sanpham['ten_sp'] : '';?></h3>
                <div class="d-flex mb-3">
                    <div class="text-primary mr-2">
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star-half-alt"></small>
                        <small class="far fa-star"></small>
                    </div>
                    <small class="pt-1">(50 Reviews)</small>
                </div>
                <h3 class="font-weight-semi-bold mb-4"><?php echo isset($sanpham['don_gia_ban']) ? $sanpham['don_gia_ban'] . " VND" : '';?></h3>
                <p class="mb-4"><?php echo isset($sanpham['mo_ta']) ? $sanpham['mo_ta'] : '';?></p>
                
                <div class="row">
                    <div class="col-lg-6 d-flex align-items-start">
                        <img src="https://img.icons8.com/external-anggara-basic-outline-anggara-putra/32/000000/external-shield-ecommerce-interface-anggara-basic-outline-anggara-putra.png"/>

                        <p class="ml-2">Bảo hành chính hãng 2 năm tại các trung tâm bảo hành hãng</p>
                    </div>

                    <div class="col-lg-6 d-flex align-items-start">
                        <img src="https://img.icons8.com/wired/32/000000/renew-subscription.png"/>

                        <p class="ml-2">Bảo hành có cam kết trong 12 tháng </p>
                    </div>

                    <div class="col-lg-6 d-flex align-items-start">
                        <img src="https://img.icons8.com/ios/32/000000/open-box.png"/>
                        <p class="ml-2">Bộ sản phẩm gồm: Hướng dẫn sử dụng, Hộp, Phiếu bảo hành</p>
                    </div>

                    <div class="col-lg-6 d-flex align-items-start">
                        <img src="https://img.icons8.com/dotty/32/000000/truck--v1.png"/>

                        <p class="ml-2">
                            TP.Hồ Chí Minh (trừ Củ Chi, Nhà Bè, Cần Giờ): giao hàng nhanh chóng.<br/>
                            Tỉnh thành khác: giao hàng từ 2 - 10 ngày. Xem chi tiết
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-4 pt-2">
                    <div class="input-group quantity mr-3" style="width: 130px;">
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-minus" >
                            <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <input type="text" id="soluong" class="form-control bg-gray-200 text-center" value="1">
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-plus">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <a href="./insert-cart.php?id=<?=$id_san_pham?>&soluong=1" data-id="<?=$id_san_pham?>" class="btn btn-primary btn-add-cart text-light px-3"><i class="fa fa-shopping-cart mr-1"></i> Thêm giỏ hàng</a>
                </div>
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 mr-2">Share on:</p>
                    <div class="d-inline-flex">
                        <a class="text-dark px-2" href="">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a class="text-dark px-2" href="">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a class="text-dark px-2" href="">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a class="text-dark px-2" href="">
                            <i class="fab fa-pinterest"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row px-xl-5">
            <div class="col">
                <div class="nav nav-tabs justify-content-center border-secondary mb-4">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#tab-pane-1">Description</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#tab-pane-2">Infomation</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#tab-pane-3">Review (0)</a>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-pane-1">
                        <h4 class="mb-3">Product Description</h4>
                        <p><?php echo isset($sanpham['mo_ta']) ? $sanpham['mo_ta'] : '';?></p>
                    </div>
                    <div class="tab-pane fade" id="tab-pane-2">
                        <h4 class="mb-3">Additional Information</h4>
                        <p>Eos no lorem eirmod diam diam, eos elitr et gubergren diam sea. Consetetur vero aliquyam invidunt duo dolores et duo sit. Vero diam ea vero et dolore rebum, dolor rebum eirmod consetetur invidunt sed sed et, lorem duo et eos elitr, sadipscing kasd ipsum rebum diam. Dolore diam stet rebum sed tempor kasd eirmod. Takimata kasd ipsum accusam sadipscing, eos dolores sit no ut diam consetetur duo justo est, sit sanctus diam tempor aliquyam eirmod nonumy rebum dolor accusam, ipsum kasd eos consetetur at sit rebum, diam kasd invidunt tempor lorem, ipsum lorem elitr sanctus eirmod takimata dolor ea invidunt.</p>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <tbody>
                                      <tr>
                                        <td class="font-weight-bold">Giới tính:</td>
                                        <td>
                                            <?php
                                                if(isset($sanpham['id_loai_hang'])) {
                                                    echo $sanpham['id_loai_hang'] == 1 ? "Nam" : "Nữ";
                                                }
                                            ?>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td class="font-weight-bold">Đường kính mặt:</td>
                                        <td><?php echo isset($sanpham['duong_kinh']) ? $sanpham['duong_kinh'] : '';?></td>
                                      </tr>
                                      <tr>
                                        <td class="font-weight-bold">Chất liệu mặt kính:</td>
                                        <td><?php echo isset($sanpham['chat_lieu_mat']) ? $sanpham['chat_lieu_mat'] : '';?></td>
                                      </tr>
                                      <tr>
                                        <td class="font-weight-bold">Chất liệu dây:</td>
                                        <td><?php echo isset($sanpham['chat_lieu_day']) ? $sanpham['chat_lieu_day'] : '';?></td>
                                      </tr>
                                      <tr>
                                        <td class="font-weight-bold">Bộ máy:</td>
                                        <td><?php echo isset($sanpham['bo_may']) ? $sanpham['bo_may'] : '';?></td>
                                      </tr>
                                      <tr>
                                        <td class="font-weight-bold">Chống nước:</td>
                                        <td><?php echo isset($sanpham['chong_nuoc']) ? $sanpham['chong_nuoc'] : '';?></td>
                                      </tr>
                                      <tr>
                                        <td class="font-weight-bold">Thương hiệu:</td>
                                        <td><?php echo isset($sanpham['thuong_hieu']) ? $sanpham['thuong_hieu'] : '';?></td>
                                      </tr>
                                      <tr>
                                        <td class="font-weight-bold">Hãng:</td>
                                        <td><?php echo isset($sanpham['hang']) ? $sanpham['hang'] : '';?></td>
                                      </tr>
                                    </tbody>
                                  </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tbody>
                                      <tr>
                                        <td class="font-weight-bold">Giới tính:</td>
                                        <td>
                                            <?php
                                                if(isset($sanpham['id_loai_hang'])) {
                                                    echo $sanpham['id_loai_hang'] == 1 ? "Nam" : "Nữ";
                                                }
                                            ?>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td class="font-weight-bold">Đường kính mặt:</td>
                                        <td><?php echo isset($sanpham['duong_kinh']) ? $sanpham['duong_kinh'] : '';?></td>
                                      </tr>
                                      <tr>
                                        <td class="font-weight-bold">Chất liệu mặt kính:</td>
                                        <td><?php echo isset($sanpham['chat_lieu_mat']) ? $sanpham['chat_lieu_mat'] : '';?></td>
                                      </tr>
                                      <tr>
                                        <td class="font-weight-bold">Chất liệu dây:</td>
                                        <td><?php echo isset($sanpham['chat_lieu_day']) ? $sanpham['chat_lieu_day'] : '';?></td>
                                      </tr>
                                      <tr>
                                        <td class="font-weight-bold">Bộ máy:</td>
                                        <td><?php echo isset($sanpham['bo_may']) ? $sanpham['bo_may'] : '';?></td>
                                      </tr>
                                      <tr>
                                        <td class="font-weight-bold">Chống nước:</td>
                                        <td><?php echo isset($sanpham['chong_nuoc']) ? $sanpham['chong_nuoc'] : '';?></td>
                                      </tr>
                                      <tr>
                                        <td class="font-weight-bold">Thương hiệu:</td>
                                        <td><?php echo isset($sanpham['thuong_hieu']) ? $sanpham['thuong_hieu'] : '';?></td>
                                      </tr>
                                      <tr>
                                        <td class="font-weight-bold">Hãng:</td>
                                        <td><?php echo isset($sanpham['hang']) ? $sanpham['hang'] : '';?></td>
                                      </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-pane-3">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-4">1 review for "Colorful Stylish Shirt"</h4>
                                <div class="media mb-4">
                                    <img src="./assets/img/user.jpg" alt="Image" class="img-fluid mr-3 mt-1" style="width: 45px;">
                                    <div class="media-body">
                                        <h6>John Doe<small> - <i>01 Jan 2045</i></small></h6>
                                        <div class="text-primary mb-2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <p>Diam amet duo labore stet elitr ea clita ipsum, tempor labore accusam ipsum et no at. Kasd diam tempor rebum magna dolores sed sed eirmod ipsum.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 class="mb-4">Leave a review</h4>
                                <small>Your email address will not be published. Required fields are marked *</small>
                                <div class="d-flex my-3">
                                    <p class="mb-0 mr-2">Your Rating * :</p>
                                    <div class="text-primary">
                                        <i class="far fa-star rate-star"></i>
                                        <i class="far fa-star rate-star"></i>
                                        <i class="far fa-star rate-star"></i>
                                        <i class="far fa-star rate-star"></i>
                                        <i class="far fa-star rate-star"></i>
                                    </div>
                                </div>
                                <form>
                                    <div class="form-group">
                                        <label for="message">Your Review *</label>
                                        <textarea id="message" cols="30" rows="5" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Your Name *</label>
                                        <input type="text" class="form-control" id="name">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Your Email *</label>
                                        <input type="email" class="form-control" id="email">
                                    </div>
                                    <div class="form-group mb-0">
                                        <input type="submit" value="Leave Your Review" class="btn btn-primary px-3">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Detail End -->


    <!-- Products Start -->
    <div class="container-fluid py-5">
        <div class="text-center mb-4">
            <h3 class="section-title px-5"><span class="px-2">Related products</span></h3>
        </div>
        <div class="row px-xl-5">
            <div class="col">
                <div class="owl-carousel related-carousel related-slick">
                    <?php foreach($sanpham_cungloai as $item) { 
                        $newArr = explode("||", $item['hinh_anh']);
                    ?>
                        
                        <div class="card product-item border-0">
                            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                <img class="img-fluid w-100" src="./storage/uploads/<?=$newArr[0]?>" alt="">
                            </div>
                            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                <h6 class="text-truncate mb-3"><?=$item['ten_sp']?></h6>
                                <div class="d-flex justify-content-center">
                                    <h6><?=$item['don_gia_ban'] . " VND"?></h6>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light border">
                                <a href="./detail.php?id=<?=$item['id']?>" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                                <a href="./cart.php?id=<?=$item['id']?>" class="btn btn-sm text-dark p-0"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Products End -->


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


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.js"></script>
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>

    <!-- Template Javascript -->
    <script src="./assets/js/main.js"></script>
    <script>
        const inputQuantity = document.querySelector('input#soluong');

        inputQuantity.addEventListener('change', function() {
            console.log('Quantity');
        })
    </script>
</body>

</html>