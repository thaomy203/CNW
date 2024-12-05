<?php
include "connectdb.php";
//biến $conn ở file connect là một biến kết nối tới csdl
function check_tk($username) //check xem tài khoản đã tồn tại chưa
{
    $ds_tk = ds_tai_khoan();
    foreach ($ds_tk as $tk)
    {
        if ($username == $tk['tk'])
        {
            return true;
        }
    }
    return false;
}

function showAlert($text, $icon = 'success') //hàm show kết quả đăng ký tk, tạo ngành,...
{
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script type='text/javascript'>
            window.onload = function() {
                Swal.fire({
                    title: 'Thông báo',
                    text: '{$text}',
                    icon: '{$icon}',
                    confirmButtonText: 'Đóng'
                });
            }
          </script>";
}

function hien_mk() //JS - hàm giúp hiện mật khẩu 
{
    echo '<script>
        function showPassword(id) 
        {
            const input = document.getElementById(id); 
            if (input.type === "password") 
            {
                input.type = "text";
            } 
            else 
            {
                input.type = "password";
            }
        }
        </script>';
}

function ds_tai_khoan() //hàm lấy ds tk từ DB (full cột)
{
    global $conn; //lấy biến cục bộ từ file connectdb nếu không truyền $conn vào
    $ds = [];  
    $sql = 'SELECT * FROM `nguoi_dung`'; 
    $result = mysqli_query($conn, $sql); 
    if (!$result) 
    {
        die("Lỗi truy vấn: " . mysqli_error($conn)); //kiểm tra lỗi truy vấn
    }
    if (mysqli_num_rows($result) > 0) //kiểm tra bảng có dữ liệu nào k 
    { 
        while ($row = mysqli_fetch_array($result)) 
        { 
            $ds[] = $row; //thêm dữ liệu vào mảng
        }
        return $ds; 
    } 
    else return []; //trả về mảng rỗng 
    
}

function ds_nganh() //hàm lấy ds các ngành xét tuyển (full cột)
{
    global $conn;
    $ds_nganh = [];
    $sql = 'SELECT * FROM `nganh` ORDER BY `tg_ket_thuc` DESC'; //sắp xếp ngày kết thúc muộn nhất
    $result = mysqli_query($conn, $sql);
    if (!$result)
    {
        die("Lỗi truy vấn: " . mysqli_error($conn));
    }
    if (mysqli_num_rows($result) > 0)
    {
        while ($row = mysqli_fetch_array($result))
        {
            $ds_nganh[] = $row;
        }
        return $ds_nganh;
    }
    else return [];
}

function hien_tao_nganh() //JS - hàm hiện form tạo ngành
{
    echo '<script>
        // Khi nhấn vào nút tạo ngành
        document.getElementById("btn_tao_nganh").addEventListener("click", function() 
        {
            var form = document.getElementById("form-tao-nganh");
            // Kiểm tra trạng thái hiển thị, nếu đang ẩn thì hiện lên, nếu đang hiện thì ẩn đi
            if (form.style.display === "none" || form.style.display === "") 
            {
                form.style.display = "block";  // Hiện form
            } 
            else form.style.display = "none";  // Ẩn form
        });
    </script>';
}

function hien_sua_nganh() //JS - hàm hiện form sửa ngành
{
    echo '<script>
        function hien_sua(id) 
        {
            const form = document.getElementById(`form-sua${id}`);
            if (form.style.display === "none" || form.style.display === "") 
            {
                form.style.display = "block"; // Hiện form
            } 
            else form.style.display = "none"; 
        }
    </script>';
}

function hien_phan_quyen() //JS - hàm hiện lựa chọn phân quyền
{
    echo '<script>
        function hien_phan_quyen(id) 
        {
            const form = document.getElementById(`phan_quyen${id}`);
            if (form.style.display === "none" || form.style.display === "") 
            {
                form.style.display = "block"; // Hiện form
            } 
            else form.style.display = "none"; 
        }
    </script>';
}

