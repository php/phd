<?php
namespace phpdotnet\phd;

class PI_MarkdownPHPDOCHandler extends PI_PHPDOCHandler {

    protected function generateChangelogMarkup($changelogs): string {
        usort($changelogs, [PI_PHPDOCHandler::class, '_sortByVersion']);

        $ret = "| Version | Function | Description |\n| --- | --- | --- |\n";
        $desc = "";
        $lastVersion = "";
        foreach ($changelogs as $entry) {
            if (!$this->_changelogSince || version_compare($entry["version"], $this->_changelogSince) >= 0) {
                $link    = $this->format->createLink($entry["docbook_id"], $desc);
                $version = ($entry["version"] === $lastVersion) ? "" : $entry["version"];
                $description = html_entity_decode($entry["description"], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $description = preg_replace('/\s+/', ' ', trim($description));
                $description = str_replace('|', '\\|', $description);
                $ret    .= sprintf("| %s | [%s](%s) | %s |\n", $version, $desc, $link, $description);
                $lastVersion = $entry["version"];
            }
        }
        $this->_changelogSince = null;
        return $ret;
    }
}
