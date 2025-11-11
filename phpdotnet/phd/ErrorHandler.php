<?php
namespace phpdotnet\phd;

class ErrorHandler
{
    private const ERROR_MAP = [
        // PHP Triggered Errors
        E_DEPRECATED                  => 'E_DEPRECATED          ',
        E_RECOVERABLE_ERROR           => 'E_RECOVERABLE_ERROR   ',
        E_WARNING                     => 'E_WARNING             ',
        E_NOTICE                      => 'E_NOTICE              ',

        // User Triggered Errors
        E_USER_ERROR                  => 'E_USER_ERROR          ',
        E_USER_WARNING                => 'E_USER_WARNING        ',
        E_USER_NOTICE                 => 'E_USER_NOTICE         ',
        E_USER_DEPRECATED             => 'E_USER_DEPRECATED     ',
    ];
    
    private bool $recursive = false;
    
    public function __construct(
        private readonly OutputHandler $outputHandler
    ) {}

    public function handleError($errno, $msg, $file, $line) {
        // Respect the error_reporting setting
        if (!(error_reporting() & $errno)) {
            return false;
        }

        // Recursive protection
        if ($this->recursive) {
            // Thats bad.. lets print a backtrace right away
            debug_print_backtrace();
            // Fallback to the default errorhandler
            return false;
        }
        $this->recursive = true;

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
            case E_WARNING:
            case E_NOTICE:
                $this->outputHandler->printPhpError($msg, $file, $line, self::ERROR_MAP[$errno]);
                break;

            default:
                $this->recursive = false;
                return false;
        }

        // Abort on fatal errors
        if ($errno & (E_USER_ERROR|E_RECOVERABLE_ERROR)) {
            exit(1);
        }

        $this->recursive = false;
        return true;
    }
}
