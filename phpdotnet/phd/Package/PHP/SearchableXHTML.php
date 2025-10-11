<?php

namespace phpdotnet\phd;

class Package_PHP_SearchableXHTML extends Package_PHP_ChunkedXHTML
{
    protected array $js = [];

    public function __construct(Config $config, OutputHandler $outputHandler)
    {
        parent::__construct($config, $outputHandler);
        $this->registerFormatName("PHP-Searchable-XHTML");
    }


    protected function writeJsonIndex()
    {
        parent::writeJsonIndex();

        $entries = $this->processCombinedJsonIndex();
        file_put_contents(
            $this->getOutputDir() . "search-combined.json",
            json_encode($entries)
        );
        $entries = 'var localSearchIndexes = '. json_encode($entries) .';';
        file_put_contents(
            $this->getOutputDir() . "search-combined.js",
            $entries
        );
        $this->outputHandler->v("Combined Index written", VERBOSE_FORMAT_RENDERING);
    }

    private function processCombinedJsonIndex(): array
    {
        $entries = [];
        foreach ($this->indexes as $index) {
            if (!$index["chunk"]) {
                continue;
            }

            if ($index["sdesc"] === "" && $index["ldesc"] !== "") {
                $index["sdesc"] = $index["ldesc"];
                $bookOrSet = $this->findParentBookOrSet($index['parent_id']);
                if ($bookOrSet) {
                    $index["ldesc"] = Format::getLongDescription(
                        $bookOrSet['docbook_id']
                    );
                }
            }

            $nameParts = explode('::', $index['sdesc']);
            $methodName = array_pop($nameParts);

            $type = 'General';
            switch ($index['element']) {
                case "phpdoc:varentry":
                    $type = "Variable";
                    break;

                case "refentry":
                    $type = "Function";
                    break;

                case "phpdoc:exceptionref":
                    $type = "Exception";
                    break;

                case "phpdoc:classref":
                    $type = "Class";
                    break;

                case "set":
                case "book":
                case "reference":
                    $type = "Extension";
                    break;
            }

            $entries[] = [
                'id' => $index['filename'],
                'name' => $index['sdesc'],
                'description' => html_entity_decode($index['ldesc']),
                'tag' => $index['element'],
                'type' => $type,
                'methodName' => $methodName,
            ];
        }
        return $entries;
    }

    /**
     * Finds the closest parent book or set in the index hierarchy.
     */
    private function findParentBookOrSet(string $id): ?array
    {
        // array_key_exists() to guard against undefined array keys, either for
        // root elements (no parent) or in case the index structure is broken.
        while (array_key_exists($id, $this->indexes)) {
            $parent = $this->indexes[$id];
            $element = $parent['element'];

            if ($element === 'book' || $element === 'set') {
                return $parent;
            }

            $id = $parent['parent_id'];
        }

        return null;
    }

    public function header($id)
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

        $searchNav = <<<HTML
            <div class="navbar__item search">
              <!-- Desktop encanced search -->
              <button
                id="navbar__search-button"
                class="navbar__search-button"
                hidden
              >
                $searchIcon
                Search docs
              </button>
            </div>
            HTML;

        $title = Format::getLongDescription($id);
        static $cssLinks = null;
        if ($cssLinks === null) {
            $cssLinks = $this->createCSSLinks();
        }
        $header = <<<HEADER
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <style>
    .navbar__local {
      display: flex;
      flex-grow: 1;
      margin: 0;
      justify-content: space-between;
      align-items: center;

      .navbar__item { flex-grow: 1 }
      .navbar__item.next .navbar__link { justify-content: end; }
    }
  </style>
  <title>$title</title>
{$cssLinks}
 </head>
 <body class="docs">
HEADER;
        $nextLink = $prevLink = $upLink = '';
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
        if ($parentId = Format::getParent($id)) {
            $up = array(
                "href" => $this->getFilename($parentId) . $this->getExt(),
                "desc" => $this->getShortDescription($parentId),
            );
            if ($up['href'] != 'index.html') {
                $upLink = "<li><a href=\"{$up["href"]}\">{$up["desc"]}</a></li>";
            }
        }

        $nav = <<<NAV
<div class="navbar navbar-fixed-top">
  <div class="navbar__inner clearfix">
      <ul class="navbar__local">
        {$prevLink}
        {$searchNav}
        {$nextLink}
      </ul>
  </div>
</div>
<div id="breadcrumbs" class="clearfix">
  <ul class="breadcrumbs-container">
    <li><a href="index.html">PHP Manual</a></li>
    {$upLink}
    <li>{$title}</li>
  </ul>
</div>
<div id="layout">
  <div id="layout-content">
NAV;
        return $header . $nav;
    }

    public function footer($id)
    {
        $oldFooter = parent::footer($id);
        $oldFooter = str_replace('</body></html>', '', $oldFooter);

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
                    <kbd>Esc</kbd> to close
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

        return $oldFooter . $footer . '</body></html>';
    }

    private function mkdir(string $dir): void
    {
        if (file_exists($dir)) {
            if (!is_dir($dir)) {
                trigger_error("The styles/ directory is a file?", E_USER_ERROR);
            }
        } else {
            if (!mkdir($dir, 0777, true)) {
                trigger_error("Can't create the styles/ directory.", E_USER_ERROR);
            }
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
            if (@copy($sourceFile, $outputDir . $dest)) {
                $this->js[] = $jsDir . $dest;
            } else {
                trigger_error(vsprintf('Impossible to copy the %s file.', [$sourceFile]), E_USER_WARNING);
            }
        }
    }
}