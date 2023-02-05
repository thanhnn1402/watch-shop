<?php 
    $conn = mysqli_connect('localhost', 'root', '', 'watch-shop') or die('Couldn\'t connect to Database');

    session_start();

    if(!isset($_SESSION['admin_logged'])) {
        header('location: ../../login.php');
    }

    $admin_logged = $_SESSION['user_logged'];

    $id_san_pham = isset($_GET['id']) ? $_GET['id'] : '';
 
    if(!(empty($id_san_pham))) {
        $sanpham = array();

        $sql = "SELECT * FROM san_pham, chi_tiet_san_pham WHERE san_pham.id = chi_tiet_san_pham.id_san_pham AND san_pham.id = {$id_san_pham}";
        $query = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($query)) {
            $sanpham[] = $row;
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

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css">

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
                            <a href="./list.php" class="menu-link active">
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
                        <div class="page-content bg-white p-4">
                            <table class="table table-bordered display mt-3" id="table-list-products">
                                <tbody>
                                    <tr>
                                        <th>ID</th>
                                        <td><?php echo isset($sanpham[0]['id_san_pham']) ? $sanpham[0]['id_san_pham'] : ''; ?></td>
                                        <th>Tên sản phẩm</th>
                                        <td><?php echo isset($sanpham[0]['ten_sp']) ? $sanpham[0]['ten_sp'] : ''; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Đơn giá nhập</th>
                                        <td><?php echo isset($sanpham[0]['don_gia_nhap']) ? $sanpham[0]['don_gia_nhap'] : ''; ?></td>
                                        <th>Đơn giá bán</th>
                                        <td><?php echo isset($sanpham[0]['don_gia_ban']) ? $sanpham[0]['don_gia_ban'] : ''; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Số lượng kho</th>
                                        <td><?php echo isset($sanpham[0]['so_luong_kho']) ? $sanpham[0]['so_luong_kho'] : ''; ?></td>
                                        <th>Mô tả</th>
                                        <td><?php echo isset($sanpham[0]['mo_ta']) ? $sanpham[0]['mo_ta'] : ''; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Loại hàng</th>
                                        <td><?php echo isset($sanpham[0]['id_loai_hang']) ? $sanpham[0]['id_loai_hang'] : ''; ?></td>
                                        <th>Đường kính</th>
                                        <td><?php echo isset($sanpham[0]['duong_kinh']) ? $sanpham[0]['duong_kinh'] : ''; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Chất liệu mặt</th>
                                        <td><?php echo isset($sanpham[0]['chat_lieu_mat']) ? $sanpham[0]['chat_lieu_mat'] : ''; ?></td>
                                        <th>Chất liệu dây</th>
                                        <td><?php echo isset($sanpham[0]['chat_lieu_day']) ? $sanpham[0]['chat_lieu_day'] : ''; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Bộ máy</th>
                                        <td><?php echo isset($sanpham[0]['bo_may']) ? $sanpham[0]['bo_may'] : ''; ?></td>
                                        <th>Chống nước</th>
                                        <td><?php echo isset($sanpham[0]['chong_nuoc']) ? $sanpham[0]['chong_nuoc'] : ''; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Thương hiệu</th>
                                        <td><?php echo isset($sanpham[0]['thuong_hieu']) ? $sanpham[0]['thuong_hieu'] : ''; ?></td>
                                        <th>Hãng</th>
                                        <td><?php echo isset($sanpham[0]['hang']) ? $sanpham[0]['hang'] : ''; ?></td>
                                    </tr>
                                    <tr class="grid-img">
                                        <th>Hình ảnh</th>
                                        <td colspan="3"></td>
                                    </tr>
                                </tbody>
                              </table>
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

    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>

    <script src="../assets/js/main.js"></script>

    <script>
        const gridImg = document.querySelector('.grid-img td');
        const arrImg = <?php echo "'" . $sanpham[0]['hinh_anh'] . "'"; ?> ?? ''; 
        const newArrImg = arrImg.split('||');

        newArrImg.pop();

        if(Array.isArray(newArrImg)) {
            newArrImg.forEach(function(item, index) {
                let img = document.createElement('img');
                img.setAttribute('src', `../../storage/uploads/${item}`);
                img.style.width = "15%"
                gridImg.appendChild(img);
            })
        }

    </script>
</body>
</html>