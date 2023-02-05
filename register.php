<?php 
    $conn = mysqli_connect('localhost', 'root', '', 'watch-shop') or die('Couldn\'t connect to Database');

    $error = array();

    if(isset($_POST['submit']) && $_POST['submit'] == 'submit') {
        $account = isset($_POST['account']) ? addslashes($_POST['account']) : '';
        $fullname = isset($_POST['fullname']) ? addslashes($_POST['fullname']) : '';
        $email = isset($_POST['email']) ? addslashes($_POST['email']) : '';
        $password = isset($_POST['password']) ? addslashes($_POST['password']) : '';
        $confirm_password = isset($_POST['confirm-password']) ? addslashes($_POST['confirm-password']) : '';

        if(empty($account)) {
            $error['account'] = 'Bạn chưa nhập tên tài khoản!';
        }

        if(empty($fullname)) {
            $error['fullname'] = 'Bạn chưa nhập họ tên!';
        }

        if(empty($email)) {
            $error['email'] = 'Bạn chưa nhập email!';
        } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ // Kiểm tra định dạng email
            $error['email'] = 'Email chưa đúng định dạng!';
        }

        if(empty($password)) {
            $error['password'] = 'Bạn chưa nhập mật khẩu!';
        } else {
            if(empty($confirm_password)) {
                $error['confirm-password'] = 'Bạn chưa nhập lại mật khẩu!';
            } else if($confirm_password != $password) {
                $error['confirm-password'] = 'Mật khẩu nhập lại chưa đúng!';
            }
        }

        
        // Nếu không có lỗi
        if(!($error)) {

            $sql = "SELECT * FROM khach_hang WHERE ten_tai_khoan = '{$account}' OR email = '{$email}'";
            $query = mysqli_query($conn, $sql);

            // Nếu tồn tại tên tài khoản hoặc email thì báo lỗi
            if(mysqli_num_rows($query) > 0) {
                $data = mysqli_fetch_assoc($query);

                if($account == $data['ten_tai_khoan']) {
                    $error['account'] = 'Tài khoản đã tồn tại!';
                }

                if($email == $data['email']) {
                    $error['email'] = 'Email đã tồn tại!';
                }
            } else { // Ngược lại thêm thông tin tài khoản vào database
                $sql = "INSERT INTO `khach_hang`(`ten_tai_khoan`, `ho_ten`, `email`, `mat_khau`) VALUES ('{$account}','{$fullname}','{$email}','{$password}')";

                if(mysqli_query($conn, $sql)) {
                    echo "<script>
                            alert('Đăng ký tài khoản thành công!');
                            window.location.href = 'login.php';
                         </script>";
                } else {
                    echo "<script>
                            alert('Có lỗi trong quá trình xử lý, Vui lòng thử lại!');
                         </script>";
                }
            }

        }
        
    }

    mysqli_close($conn);
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
                        <h6>Đăng ký</h6>

                        <div class="mb-3">
                            <input type="text" class="form-control" id="account" name="account" placeholder="Tên tài khoản">
                            <span class="form-text text-danger"><?php echo !(empty($error['account'])) ? $error['account'] : ''; ?></span>
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Họ tên">
                            <span class="form-text text-danger"><?php echo !(empty($error['fullname'])) ? $error['fullname'] : ''; ?></span>
                        </div>

                        <div class="mb-3">
                          <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Email">
                          <span class="form-text text-danger"><?php echo !(empty($error['email'])) ? $error['email'] : ''; ?></span>
                        </div>

                        <div class="mb-3 position-relative">
                          <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu">
                          <span class="form-text text-danger"><?php echo !(empty($error['password'])) ? $error['password'] : ''; ?></span>
                          <span class="show-password hide"><i class="fa-regular fa-eye"></i></span>
                        </div>

                        <div class="mb-3 position-relative">
                            <input type="password" class="form-control" id="confirm-password" name="confirm-password" placeholder="Nhập lại mật khẩu">
                            <span class="form-text text-danger"><?php echo !(empty($error['confirm-password'])) ? $error['confirm-password'] : ''; ?></span>
                            <span class="show-password hide"><i class="fa-regular fa-eye"></i></span>

                          </div>

                        <button type="submit" name="submit" value="submit" class="btn btn-primary">Đăng ký</button>

                        <p class="text-center">Bạn đã có tài khoản? <a href="./login.php">Đăng nhập</a> </p>
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