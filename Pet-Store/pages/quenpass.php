<?php
$thongbao = "";

if (isset($_POST['btn1'])) {
    $email = trim(strip_tags($_POST['email'])); // Tiếp nhận email

    // Kiểm tra định dạng email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $thongbao .= "Email không đúng <br>"; 
    } else {
        require_once 'config.php';
        $sql = "SELECT count(*) FROM users WHERE email = '{$email}'";
        $kq = $conn->query($sql);
        $row = $kq->fetch();

        if ($row[0] == 0) {
            $thongbao .= "Email này không phải là thành viên <br>";
        } else {
            $pass_moi = substr(md5(random_int(0, 9999)), 0, 8);
            $sql_update = "UPDATE users SET pass='{$pass_moi}' WHERE email='{$email}'";
            $kq_update = $conn->query($sql_update);

            if ($kq_update) {
                $thongbao .= "Cập nhật mật khẩu thành công<br>";

                require_once "PHPMailer-master/src/PHPMailer.php";
                require_once "PHPMailer-master/src/Exception.php";
                require_once "PHPMailer-master/src/SMTP.php";

                $mail = new PHPMailer\PHPMailer\PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = '2251120353@ut.edu.vn';
                    $mail->Password = 'huylinh2k';
                    $mail->SMTPSecure = 'TLS';
                    $mail->Port = 587;
                    $mail->CharSet = "UTF-8";

                    // Cấu hình SSL
                    $mail->smtpConnect([
                        "ssl" => [
                            "verify_peer" => false,
                            "verify_peer_name" => false,
                            "allow_self_signed" => true
                        ]
                    ]);

                    $mail->setFrom('diachi@gmail.com', 'Ban quản trị website');
                    $mail->addAddress($email, 'Quý khách');
                    $mail->isHTML(true);
                    $mail->Subject = 'Cấp lại mật khẩu mới';
                    $mail->Body = "Đây là mật khẩu mới của bạn: <b>{$pass_moi}</b>";

                    $mail->send();
                    $thongbao .= "Đã gửi mail thành công<br>";
                } catch (Exception $e) {
                    $thongbao .= "Lỗi khi gửi thư: " . $mail->ErrorInfo . "<br>";
                }

            } else {
                $thongbao .= "Cập nhật mật khẩu không thành công<br>";
            }
        }
    }
}
?>
<!-- Modal quên mật khẩu -->
<div id="forgotPasswordModal" class="modal" style="display: none">
    <div class="modal-content">
        <span id="closeForgotPasswordModalButton" class="close">&times;</span>
        <h2>Quên Mật Khẩu</h2>
        <form id="forgotPasswordForm" method="POST">
            <div class="form-group">
                <label for="email">Nhập email</label>
                <input id="email" class="form-control" name="email" type="email" required>
            </div>
            <div class="form-group">
                <button type="submit" name="btn1">Gửi yêu cầu</button>
            </div>
        </form>
    </div>
</div>
<script src="../asset/js/pass.js"></script>