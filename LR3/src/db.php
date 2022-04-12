<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../vendor/autoload.php';

$ini_array = parse_ini_file("parameters.ini", true);

$dsn = "mysql:host=$ini_array[host];dbname=$ini_array[db];";
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $pdo = new PDO($dsn, $ini_array['user'], $ini_array['pass'], $opt);
} catch (PDOException $e) {
    print "Has errors: " . $e->getMessage();
    die();
}

$name = $_POST['name'];
$email = $_POST['email'];
$tel = $_POST['tel'];
$comment = $_POST['comment'];
$now = date('H:m:i d.m.Y', strtotime('now + 1 hours + 30 minutes'));

if (! empty($pdo)) {
    $emailFlag = checkEmail($pdo, $email);

    # до этого не использовался
    if ($emailFlag == false) {
        # вносим запись в БД
        addRecord($pdo, $name, $email, $tel, $comment);
        echo json_encode([
            'date' => $now, # с вами свяжутся после указанного времени
            'time' => null
        ]);
        sendMail($ini_array['email'], $ini_array['password'], $ini_array['secondaryEmail'], $name, $email, $tel, $comment);
    } else {
        # находим разницу во времени между тем когда была отправлена новая заявка и старой записью
        $differenceTime = diffTime($pdo, $emailFlag, $email);
        # если время > 60 -- можно отправлять новую заявку (час прошел)
        if ($differenceTime > 60) {
            addRecord($pdo, $name, $email, $tel, $comment);
            echo json_encode([
                'date' => $now, # с вами свяжутся после указанного времени
                'time' => null
            ]);
            sendMail($ini_array['email'], $ini_array['password'], $ini_array['secondaryEmail'], $name, $email, $tel, $comment);
        } else {
            echo json_encode([
                'time' => 60 - $differenceTime, # осталось подождать
                'date' => null
            ]);
        }
    }
}
/**
 * @param PDO $pdo
 * @param string $email
 * @return mixed
 */
function checkEmail(PDO $pdo, string $email): mixed
{
    $query = 'SELECT *
              FROM feedback f 
              WHERE f.email = :email 
              ORDER BY f.date DESC 
              LIMIT 1';
    $params = [
        'email' => $email
    ];
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    # такого email нет в БД
    if ($stmt->rowCount() == 0) {
        return false;
    } else {
        $row = $stmt->fetch(PDO::FETCH_LAZY);
        return $row->date;
    }
}

/**
 * @param PDO $pdo
 * @param $dbDate
 * @param $email
 * @return mixed
 */
function diffTime(PDO $pdo, $dbDate, $email): mixed
{
    $query = 'SELECT TIMESTAMPDIFF(MINUTE, date ,now()) AS diff
              FROM feedback f 
              WHERE f.email = :email 
              AND f.date = :date';
    $params = [
        'email' => $email,
        'date' => $dbDate
    ];
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $row = $stmt->fetch(PDO::FETCH_LAZY);
    return $row->diff;
}

/**
 * @param PDO $pdo
 * @param string $name
 * @param string $email
 * @param string $phone
 * @param string $comment
 * @return void
 */
function addRecord(PDO $pdo, string $name, string $email, string $phone, string $comment): void
{
    $query = "INSERT INTO feedback (name, email, phone, comment) VALUES (:name, :email, :phone, :comment)";
    $params = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'comment' => $comment,
    ];
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
}

function sendMail(string $adminEmail, string $adminPassword, string $secondaryEmail, string $name, string $email, string $phone, string $comment)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $adminEmail;
        $mail->Password = $adminPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom($adminEmail, 'Feedback');
        $mail->addAddress($secondaryEmail, 'Feedback');
        $mail->addReplyTo($adminEmail, 'Feedback');

        $mail->isHTML(true);
        $mail->Subject = 'Notification!';
        $mail->Body = 'Было оставлено сообщение в форме обратной связи.<br><b>Автор: ' . $name . '</b>.<br><b>Email автора: ' . $email . '</b>.<br><b>Телефон: ' . $phone . '</b>.<br><b>Сообщение: ' . $comment . '</b>.';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
