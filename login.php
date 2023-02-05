<?php 
    $conn = mysqli_connect('localhost', 'root', '', 'watch-shop') or die('Couldn\'t connect to Database');

    $error = array();
    session_start();

    if(isset($_POST['submit']) && $_POST['submit'] == 'submit') {
        $account_email = isset($_POST['account-email']) ? addslashes( $_POST['account-email']) : ''; 
        $password = isset($_POST['password']) ? addslashes( $_POST['password']) : ''; 

        if(empty($account_email)) {
            $error['account-email'] = 'Bạn chưa nhập tên tài khoản / email!';
        }

        if(empty($password)) {
            $error['password'] = 'Bạn chưa nhập mật khẩu!';
        }

        if(!($error)) {
            $sql = "SELECT * FROM khach_hang WHERE ten_tai_khoan = '{$account_email}' OR email = '{$account_email}'";
            $query = mysqli_query($conn, $sql);

            if(mysqli_num_rows($query) > 0) {
                $user = mysqli_fetch_assoc($query);

                if($account_email == $user['email'] || $account_email == $user['ten_tai_khoan']) {
                    if($password == $user['mat_khau']) {
                        // Lưu thông tin đăng nhập
                        $_SESSION['user_logged'] = array(
                            'id' => $user['id'],
                            'ten_tai_khoan' => $user['ten_tai_khoan'],
                            'email' => $user['email'],
                            'avatar' => $user['avatar'],
                        );

                        // Nếu là admin
                        if($user['level'] == 1) {
                            $_SESSION['admin_logged'] = array (
                                'set_logged' => true,
                            );

                            if(isset($_SESSION['previous-page'])) {
                                header('location: ' . $_SESSION['previous-page']);
                            } else {
                                header('location: ./admin/index.php');
                            }
                            
                        } else {
                            if(isset($_SESSION['previous-page'])) {
                                header('location: ' . $_SESSION['previous-page']);
                            } else {
                                header('location: ./index.php');
                            }
                        }
                    } else {
                        $error['password'] = 'Mật khẩu không đúng, vui lòng thử lại!';
                    }
                }

            } else {
                $error['account-email'] = 'Tên tài khoản hoặc mật khẩu không đúng!';
                $error['password'] = 'Tên tài khoản hoặc mật khẩu không đúng!';

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

    
    <!-- Customized Bootstrap Stylesheet -->
    <link href="./assets/css/style.css" rel="stylesheet">
</head>

<body style="background-color: #f5f5f5">
    <div class="wrapper-login">
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-md-4 offset-md-4">
                    <form class="form-login" method="POST" action="">

                        <span class="form-icon"><i class="fa-regular fa-user"></i></span>
                        <h6>Đăng nhập</h6>

                        <div class="mb-3">
                          <input type="text" class="form-control" id="account-email" name="account-email" aria-describedby="emailHelp" placeholder="Tên tài khoản / email">
                          <span class="form-text text-danger"><?php echo !(empty($error['account-email'])) ? $error['account-email'] : ''; ?></span>
                        </div>

                        <div class="mb-3 position-relative">
                          <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu">
                          <span class="form-text text-danger"><?php echo !(empty($error['password'])) ? $error['password'] : ''; ?></span>
                          <span class="show-password hide"><i class="fa-regular fa-eye"></i></span>
                        </div>

                        <button type="submit" name="submit" value="submit" class="btn btn-primary">Đăng nhập</button>

                        <p class="text-center">Bạn chưa có có tài khoản? <a href="./register.php">Đăng ký</a> </p>
                      </form>
                </div>
            </div>
        </div>
    </div>


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