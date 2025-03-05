    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <style>
            .error {
                color: red;
            }
            </style>
    </head>
    <body>
        <?php
        $namError = "";
        if($_SERVER["REQUEST_METHOD"]== "POST"){
            if(empty($_POST['name'])){
                $namError = "Không được để trống";
            }else{
                echo $_POST['name'];
            }
        }
        ?>
        <form action = "index.php" method = "post" >
            Tên :<input type = "text" name = "name">
            <span class = "error"> <?php echo $namError ?> </span>
            <br>
            <input type = "submit" name = "submit" value = "Gửi">

        </form>
    </body>
    </html>
   // Form HTML trên client-side (trình duyệt của người dùng) được dùng để thu thập dữ liệu từ người dùng và gửi đến server khi nhấn nút "Submit".(phía máy chủ đưa ra các yêu
  // cầu , lựa chọn, phía cliet chỉ việc chọn sau đó dữ liệu được đưa về phía sever để xử lí )
   //👉 PHP trên server-side (máy chủ) nhận dữ liệu này, xử lý nó và phản hồi lại kết quả cho client.
