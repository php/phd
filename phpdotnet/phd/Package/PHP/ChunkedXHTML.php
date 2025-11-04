<?php
namespace phpdotnet\phd;

class Package_PHP_ChunkedXHTML extends Package_PHP_Web {
    protected array $js = [];

    public function __construct(
      Config $config,
      OutputHandler $outputHandler
    ) {
        parent::__construct($config, $outputHandler);
        $this->registerFormatName("PHP-Chunked-XHTML");
        $this->setExt($this->config->ext === null ? ".html" : $this->config->ext);
    }

    public function __destruct() {
        parent::__destruct();
    }

    protected function headerNav($id): string
    {
        // https://feathericons.com search
        $searchIcon = <<<SVG
            <svg
                xmlns="http://www.w3.org/2000/svg"
                aria-hidden="true"
                width="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        SVG;

        $nextLink = $prevLink = '<li class="navbar__item"></li>';
        if ($prevId = Format::getPrevious($id)) {
            $prev = array(
                "href" => $this->getFilename($prevId) . $this->getExt(),
                "desc" => $this->getShortDescription($prevId),
            );
            $prevLink = "<li class=\"navbar__item prev\"><a href=\"{$prev["href"]}\" class=\"navbar__link\">« {$prev["desc"]}</a></li>";
        }
        if ($nextId = Format::getNext($id)) {
            $next = array(
                "href" => $this->getFilename($nextId) . $this->getExt(),
                "desc" => $this->getShortDescription($nextId),
            );
            $nextLink = "<li class=\"navbar__item next\"><a href=\"{$next["href"]}\" class=\"navbar__link\">{$next["desc"]} »</a></li>";
        }

        return <<<HTML
            <div class="navbar navbar-fixed-top">
              <div class="navbar__inner clearfix">
                  <ul class="navbar__local">
                    {$prevLink}
                    <li class="navbar__item search">
                      <!-- Desktop encanced search -->
                      <button
                        id="navbar__search-button"
                        class="navbar__search-button"
                        hidden
                      >
                        $searchIcon
                        Search
                      </button>
                    </li>
                    {$nextLink}
                  </ul>
              </div>
            </div>
        HTML;
    }

    protected function headerCrumbs($id): string
    {
        $title = Format::getLongDescription($id);
        $upLink = '';
        if ($parentId = Format::getParent($id)) {
            $up = array(
                "href" => $this->getFilename($parentId) . $this->getExt(),
                "desc" => $this->getShortDescription($parentId),
            );
            if ($up['href'] != 'index.html') {
                $upLink = "<li><a href=\"{$up["href"]}\">{$up["desc"]}</a></li>";
            }
        }

        return <<<HTML
            <div id="breadcrumbs" class="clearfix">
              <ul class="breadcrumbs-container">
                <li><a href="index.html">PHP Manual</a></li>
                {$upLink}
                <li>{$title}</li>
              </ul>
            </div>
        HTML;
    }

    public function header($id) {
        $title = Format::getLongDescription($id);
        static $cssLinks = null;
        if ($cssLinks === null) {
            $cssLinks = $this->createCSSLinks();
        }
        $header = <<<HTML
            <!DOCTYPE HTML>
            <html>
             <head>
              <meta http-equiv="content-type" content="text/html; charset=UTF-8">
              <style>
                .navbar__local {
                  flex-grow: 1;
                  display: grid;
                  grid-auto-flow: column;
                  grid-auto-columns: 1fr min-content 1fr;
                  margin: 0;
                  height: 100%;
                  
                  .navbar__item {
                    align-content: center;
                  }
                  .navbar__link {
                    display: inline;
                  }
                  .search {
                    text-align: center;
                  }
                  .next {
                    text-align: right;
                  }
                }
              </style>
              <title>$title</title>
              {$cssLinks}
             </head>
             <body class="docs">
        HTML;

        $contentOpen = <<<HTML
            <div id="layout">
              <div id="layout-content">
        HTML;

        return $header . $this->headerNav($id) . $this->headerCrumbs($id) . $contentOpen;
    }

    protected function footerSearch(): string
    {
        $this->fetchJS();

        $footer = '';
        $footer .= '<script type="text/javascript" src="search-combined.js"></script>';
        foreach ($this->js as $jsFile) {
            $footer .= '<script type="text/javascript" src="' . $jsFile . '"></script>';
        }

        $footer .= <<<HTML
            <div id="search-modal__backdrop" class="search-modal__backdrop">
              <div
                role="dialog"
                aria-label="Search modal"
                id="search-modal"
                class="search-modal"
              >
                <div class="search-modal__header">
                  <div class="search-modal__form">
                    <div class="search-modal__input-icon">
                      <!-- https://feathericons.com search -->
                      <svg xmlns="http://www.w3.org/2000/svg"
                        aria-hidden="true"
                        width="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      >
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                      </svg>
                    </div>
                    <input
                      type="search"
                      id="search-modal__input"
                      class="search-modal__input"
                      placeholder="Search docs"
                      aria-label="Search docs"
                    />
                  </div>
            
                  <button aria-label="Close" class="search-modal__close">
                    <!-- https://pictogrammers.com/library/mdi/icon/close/ -->
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      aria-hidden="true"
                      width="24"
                      viewBox="0 0 24 24"
                    >
                      <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/>
                    </svg>
                  </button>
                </div>
                <div
                  role="listbox"
                  aria-label="Search results"
                  id="search-modal__results"
                  class="search-modal__results"
                ></div>
                <div class="search-modal__helper-text">
                  <div>
                    <kbd>↑</kbd> and <kbd>↓</kbd> to navigate •
                    <kbd>Enter</kbd> to select •
                    <kbd>Esc</kbd> to close • <kbd>/</kbd> to open
                  </div>
                </div>
              </div>
            </div>
            <script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function() {
                    /*{{{Search Modal*/
                    const language = 'local';
                    initSearchModal();
                    initPHPSearch(language).then((searchCallback) => {
                        initSearchUI({language, searchCallback, limit: 30});
                    });
                    /*}}}*/
                });
            </script>
        HTML;

        return $footer;
    }

    private function mkdir(string $dir): void
    {
        if (file_exists($dir)) {
            if (!is_dir($dir)) {
                trigger_error("The specified directory is a file: {$dir}", E_USER_ERROR);
            }
            return;
        }
        if (!mkdir($dir, 0777, true)) {
            trigger_error("Can't create the specified directory: {$dir}", E_USER_ERROR);
        }
    }

    private function fetchJS(): void
    {
        if ($this->js !== []) {
            return;
        }
        $outputDir = $this->getOutputDir();
        if (!$outputDir) {
            $outputDir = $this->config->outputDir;
        }
        $jsDir = 'js/';
        $outputDir .= '/' . $jsDir;
        $this->mkdir($outputDir);

        $files = [
            'https://www.php.net/js/ext/FuzzySearch.min.js',
            __DIR__ . '/search.js',
        ];
        foreach ($files as $sourceFile) {
            $basename = basename($sourceFile);
            $dest = md5(substr($sourceFile, 0, -strlen($basename))) . '-' . $basename;
            if (! @copy($sourceFile, $outputDir . $dest)) {
                trigger_error(vsprintf('Impossible to copy the %s file.', [$sourceFile]), E_USER_WARNING);
                continue;
            }
            $this->js[] = $jsDir . $dest;
        }
    }

    public function footer($id)
    {
        return '</div></div>' . $this->footerSearch() . '</body></html>';
    }
}
