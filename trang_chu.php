<?php
include '../function.php';
if (!isset($_COOKIE['login'])) //ktra dang nhap chua
{
    //điều hướng nếu chưa đăng nhập
    header('Location: dang_nhap.php');
    exit();
}
if (isset($_GET['dx'])) //dang xuat
{
    //đưa thời gian sống cookie về âm để hủy
    setcookie('hoten', '', time() - 3600, '/');
    setcookie('tk', '', time() - 3600, '/');
    setcookie('mk', '', time() - 3600, '/');
    setcookie('loaitk', '', time() - 3600, '/');
    setcookie('login', '', time() - 3600, '/');
    
    header('Location: dang_nhap.php');
    exit();
}
$id = $_COOKIE['id'];
$loaitk = $_COOKIE['loaitk'];
$hoten = $_COOKIE['hoten'];
$ds_nganh = ds_nganh();
$ds_gv = ds_gv();
$ds_khoi = ds_khoi();
$ds_phan_cong = ds_phan_cong();

if (isset($_GET['them_nganh']))
{
    $id_nganh = strtoupper(trim($_GET['id_nganh'])); //strtoupper để chuyển chữ cái thành in hoa phù hợp với dữ liệu id
    $ten_nganh = ucwords(trim($_GET['ten_nganh']));  //ucwords để chuyển chữ cái đầu tiên thành in hoa
    $khoi_xet_tuyen = ucwords(trim($_GET['khoi_xet_tuyen']));
    $tg_bat_dau = $_GET['tg_bat_dau'];
    $tg_ket_thuc = $_GET['tg_ket_thuc'];
    $start = new DateTime($tg_bat_dau); //chuyển sang kiểu này để so sánh hai ngày vì kiểu DATE k ss được
    $end = new DateTime($tg_ket_thuc);
    $trang_thai = $_GET['trang_thai'];

    tao_nganh($id_nganh, $ten_nganh, $khoi_xet_tuyen, $tg_bat_dau, $tg_ket_thuc, $start, $end, $trang_thai);
}

if (isset($_GET['reset_form']))
{
    header('Location: trang_chu.php');
}

if (isset($_GET['sua_nganh']))
{
    $id_sua = $_GET['id_sua'];
    $ten_truoc = ''; 
    $khoi_truoc = '';
    foreach ($ds_nganh as $nganh) 
    {
        if ($nganh['id_nganh'] == $id_sua) 
        {
            $ten_truoc = $nganh['ten_nganh'];
            $khoi_truoc = $nganh['khoi_xet_tuyen'];
            break;  // Dừng vòng lặp khi tìm thấy ngành
        }
    }

    $ten_nganh = ucwords(trim($_GET['ten' . $id_sua])); 
    $khoi_xet_tuyen = ucwords(trim($_GET['khoi' . $id_sua]));
    $tg_bat_dau = $_GET['tg_bat_dau' . $id_sua];
    $tg_ket_thuc = $_GET['tg_ket_thuc' . $id_sua];
    $start = new DateTime($tg_bat_dau); //chuyển sang kiểu này để so sánh hai ngày vì kiểu DATE k ss được
    $end = new DateTime($tg_ket_thuc);
    $trang_thai = $_GET['trang_thai' . $id_sua];

    sua_nganh($id_sua, $ten_truoc, $khoi_truoc, $ten_nganh, $khoi_xet_tuyen, $tg_bat_dau, $tg_ket_thuc, $start, $end, $trang_thai);
}

if (isset($_GET['xoa_nganh']))
{
    $id_xoa = $_GET['id_hidden'];
    xoa_nganh($id_xoa);
}

