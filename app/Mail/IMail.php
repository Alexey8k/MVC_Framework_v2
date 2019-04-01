<?php

namespace App\Mail;

/**
 * Interface IMail
 * @package App\Mail
 */
interface IMail
{
    /**
     * @param string $to
     * @param string $subject
     * @param string $message
     * @param string|null $headers
     * @param string|null $parameters
     * @return bool
     */
    function send(string $to, string $subject, string $message, string $headers = null, string $parameters = null) : bool;
}