<?php 
$cookiename = "user";
$cookievalue = "VHung";
setcookie($cookiename,$cookievalue,time() + (86400*30),"/");
if(!isset($_COOKIE[$cookiename])){
    echo "Cookie named '" . $cookiename . "' is not set!";
}else{
    echo "Cookie " . $cookiename . "' is set!<br>";
    echo "Value is: " . $_COOKIE[$cookiename];

}
?>
//cookiename là tên của cookie.Nó dùng để xác định cookie khi bạn muốn lưu, lấy, hoặc xóa cookie.
//cookievalue là giá trị của cookie. Nó chứa dữ liệu mà bạn muốn lưu trữ trong trình duyệt.