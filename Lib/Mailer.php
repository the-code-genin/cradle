<?php

namespace Lib;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    /**
     * Send an email.
     *
     * @param string $toEmail
     * @param string $mailSubject
     * @param string $mailBody
     * @return boolean
     */
    public static function sendMail(string $toEmail, string $mailSubject, string $mailBody): bool
    {
        $mail = new PHPMailer(true);

        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host       = getenv("SMTP_HOST");
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv("SMTP_USERNAME");
        $mail->Password   = getenv("SMTP_PASSWORD");
        $mail->SMTPSecure = getenv("SMTP_ENCRYPTION") == "ssl" ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = (int) getenv("SMTP_PORT");

        //Recipients
        $mail->setFrom(getenv("SMTP_FROM_EMAIL"), getenv("SMTP_FROM_NAME"));
        $mail->addAddress($toEmail);

        //Content
        $mail->isHTML(true);
        $mail->Subject = $mailSubject;
        $mail->Body    = $mailBody;

        return $mail->send();
    }
}
