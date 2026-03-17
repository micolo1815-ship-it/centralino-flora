<?php

namespace App\Helpers;

class GmailSentHelper
{
    public static function appendToSent($rawMessage)
    {
        $username = env('null');
        $password = env('null');
        $imapPath = '{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail'; // adjust if needed

        $imapStream = @imap_open($imapPath, $username, $password);

        $imap = @imap_open($imapPath, $username, $password);
        if (!$imap) {
            die('IMAP failed: ' . imap_last_error());
        } else {
            echo 'IMAP connected!';
            imap_close($imap);
        }

        if (!$imapStream) {
            throw new \Exception('IMAP connection failed: ' . imap_last_error());
        }

        $result = imap_append($imapStream, $imapPath, $rawMessage);

        if (!$result) {
            throw new \Exception('IMAP append failed: ' . imap_last_error());
        }

        imap_close($imapStream);
    }
}
