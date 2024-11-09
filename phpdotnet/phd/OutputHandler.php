<?php
namespace phpdotnet\phd;

class OutputHandler
{
    public function __construct(
        private Config $config
    ) {}

    /**
     * Method to get a color escape sequence
     */
    private function term_color(string $text, string|false $color): string {
        return $this->config->color_output() && $color !== false ? "\033[" . $color . "m" . $text . "\033[m" : $text;
    }

    public function printPhdInfo(string $msg, ?string $info = ""): int {
        $color = $this->config->phd_info_color();
        $outputStream = $this->config->phd_info_output();

        return $this->print($msg, $outputStream, $color, $info);
    }

    private function printPhdWarning(string $msg, ?string $warning = ""): int {
        $color = $this->config->phd_warning_color();
        $outputStream = $this->config->phd_warning_output();

        return $this->print($msg, $outputStream, $color, $warning);
    }

    public function printUserError(string $msg, string $file, int $line, ?string $error = ""): int {
        $color = $this->config->user_error_color();
        $outputStream = $this->config->user_error_output();
        $data = \sprintf("%s:%d\n\t%s", $file, $line, $msg);

        return $this->print($data, $outputStream, $color, $error);
    }

    public function printPhpError(string $msg, string $file, int $line, ?string $error = ""): int {
        $color = $this->config->php_error_color();
        $outputStream = $this->config->php_error_output();
        $data = \sprintf("%s:%d\n\t%s", $file, $line, $msg);

        return $this->print($data, $outputStream, $color, $error);
    }

    private function print(string $msg, $outputStream, string|false $color = false, ?string $infoOrErrorString = ""): int {
        if ($infoOrErrorString === "") {
            $colorMsg = $this->term_color(\sprintf("%s", $msg), $color);

            return \fprintf($outputStream, "%s\n", $colorMsg);
        }
        
        $time = \date($this->config->date_format());
        $timestamp = $this->term_color(\sprintf("[%s - %s]", $time, $infoOrErrorString), $color);

        return \fprintf($outputStream, "%s %s\n", $timestamp, $msg);
    }
    
    /**
     * Print info messages: v("printf-format-text" [, $arg1, ...], $verbose-level)
     */
    public function v(...$args): bool {
        $errno = \array_pop($args);
        $msg = \vsprintf(\array_shift($args), $args);

        static $err = [
            // PhD informationals
            VERBOSE_INDEXING              => 'Indexing              ',
            VERBOSE_FORMAT_RENDERING      => 'Rendering Format      ',
            VERBOSE_THEME_RENDERING       => 'Rendering Theme       ',
            VERBOSE_RENDER_STYLE          => 'Rendering Style       ',
            VERBOSE_PARTIAL_READING       => 'Partial Reading       ',
            VERBOSE_PARTIAL_CHILD_READING => 'Partial Child Reading ',
            VERBOSE_TOC_WRITING           => 'Writing TOC           ',
            VERBOSE_CHUNK_WRITING         => 'Writing Chunk         ',
            VERBOSE_MESSAGES              => 'Heads up              ',

            // PhD warnings
            VERBOSE_NOVERSION             => 'No version information',
            VERBOSE_BROKEN_LINKS          => 'Broken links          ',
            VERBOSE_OLD_LIBXML            => 'Old libxml2           ',
            VERBOSE_MISSING_ATTRIBUTES    => 'Missing attributes    ',
        ];

        // Respect the error_reporting setting
        if (!(\error_reporting() & $errno)) {
            return false;
        }

        switch($errno) {
            case VERBOSE_INDEXING:
            case VERBOSE_FORMAT_RENDERING:
            case VERBOSE_THEME_RENDERING:
            case VERBOSE_RENDER_STYLE:
            case VERBOSE_PARTIAL_READING:
            case VERBOSE_PARTIAL_CHILD_READING:
            case VERBOSE_TOC_WRITING:
            case VERBOSE_CHUNK_WRITING:
            case VERBOSE_MESSAGES:
                $this->printPhdInfo($msg, $err[$errno]);
                break;

            case VERBOSE_NOVERSION:
            case VERBOSE_BROKEN_LINKS:
            case VERBOSE_OLD_LIBXML:
            case VERBOSE_MISSING_ATTRIBUTES:
                $this->printPhdWarning($msg, $err[$errno]);
                break;
                
            default:
                return false;
        }
        return true;
    }
}
