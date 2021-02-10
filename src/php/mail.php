<?php
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

// Проверяем отравленность сообщения
function SendMail($mail, &$status)
{
  if ($mail->send())
    $status = "Сообщение успешно отправлено";
  else
    $status =  "Сообщение не было отправлено. Причина ошибки: " . $mail->ErrorInfo;
}

function CheckEmpty($variable) {
  if ($variable != '') return $variable;
  else return 'Не указано';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // Настройки PHPMailer
  $mail = new PHPMailer\PHPMailer\PHPMailer();

  $mail->isSMTP();
  $mail->CharSet = "UTF-8";
  $mail->SMTPAuth   = true;
  $mail->isHTML(true);
  $mail->Debugoutput = function ($str, $level) {
    $GLOBALS['status'][] = $str;
  };

  // Настройки вашей почты
  $mail->Host       = 'smtp.yandex.ru'; // SMTP сервера вашей почты
  $mail->Username   = 'inbox@talantika.pro'; // Логин на почте
  $mail->Password   = ''; // Пароль на почте
  $mail->SMTPSecure = 'ssl';
  $mail->Port       = 465;
  $mail->setFrom('inbox@talantika.pro', 'ТалантиКА'); // от кого будет уходить письмо?

  // Переменные
  $form_type = $_POST['form_type'];

  // Переменные
  $name = CheckEmpty($_POST['user_name']);
  $email = CheckEmpty($_POST['user_email']);
  $job = CheckEmpty($_POST['user_job']);
  $phone = CheckEmpty($_POST['user_phone']);
  $city = CheckEmpty($_POST['user_city']);
  $qustion = CheckEmpty($_POST['user_question']);

  $status = '';

  $phoneTemplate;
  if ($phone == 'Не указано') $phoneTemplate = "<b>Номер телефона: </b> $phone";
  else $phoneTemplate = "<b>Номер телефона: </b> <a href='mailto: $phone'> $phone </a>";

  if ($form_type == 'hirer') {

    // Формирование содержимого письма
    $title = "Заявка с сайта talantika.pro";
    $body =
      "
    <html>
     <p>
      Контактная информация: <br> <br>
      <b>Имя: </b> $name <br>
      $phoneTemplate <br>
      <b>Адрес электронной почты: </b> <a href='mailto: $email'> $email </a> <br>
      <b>Город: </b> $city <br>
      <b>Искомая должность: </b> $job <br>
     </p>
    </html>
   ";

    // Получатель письма
    $mail->addAddress('main.acr0matic@gmail.com');
  }

  else if ($form_type == 'applicant') {
    // Формирование содержимого письма
    $title = "Заявка с сайта talantika.pro";
    $body =
      "
    <html>
     <p>
      Контактная информация: <br> <br>
      <b>Имя: </b> $name <br>
      $phoneTemplate <br>
      <b>Адрес электронной почты: </b> <a href='mailto: $email'> $email </a> <br>
      <b>Город: </b> $city <br>
      <b>Искомая должность: </b> $job <br>
     </p>
    </html>
   ";

    // Получатель письма
    $mail->addAddress('main.acr0matic@gmail.com');
  }

  else if ($form_type == 'question') {
    // Формирование содержимого письма
    $title = "Заявка с сайта talantika.pro";
    $body =
      "
    <html>
     <p>
      Контактная информация: <br> <br>
      <b>Имя: </b> $name <br>
      $phoneTemplate <br>
      <b>Адрес электронной почты: </b> <a href='mailto: $email'> $email </a> <br>
      <b>Вопрос: </b> $qustion <br>
     </p>
    </html>
   ";

    // Получатель письма
    $mail->addAddress('main.acr0matic@gmail.com');
  }

  // Отправка сообщения
  $mail->Subject = $title;
  $mail->Body = $body;

  SendMail($mail, $status);

  echo json_encode($status);
}
