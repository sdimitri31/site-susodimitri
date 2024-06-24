<?php

namespace App\Helpers;

class AlertMessage
{
    const INFO = 'info';
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const ERROR = 'error';

    /**
     * Get all alert types.
     *
     * @return array
     */
    public static function getAllAlertTypes()
    {
        return [
            self::INFO,
            self::SUCCESS,
            self::WARNING,
            self::ERROR,
        ];
    }

    /**
     * Display all alert messages.
     */
    public static function displayMessages()
    {
        foreach (self::getAllAlertTypes() as $type) {
            self::displayAlert($type);
        }
    }

    /**
     * Display a specific alert message.
     *
     * @param string $type
     */
    public static function displayAlert($type)
    {
        $message = self::getAlert($type);
        if (empty($message)) {
            return;
        }

        try {
            View::render('alertMessage/' . $type . '.php', ['message' => $message]);
        } catch (\Exception $e) {
            // Handle the error if the view is missing
            error_log("Missing view for alert type: " . $type);
        }

        self::setAlert($type, null);
    }

    /**
     * Set an alert message.
     *
     * @param string $type
     * @param string $message
     */
    public static function setAlert($type, $message)
    {
        if (!self::isTypeValid($type)) {
            error_log("Invalid alert type: " . $type);
            return; 
        }

        Session::set($type, $message);
    }

    /**
     * Get an alert message.
     *
     * @param string $type
     * @return string|null
     */
    public static function getAlert($type)
    {
        if (!self::isTypeValid($type)) {
            error_log("Invalid alert type: " . $type);
            return null;
        }

        return Session::get($type);
    }

    /**
     * Check if the alert type is valid.
     *
     * @param string $type
     * @return bool
     */
    public static function isTypeValid($type){
        return in_array($type, self::getAllAlertTypes());
    }
}