function ds_gv() //hàm lấy ds giáo viên từ bảng nguoi_dung
{
    global $conn;
    $ds_gv = [];
    $sql = 'SELECT * FROM `nguoi_dung` WHERE `loaitk` = "Giáo viên"';
    $result = mysqli_query($conn, $sql);
    if (!$result)
    {
        die("Lỗi truy vấn: " . mysqli_error($conn));
    }
    if (mysqli_num_rows($result) > 0)
    {
        while ($row = mysqli_fetch_array($result))
        {
            $ds_gv[] = $row;
        }
        return $ds_gv;
    }
    else return [];
}

function ds_khoi() //lấy các khối xét tuyển hợp lệ (trả về mảng tuần tự chỉ chứa tên khối)
{
    global $conn;
    $ds_khoi = [];
    $sql = 'SELECT DISTINCT `khoi_xet_tuyen` FROM `khoi`'; 
    $result = mysqli_query($conn, $sql);
    if (!$result)
    {
        die("Lỗi truy vấn: " . mysqli_error($conn));
    }
    if (mysqli_num_rows($result) > 0)
    {
        while ($row = mysqli_fetch_array($result))
        {
            $ds_khoi[] = $row['khoi_xet_tuyen'];
        }
        return $ds_khoi;
    }
    else return [];
}

function ds_phan_cong() //lấy danh sách phân công (full cột)
{
    global $conn;
    $ds_phan_cong = [];
    $sql = 'SELECT * FROM `phan_cong`'; 
    $result = mysqli_query($conn, $sql);
    if (!$result)
    {
        die("Lỗi truy vấn: " . mysqli_error($conn));
    }
    if (mysqli_num_rows($result) > 0)
    {
        while ($row = mysqli_fetch_array($result))
        {
            $ds_phan_cong[] = $row;
        }
        return $ds_phan_cong;
    }
    else return [];
}

function check_id_nganh($id) //kiểm tra id_nganh đã tồn tại chưa
{
    $ds_nganh = ds_nganh();
    foreach ($ds_nganh as $nganh)
    {
        if ($id == $nganh['id_nganh'])
        return true; //trùng id ngành
    }
    return false;
}

function check_ktdb($str)
{
    $ktdb = "!@#$%^&*()_+-=[]{}|;:'\",.<>?/~";
    return strpbrk($str, $ktdb) !== false; //hàm tìm kí tự, sẽ trả về chuỗi tính từ kí tự đó trở đi
    //sau đó so sánh với false, nếu trả chuỗi => khác false, hàm check return true
    // nếu chuỗi rỗng hàm strpbrk trả false => không khác false (== false), hàm check return false
}

function check_khoi($khoi) //kiểm tra khối có hợp lệ không
{
    $ds_khoi = ds_khoi();
    if (in_array($khoi, $ds_khoi)) return true;
    else return false;
}

function check_ten_khoi($ten, $khoi) 
{
    $ds_nganh = ds_nganh();
    foreach ($ds_nganh as $nganh)
    {
        if ($ten == $nganh['ten_nganh'] && $khoi == $nganh['khoi_xet_tuyen'])
        return true; //vì ngành có thể trùng tên nhau nhưng khối xét tuyển phải khác nhau 
                    // => true: trùng cả hai = không hợp lệ
    }
    return false;
}

