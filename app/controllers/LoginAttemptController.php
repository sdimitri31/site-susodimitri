<?php

namespace App\Controllers;

use App\Models\LoginAttempt;

class LoginAttemptController
{
    private $loginAttempt;

    public function __construct()
    {
        $this->loginAttempt = new LoginAttempt();
    }

    public function logAttempt($username, $ipAddress, $isSuccess)
    {
        $this->loginAttempt->logAttempt($username, $ipAddress, $isSuccess);
    }

    public function checkAndBlockUser($username, $ipAddress, $maxAttempts = 5, $attemptWindowMinutes = 30)
    {
        return $this->loginAttempt->isUserBlocked($username, $ipAddress, $maxAttempts, $attemptWindowMinutes);
    }

    public function clearLoginAttempts($username)
    {
        $this->loginAttempt->clearLoginAttempts($username);
    }
}

?>
