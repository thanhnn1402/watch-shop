<?php 
    $conn = mysqli_connect('localhost', 'root', '', 'watch-shop') or die('Couldn\'t connect to Database');

    session_start();
    date_default_timezone_set('Asia/Ho_Chi_Minh'); 
    $date_current = '';
    $date_current = date("Y-m-d H:i:s");

    if(!isset($_SESSION['admin_logged'])) {
        header('location: ../../login.php');
    }

    $admin_logged = $_SESSION['user_logged'];

    include('../../libraries/helper.php');

    $id_san_pham = isset($_GET['id']) ? $_GET['id'] : '';

    if(!(empty($id_san_pham))) {
        $error = array();

        // Lấy dữ diệu bảng loại hàng tạo select html
        $sql = "SELECT * FROM loai_hang";
        $query = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($query)) {
            $loaihang[] = $row;
        }

        // Lấy dữ liệu sản phẩm cần cập nhật thông qua id
        $sql = "SELECT * FROM san_pham WHERE id = {$id_san_pham}";
        $query = mysqli_query($conn, $sql);

        if(mysqli_num_rows($query) > 0) {
            $sanpham = mysqli_fetch_assoc($query);
        }

        if(isset($_POST['submit']) && $_POST['submit'] == 'submit') {
            $ten_sp = isset($_POST['ten_sp']) ? addslashes($_POST['ten_sp']) : '';
            $mo_ta = isset($_POST['mo_ta']) ? addslashes($_POST['mo_ta']) : '';
            $don_gia_nhap = isset($_POST['don_gia_nhap']) ? addslashes($_POST['don_gia_nhap']) : '';
            $don_gia_ban = isset($_POST['don_gia_ban']) ? addslashes($_POST['don_gia_ban']) : '';
            $so_luong_kho = isset($_POST['so_luong_kho']) ? addslashes($_POST['so_luong_kho']) : '';
            $ngay_nhap = isset($_POST['ngay_nhap']) ? date_format(date_create($_POST['ngay_nhap']), 'Y-m-d H:i:s') : $date_current;
            $id_loai = isset($_POST['id_loai']) ? addslashes($_POST['id_loai']) : '';

            $files = isset($_FILES['hinh_anh']) ? $_FILES['hinh_anh'] : array();
            $hinh_anh = '';

            if(!empty($files)) {
                $files_array = reArrayFiles($files);

                foreach($files_array as $file) {
                    if($file['error'] <= 0) {
                        move_uploaded_file($file['tmp_name'], "../../storage/uploads/" . $file['name']);
                        $hinh_anh .= $file['name'] . '||';
                    }
                }
            }

            $hinh_anh = trim($hinh_anh, '||');


            if(empty($ten_sp)) {
                $error['ten_sp'] = 'Bạn chưa nhập tên sản phẩm';
            }

            if(empty($don_gia_nhap)) {
                $error['don_gia_nhap'] = 'Bạn chưa nhập đơn giá nhập';
            }

            if(empty($don_gia_ban)) {
                $error['don_gia_ban'] = 'Bạn chưa nhập đơn giá bán';
            }

            if(empty($so_luong_kho)) {
                $error['so_luong_kho'] = 'Bạn chưa nhập số lượng kho';
            }

            if(empty($mo_ta)) {
                $error['mo_ta'] = 'Bạn chưa nhập mô tả sản phẩm';
            }

            if(empty($id_loai)) {
                $error['id_loai'] = 'Bạn chưa chọn id loại hàng';
            }

            if(empty($hinh_anh)) {
                $hinh_anh = $sanpham['hinh_anh'];
            }

            if(!($error)) {
                $sql = "UPDATE `san_pham` SET `ten_sp`='{$ten_sp}',`don_gia_nhap`='{$don_gia_nhap}',`don_gia_ban`='{$don_gia_ban}',`so_luong_kho`='{$so_luong_kho}',`mo_ta`='{$mo_ta}',`hinh_anh`='{$hinh_anh}',`id_loai_hang`='{$id_loai}',`created_at`='{$ngay_nhap}',`updated_at`='{$date_current}' WHERE `id`={$id_san_pham}";

                if(mysqli_query($conn, $sql)) {
                    echo "<script>
                            alert('Cập nhật sản phẩm thành công!');
                            window.location.href = './list.php';
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
                            <a href="./add-detail.php" class="menu-link">
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
                            <form class="" method="POST" action="edit.php?id=<?=$id_san_pham?>" enctype="multipart/form-data">
                                <div class="form-group-flex">
                                    <div class="form-group">
                                        <label for="ten_sp" class="form-label">Tên sản phẩm</label>
                                        <input type="text" class="form-control" id="ten_sp" name="ten_sp" placeholder="VD: Đồng hồ BABY-G 38.1 mm Nữ MSG-S200-4ADR" value="<?php echo isset($sanpham['ten_sp']) ? $sanpham['ten_sp'] : ''; ?>">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['ten_sp'])) ? $error['ten_sp'] : ''; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="mo_ta" class="form-label">Mô tả</label>
                                        <textarea class="form-control" name="mo_ta" id="mo_ta" cols="30" rows="1"><?php echo isset($sanpham['mo_ta']) ? $sanpham['mo_ta'] : ''; ?></textarea>
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['mo_ta'])) ? $error['mo_ta'] : ''; ?></span>
                                    </div>
                                </div>

                                <div class="form-group-flex">
                                    <div class="form-group">
                                        <label for="don_gia_nhap" class="form-label">Đơn giá nhập</label>
                                        <input type="number" class="form-control" id="don_gia_nhap" name="don_gia_nhap" placeholder="VD: 2.000.000" value="<?php echo isset($sanpham['don_gia_nhap']) ? $sanpham['don_gia_nhap'] : ''; ?>">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['don_gia_nhap'])) ? $error['don_gia_nhap'] : ''; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="don_gia_ban" class="form-label">Đơn giá bán</label>
                                        <input type="number" class="form-control" id="don_gia_ban" name="don_gia_ban" placeholder="VD: 2.450.000"value="<?php echo isset($sanpham['don_gia_ban']) ? $sanpham['don_gia_ban'] : ''; ?>">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['don_gia_ban'])) ? $error['don_gia_ban'] : ''; ?></span>
                                    </div>
                                </div>

                                <div class="form-group-flex align-items-start">
                                    <div class="form-group">
                                        <label for="so_luong_kho" class="form-label">Số lượng kho</label>
                                        <input type="number" class="form-control" id="so_luong_kho" name="so_luong_kho" placeholder="VD: 10" value="<?php echo isset($sanpham['so_luong_kho']) ? $sanpham['so_luong_kho'] : ''; ?>">
                                        <span class="form-text ms-3 text-danger"><?php echo !(empty($error['so_luong_kho'])) ? $error['so_luong_kho'] : ''; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="ngay_nhap" class="form-label">Ngày nhập</label>
                                        <input type="datetime-local" class="form-control" id="ngay_nhap" name="ngay_nhap" value="<?php echo isset($sanpham['created_at']) ? $sanpham['created_at'] : ''; ?>">
                                    </div>
                                </div>

                                <div class="form-group-flex align-items-start">
                                    <div class="form-group">
                                        <label for="id_loai" class="form-label">ID loại hàng</label>
                                        <select class="form-select" name="id_loai" aria-label="Default select example">
                                            <?php foreach ($loaihang as $item) { ?>
                                                <option value="<?=$item['id']?>" <?php if($item['id'] == $sanpham['id_loai_hang']) echo "selected"; ?> >
                                                    <?=$item['id'] . " - " . $item['ten_loai']?> 
                                                </option>
                                            <?php } ?>
                                          </select>
                                          <span class="form-text ms-3 text-danger"><?php echo !(empty($error['id_loai'])) ? $error['id_loai'] : ''; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="hinh_anh" class="form-label">Hình ảnh</label>
                                        <input type="file" class="form-control" id="hinh_anh" name="hinh_anh[]" accept=".jpg, .png, .jpeg" multiple>
                                        <p class="form-text">Allowed JPG, JPEG or PNG. Max size of 800K</p>
                                        <div class="grid-img">
                                            
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
        const inputFile = document.querySelector('input[name="hinh_anh[]"]');
        const gridImg = document.querySelector('.grid-img');
        const arrImg = <?php echo "'" . $sanpham['hinh_anh'] . "'"; ?> ?? ''; 
        const newArrImg = arrImg.split('||');

        newArrImg.pop();

        if(Array.isArray(newArrImg)) {
            newArrImg.forEach(function(item, index) {
                let img = document.createElement('img');
                img.setAttribute('src', `../../storage/uploads/${item}`)
                gridImg.appendChild(img);

            })
        }

        uploadMultipleFile(inputFile, gridImg);


    </script>
</body>
</html>