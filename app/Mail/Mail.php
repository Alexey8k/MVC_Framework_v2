<?php

namespace App\Mail;

/**
 * Class Mail
 * @package App\Mail
 */
class Mail implements IMail
{
    /**
     * @param string $to
     * @param string $subject
     * @param string $message
     * @param string|null $headers
     * @param string|null $parameters
     * @return bool
     */
    public function send(string $to, string $subject, string $message, string $headers = null, string $parameters = null) : bool {
        return mail($to, $subject, $message, $headers ?? '', $parameters);
    }

}