if (isset($_GET['phan_quyen']))
{
    $id_nganh_pq = $_GET['id_hidden'];
    $id_gv = '';
    $ten_gv = $_GET['phan_quyen_gv'.$id_nganh_pq];
    if ($ten_gv == '')
    {
        showAlert('Bạn chưa chọn giáo viên nào', 'error');
    }
    else 
    {
        foreach ($ds_gv as $gv)
        {
            if ($ten_gv == $gv['hoten']) $id_gv = $gv['id'];
        }
        phan_quyen($id_nganh_pq, $id_gv, $ten_gv);
    }
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Tuyển Sinh</title>
    <link rel="stylesheet" href="css/trangchu.css">
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="top-bar">
        <div>
            <?php
            if ($loaitk && $hoten) 
            {
                echo "Xin chào, $loaitk $hoten";
            }
            ?>
        </div>
        <form method="GET">
            <button type="submit" name="dx" class="logout-btn">Đăng xuất</button>
        </form>
    </div>
    <div class="container">
        <header>
            <h1>Chào mừng đến với Trang Tuyển Sinh HNUE</h1>
        </header>

        <div id="content">
            <h2>Danh sách các ngành xét tuyển hồ sơ học bạ&ensp;&#8595;</h2>
            <?php if ($loaitk == 'Học sinh'): ?>
                <div class="section">
                    <ul class="program-list">
                        <?php foreach ($ds_nganh as $nganh): ?>
                            <?php if ($nganh['trang_thai'] == 1): ?> <!--ngành nào được hiện mới hiển thị  -->
                                <li>
                                    <span class="program-name">Ngành <?php echo $nganh['ten_nganh']; ?></span>
                                    <span class="program-info">
                                        Khối: <?php echo $nganh['khoi_xet_tuyen']; ?> | 
                                        Thời gian bắt đầu: <?php echo $nganh['tg_bat_dau']; ?> | 
                                        Thời gian kết thúc: <?php echo $nganh['tg_ket_thuc']; ?>
                                    </span>
                                    <a class="nop_hs" href="nop_ho_so.php">Nộp hồ sơ</a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>

            <?php elseif ($loaitk == 'Admin'): ?>
                <form method="GET">
                    <button type="button" id="btn_tao_nganh" class="tao-nganh">Tạo ngành xét tuyển</button>
                        <div class="form-tao-nganh" id="form-tao-nganh" style="display:none;">
                            <label for="id_nganh">Nhập ID Ngành:</label>
                            <input type="text" id="id_nganh" name="id_nganh" required
                            value="<?php echo isset($id_nganh) ? $id_nganh : ''; ?>">
                            
                            <label for="ten_nganh">Nhập Tên Ngành:</label>
                            <input type="text" id="ten_nganh" name="ten_nganh" required
                            value="<?php echo isset($ten_nganh) ? $ten_nganh : ''; ?>">
                            
                            <label for="khoi_xet_tuyen">Nhập Khối Xét Tuyển:</label>
                            <input type="text" id="khoi_xet_tuyen" name="khoi_xet_tuyen" required
                            value="<?php echo isset($khoi_xet_tuyen) ? $khoi_xet_tuyen : ''; ?>">
                            
                            <label for="thoi_gian_bat_dau">Chọn Thời Gian Bắt Đầu:</label>
                            <input type="date" id="thoi_gian_bat_dau" name="tg_bat_dau" required
                            value="<?php echo isset($tg_bat_dau) ? $tg_bat_dau : ''; ?>">
                            
                            <label for="thoi_gian_ket_thuc">Chọn Thời Gian Kết Thúc:</label>
                            <input type="date" id="thoi_gian_ket_thuc" name="tg_ket_thuc" required
                            value="<?php echo isset($tg_ket_thuc) ? $tg_ket_thuc : ''; ?>">
                            
                            <label for="trang_thai">Chọn Trạng Thái:</label>
                            <select id="trang_thai" name="trang_thai" required>
                                <option value="0">0 - Ẩn</option>
                                <option value="1" selected>1 - Hiện</option>
                            </select>
                            <button name="them_nganh" type="submit">Thêm ngành</button>
                            <a class="reset" href="trang_chu.php" >Xóa dữ liệu vừa nhập</a>
                        </div>
                </form>
                <div class="section">
                    <ul class="program-list">
                        <?php foreach ($ds_nganh as $nganh): ?>
                            <li>
                                <span class="program-name">Ngành <?php echo $nganh['ten_nganh']; ?></span>
                                <span class="program-info">
                                    Khối: <?php echo $nganh['khoi_xet_tuyen']; ?> | 
                                    Thời gian bắt đầu: <?php echo $nganh['tg_bat_dau']; ?> | 
                                    Thời gian kết thúc: <?php echo $nganh['tg_ket_thuc']; ?>
                                </span>
                                <form method="get" >
                                    <a class="nop_hs" href="nop_ho_so.php">Nộp hồ sơ</a>
                                    <button class="btn-sua" type="button" onclick="hien_sua('<?php echo $nganh['id_nganh']; ?>')">Sửa</button>
                                    <button class="btn-sua" onclick="return confirm('Bạn có chắc chắn muốn xóa ngành này không?');" 
                                            type="submit" name="xoa_nganh">Xóa</button>
                                    <input type="hidden" name="id_hidden" value="<?php echo $nganh['id_nganh']; ?>"> 
                                    <button class="btn-sua" type="button" onclick="hien_phan_quyen('<?php echo $nganh['id_nganh']; ?>')">Phân quyền duyệt</button>
                                    
                                    <div class="form-tao-nganh" id="form-sua<?php echo $nganh['id_nganh']; ?>" style="display:none;">
                                        <input type="hidden" name="id_sua" value="<?php echo $nganh['id_nganh']; ?>"> <!-- ID ngành sẽ được gửi cùng dữ liệu -->
                                        <label for="ten_nganh">Sửa Tên Ngành:</label>
                                        <input type="text" name="ten<?php echo $nganh['id_nganh']; ?>" value="<?php echo $nganh['ten_nganh']; ?>" required>

                                        <label for="khoi_xet_tuyen">Sửa Khối Xét Tuyển:</label>
                                        <input type="text" name="khoi<?php echo $nganh['id_nganh']; ?>" value="<?php echo $nganh['khoi_xet_tuyen']; ?>" required>

                                        <label for="thoi_gian_bat_dau">Sửa Thời Gian Bắt Đầu:</label>
                                        <input type="date" name="tg_bat_dau<?php echo $nganh['id_nganh']; ?>" value="<?php echo $nganh['tg_bat_dau']; ?>" required>

                                        <label for="thoi_gian_ket_thuc">Sửa Thời Gian Kết Thúc:</label>
                                        <input type="date" name="tg_ket_thuc<?php echo $nganh['id_nganh']; ?>" value="<?php echo $nganh['tg_ket_thuc']; ?>" required>

                                        <label for="trang_thai">Sửa Trạng Thái:</label>
                                        <select id="trang_thai" name="trang_thai<?php echo $nganh['id_nganh']; ?>" required>
                                            <option value="0" <?php if ($nganh['trang_thai'] == 0) echo 'selected';?>>0 - Ẩn</option>
                                            <option value="1" <?php if ($nganh['trang_thai'] == 1) echo 'selected';?>>1 - Hiện</option>
                                        </select>
                                        <button name="sua_nganh" type="submit">Lưu chỉnh sửa</button>
                                    </div>

                                    <div class="form-tao-nganh" id="phan_quyen<?php echo $nganh['id_nganh']; ?>" style="display:none;">
                                        <form method="get">
                                        <label for="trang_thai">Chọn giáo viên để duyệt hồ sơ</label>
                                        <select name="phan_quyen_gv<?php echo $nganh['id_nganh']; ?>">
                                            <option value=""?>-- Vui lòng chọn một giáo viên --</option>
                                            <?php foreach ($ds_gv as $gv): ?>
                                                <option
                                                value="<?php echo $gv['hoten']; ?>" 
                                                <?php foreach($ds_phan_cong as $phan_cong) 
                                                    if ($nganh['id_nganh'] == $phan_cong['id_nganh'] && $gv['id'] == $phan_cong['id_giao_vien'])
                                                    echo 'selected';
                                                ?>>
                                                <?php echo $gv['hoten']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button name="phan_quyen" type="submit">Lưu</button>
                                        </form>
                                    </div>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                        
            <?php elseif ($loaitk == 'Giáo viên'): ?>
                <div id="teacher-section" class="section">
                    <ul class="program-list">
                        <?php foreach ($ds_nganh as $nganh): ?>
                            <?php foreach ($ds_phan_cong as $phan_cong): ?>
                                <?php if ($nganh['trang_thai'] == 1 && //ngành nào được phân công và hiện mới hiển thị 
                                ($phan_cong['id_nganh'] == $nganh['id_nganh'] && $phan_cong['id_giao_vien'] ==  $id)): ?> 
                                    <li>
                                        <span class="program-name">Ngành <?php echo $nganh['ten_nganh']; ?></span>
                                        <span class="program-info">
                                            Khối: <?php echo $nganh['khoi_xet_tuyen']; ?> | 
                                            Thời gian bắt đầu: <?php echo $nganh['tg_bat_dau']; ?> | 
                                            Thời gian kết thúc: <?php echo $nganh['tg_ket_thuc']; ?>
                                        </span>
                                        <a class="nop_hs" href="nop_ho_so.php">Nộp hồ sơ</a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php else: ?>
                <div class="section">
                    <p>Không có thông tin tài khoản hợp lệ.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
        hien_tao_nganh(); 
        hien_sua_nganh();
        hien_phan_quyen();
    ?>
</script>
</body>
</html>