<?php
namespace phpdotnet\phd;

class ErrorHandler
{
    private const ERROR_MAP = [
        // PHP Triggered Errors
        E_DEPRECATED                  => 'E_DEPRECATED          ',
        E_RECOVERABLE_ERROR           => 'E_RECOVERABLE_ERROR   ',
        E_STRICT                      => 'E_STRICT              ',
        E_WARNING                     => 'E_WARNING             ',
        E_NOTICE                      => 'E_NOTICE              ',

        // User Triggered Errors
        E_USER_ERROR                  => 'E_USER_ERROR          ',
        E_USER_WARNING                => 'E_USER_WARNING        ',
        E_USER_NOTICE                 => 'E_USER_NOTICE         ',
        E_USER_DEPRECATED             => 'E_USER_DEPRECATED     ',
    ];
    
    public function __construct(
        private OutputHandler $outputHandler
    ) {}

    public function errh($errno, $msg, $file, $line) {
        static $recursive = false;

        // Respect the error_reporting setting
        if (!(error_reporting() & $errno)) {
            return false;
        }

        // Recursive protection
        if ($recursive) {
            // Thats bad.. lets print a backtrace right away
            debug_print_backtrace();
            // Fallback to the default errorhandler
            return false;
        }
        $recursive = true;

        switch($errno) {
            // User triggered errors
            case E_USER_ERROR:
            case E_USER_WARNING:
            case E_USER_NOTICE:
                $this->outputHandler->printUserError($msg, $file, $line, self::ERROR_MAP[$errno]);
                break;

            // PHP triggered errors
            case E_DEPRECATED:
            case E_RECOVERABLE_ERROR:
            case E_STRICT:
            case E_WARNING:
            case E_NOTICE:
                $this->outputHandler->printPhpError($msg, $file, $line, self::ERROR_MAP[$errno]);
                break;

            default:
                $recursive = false;
                return false;
        }

        // Abort on fatal errors
        if ($errno & (E_USER_ERROR|E_RECOVERABLE_ERROR)) {
            exit(1);
        }

        $recursive = false;
        return true;
    }
}
