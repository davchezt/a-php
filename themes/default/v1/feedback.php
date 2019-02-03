<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$this->layout('layout/json');

$uid = isset($_POST['uid']) ? intval($_POST['uid']) :null;
$token = isset($_POST['token']) ? trim($_POST['token']) :null;

// POST
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$body = isset($_POST['body']) ? trim($_POST['body']) : '';
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$type = isset($_POST['type']) ? trim($_POST['type']) : '';

$id = $this->user()->id ? $this->user()->id : $uid;
$ctoken = $this->user()->checkToken($uid, $token) ? 1 : $id;
$data = get_respon_code(401);
if ($ctoken) {
    http_response_code(200);
    $data = get_respon_code();
    $data = array_merge($data, array("data" => null));
    
    $mail = new PHPMailer(true);                                                    // Passing `true` enables exceptions
    try {
        // Server settings
        $mail->SMTPDebug = -1;                                                       // Enable verbose debug output
        $mail->isSMTP();                                                            // Set mailer to use SMTP
        $mail->Host = 'mail.agritama.farm';                                         // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                                                     // Enable SMTP authentication
        $mail->Username = 'dev@agritama.farm';                                      // SMTP username
        $mail->Password = '4Bahagia4';                                              // SMTP password
        $mail->SMTPSecure = 'ssl';                                                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                                          // TCP port to connect to

        // Recipients
        $mail->setFrom(trim($email), trim($name));
        if ($type == "feedback") {
            $mail->addAddress('info@agritama.farm', 'Agritama Information Center'); // Add a recipient info@agritama.farm || dev@agritama.farm
            // $mail->addAddress('dev@agritama.farm', 'Agritama Developer Team');
        }
        else {
            $mail->addAddress('dev@agritama.farm', 'Agritama Developer Team');      // Name is optional
        }
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');                            // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');                       // Optional name

        // Content
        $mail->isHTML(true);                                                        // Set email format to HTML
        $mail->Subject = strip_tags(trim($subject));
        $mail->Body    = trim($body);
        $mail->AltBody = strip_tags($body);

        $mail->send();
        $data = array_merge($data, array("data" => "email telah terkirim, terimakasih."));
    } catch (Exception $e) {
        // $mail->ErrorInfo
        $data = array_merge($data, array("data" => "tidak dapat mengirimkan email, mail server error"));
    }
}
else {
    http_response_code(401);
    $data = array_merge($data, array("data" => null));
}
echo json_encode($data, JSON_PRETTY_PRINT);
?>