<?php
    namespace util;

    /**
     * Created by PhpStorm.
     * User: bisim
     * Date: 08/09/2018
     * Time: 15:42
     */

    class ErrorHandler
    {
        /**
         * @param $severity
         * @param $message
         * @param $file
         * @param $line
         *
         * @return bool
         * @throws \ErrorException
         */
        function errorHandler($severity, $message, $file, $line)
        {
            // Determine if this error is one of the enabled ones in php config (php.ini, .htaccess, etc)
            $error_is_enabled = (bool) ($severity & error_reporting());

            // -- FATAL ERROR
            // throw an Error Exception, to be handled by whatever Exception handling logic is available in this context
            if (in_array($severity, array(E_USER_ERROR, E_RECOVERABLE_ERROR)) && $error_is_enabled) {
                throw new \ErrorException($message, 0, $severity, $file, $line);
            }

            // -- NON-FATAL ERROR/WARNING/NOTICE
            // Log the error if it's enabled, otherwise just ignore it
            else if ($error_is_enabled) {
                error_log( $message, 0 );
                return false; // Make sure this ends up in $php_errormsg, if appropriate
            }

            return true;
        }
    }
