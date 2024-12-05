<?php

include '../connectdb.php'; 
include '../function.php';  

if (isset($_GET['submit'])) 
{
    //trim: loại bỏ khoảng trắng thừa
    $hoten = trim($_GET['hoten']);
    $tk = trim($_GET['tk']); 
    $mk = trim($_GET['mk']);
    $mk1 = trim($_GET['mk1']);
    $loaitk = $_GET['loaitk'];

    $check = ''; //khai báo biến kiểm tra trống

    if (empty($hoten) || empty($tk) || empty($mk) || empty($mk1) || empty($loaitk)) 
    {
        $check = "Vui lòng nhập và chọn đầy đủ thông tin";
    }
    else
    {
        if ($mk !== $mk1) 
        {
            $check = "Mật khẩu và nhập lại mật khẩu không trùng khớp.";
        }
        if (empty($check) && check_tk($tk))
        {
            $check = "Tên tài khoản đã tồn tại.";
        }
    }   
    
    if (empty($check)) 
    {
        $hash_mk = md5($mk); //mã hóa 
        $sql = "INSERT INTO nguoi_dung (hoten, tk, mk, loaitk) VALUES ('$hoten', '$tk', '$hash_mk', '$loaitk')";

        if (mysqli_query($conn, $sql)) 
        {
            showAlert("Đăng ký thành công!", 'success');
        } 
        else 
        {
            showAlert("Có lỗi xảy ra khi lưu vào database!", 'error');
        }
    } 
    else 
    {
            showAlert($check, 'error');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="css/dk.css">
    <style>
        .mk 
        {
            position: relative;
        }
        .mk input[type="password"] 
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
            user-select: none; /* Ngăn chọn biểu tượng */
        }
    </style>
    
</head>
<body>
    <div class="dangky">
        <h1>Đăng ký</h1>
        <form action="" method="get">
            <label for="hoten">Nhập họ tên</label><br>
            <input type="text" name="hoten" value="<?php echo isset($hoten) ? $hoten : ''; ?>"><br>
            <label for="tk">Nhập tài khoản</label><br>
            <input type="text" name="tk" value="<?php echo isset($tk) ? $tk : ''; ?>"><br>
            <label for="mk">Nhập mật khẩu</label><br>
            <div class="mk">
                <input type="password" id="mk" name="mk" value="<?php echo isset($mk) ? $mk : ''; ?>">
                <span class="hien_mk" onclick="showPassword('mk')">&#128065;</span>
            </div>
            <label for="mk1">Nhập lại mật khẩu</label><br>
            <div class="mk">
                <input type="password" id="mk1" name="mk1" value="<?php echo isset($mk1) ? $mk1 : ''; ?>"><br>
                <span class="hien_mk" onclick="showPassword('mk1')">&#128065;</span>
            </div>
            <label for="loaitk">Chọn loại tài khoản</label><br>
            <select name="loaitk">
                <option value="">- - -</option>
                <option value="Admin" <?php if (isset($loaitk) && $loaitk === 'Admin') echo 'selected'; ?>>Admin</option>
                <option value="Giáo viên" <?php if (isset($loaitk) && $loaitk === 'Giáo viên') echo 'selected'; ?>>Giáo viên</option>
                <option value="Học sinh" <?php if (isset($loaitk) && $loaitk === 'Học sinh') echo 'selected'; ?>>Học sinh</option>
            </select>
            <button type="submit" name="submit">Đăng ký</button>
            <a href="dang_nhap.php" >Trang đăng nhập &#8594;</a>
        </form>
    </div>
    <?php
    hien_mk(); //gọi hàm này trong thẻ head hoặc body để đảm bảo các phần tử html đã xuất hiện trước
    //nếu để ở đầu file thì sẽ không thực hiện được vì trình duyệt có thể không tìm thấy phần tử pw
    ?>
</body>
</html>
