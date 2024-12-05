<?php
include '../function.php';

if (isset($_GET['submit'])) 
{
    $tk = trim($_GET['tk']);
    $mk = trim($_GET['mk']); 
    $hash_mk = md5($mk);

    if (!empty($tk) && !empty($mk)) 
    {
$ds_tai_khoan = ds_tai_khoan();
        $check = false; //biến kiểm tra có acc trong DB chưa
        $loaitk = '';

        foreach ($ds_tai_khoan as $nguoi_dung) 
        {
            if ($tk === $nguoi_dung['tk'] && $hash_mk === $nguoi_dung['mk']) 
            {
                $id = $nguoi_dung['id'];
                $check = true;
                $loaitk = $nguoi_dung['loaitk'];
                $hoten = $nguoi_dung['hoten'];
                break;
            }
        }

        if ($check) 
        {
            setcookie('id', $id, time() + (86400 * 30), '/'); 
            setcookie('hoten', $hoten, time() + (86400 * 30), '/'); 
            setcookie('tk', $tk, time() + (86400 * 30), '/'); 
            setcookie('loaitk', $loaitk, time() + (86400 * 30), '/');
            setcookie('login', true, time() + (86400 * 30), '/'); 

            header('Location: trang_chu.php');
            exit(); //exit để dừng toàn bộ script ở sau
        } 
        else 
        {
            showAlert('Tài khoản hoặc mật khẩu không chính xác', 'error');
        }
    } 
    else 
    {
        showAlert('Vui lòng nhập đầy đủ tài khoản và mật khẩu', 'error');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="css/dk.css">
    <style>
        .mk 
        {
            position: relative;
        }
        .mk input 
        {
            padding-right: 40px;
        }
        .mk .hien_mk 
        {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: #555;
            user-select: none; 
}
    </style>
</head>
<body>
     <div class="dangky"> <!--để tên class z để lấy luôn css kia -->
        <h1>Đăng nhập</h1>
        <form action="" method="get">
            <label for="tk">Nhập tài khoản</label><br>
            <input type="text" name="tk" value="<?php echo isset($tk) ? $tk : ''; ?>"><br>
            <label for="mk">Nhập mật khẩu</label><br>
            <div class="mk">
                <input type="password" id="mk" name="mk" value="<?php echo isset($mk) ? $mk : ''; ?>">
                <span class="hien_mk" onclick="showPassword('mk')">&#128065;</span>
            </div>
            <button type="submit" name="submit">Đăng nhập</button><br>
            <a href="dang_ky.php">Trang đăng ký &#8594;</a>
        </form>
    </div>
    <?php
    hien_mk();
    ?>
</body>
</html>