function tao_nganh($id, $ten, $khoi, $tg1, $tg2, $start, $end, $trangthai)
{
    if (check_id_nganh($id)) showAlert('Mã ngành đã tồn tại', 'error');

    else if (check_ktdb($id)) showAlert('Mã ngành không được chứa kí tự đặc biệt', 'error');
    
    else if (check_ktdb($ten)) showAlert('Tên ngành không được chứa kí tự đặc biệt', 'error');

    else if (!check_khoi($khoi)) showAlert('Khối xét tuyển không hợp lệ', 'error');

    else if (check_ten_khoi($ten, $khoi)) showAlert('Ngành và khối xét tuyển này đã tồn tại', 'error');

    else if ($start > $end) showAlert('Ngày bắt đầu đang ở sau ngày kết thúc', 'error');

    else if ($start == $end) showAlert('Ngày bắt đầu đang trùng với ngày kết thúc', 'error');

    else
    {
        global $conn;
        $sql = "INSERT INTO `nganh` (`id_nganh`, `ten_nganh`, `khoi_xet_tuyen`, `tg_bat_dau`, `tg_ket_thuc`, `trang_thai`) 
                VALUES ('$id', '$ten', '$khoi', '$tg1', '$tg2', '$trangthai');";
        $result = mysqli_query($conn, $sql);
        if (!$result)
        {
            die("Lỗi truy vấn: " . mysqli_error($conn));
        }
        else 
        {
            showAlert('Thêm ngành xét tuyển thành công!', 'success'); 
            echo "<script>
                    setTimeout(function() 
                    {
                    window.location.href = 'trang_chu.php'; //làm mới trang và không giữ lại dữ liệu trong form
                    }, 2000); //chuyển hướng sau 2 giây, đơn vị ở đây là ms(mili giây)
                </script>";
            //Do chuyển hướng bằng header để reset lại form thì sẽ không hiện showAlert 
            //vì nó chuyển hướng quá nhanh nên dùng js
        }
    }
}

function sua_nganh($id, $ten0, $khoi0, $ten, $khoi, $tg1, $tg2, $start, $end, $trangthai)
{
    if (check_ktdb($ten)) showAlert('Tên ngành không được chứa kí tự đặc biệt', 'error');

    else if (!check_khoi($khoi)) showAlert('Khối xét tuyển không hợp lệ', 'error');

    else if (($ten !== $ten0 || $khoi !== $khoi0) && (check_ten_khoi($ten, $khoi)))
        showAlert('Ngành và khối xét tuyển này đã tồn tại', 'error'); 
    else if ($start > $end) showAlert('Ngày bắt đầu đang ở sau ngày kết thúc', 'error');

    else if ($start == $end) showAlert('Ngày bắt đầu đang trùng với ngày kết thúc', 'error');

    else
    {
        global $conn;
        $sql = "UPDATE `nganh` SET `ten_nganh` = '$ten', `khoi_xet_tuyen` = '$khoi', `tg_bat_dau` = '$tg1',
                 `tg_ket_thuc` = '$tg2', `trang_thai` = '$trangthai' WHERE `id_nganh` = '$id';";
        $result = mysqli_query($conn, $sql);
        if (!$result)
        {
            die("Lỗi truy vấn: " . mysqli_error($conn));
        }
        else 
        {
            showAlert('Sửa ngành xét tuyển thành công!', 'success'); 
            echo "<script>
                    setTimeout(function() 
                    {
                        window.location.href = 'trang_chu.php'; //làm mới trang và không giữ lại dữ liệu trong form
                    }, 2000);
                </script>";
        }
    }
}

function xoa_nganh($id)
{
    global $conn;
    $sql = "DELETE FROM `nganh` WHERE `id_nganh` = '$id'";
    $result = mysqli_query($conn, $sql);
    if (!$result)
    {
        die("Lỗi truy vấn: " . mysqli_error($conn));
    }
    else 
    {
        showAlert('Xóa ngành xét tuyển thành công!', 'success'); 
        echo "<script>
                setTimeout(function() 
                {
                    window.location.href = 'trang_chu.php'; //làm mới trang và không giữ lại dữ liệu trong form
                }, 1000);
            </script>";
    }
}

function phan_quyen($id, $id_gv, $ten)
{

    global $conn;
    $sql = "INSERT INTO `phan_cong` (`id_nganh`, `id_giao_vien`, `ten_gv`) VALUES ('$id', '$id_gv', '$ten')
            ON DUPLICATE KEY UPDATE `id_giao_vien` = '$id_gv'";
    $result = mysqli_query($conn, $sql);
    if (!$result)
    {
        die("Lỗi truy vấn: " . mysqli_error($conn));
    }
    else 
    {
        showAlert('Phân quyền giáo viên xét tuyển thành công!', 'success'); 
        echo "<script>
                setTimeout(function() 
                {
                    window.location.href = 'trang_chu.php'; //làm mới trang và không giữ lại dữ liệu trong form
                }, 1000);
            </script>";
    }
}


?>