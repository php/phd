<?php
namespace phpdotnet\phd;

class TestIndex extends Index {
    public function getNfo(): array {
        return $this->nfo;
    }

    public function getChangelog(): array {
        return $this->changelog;
    }
}
