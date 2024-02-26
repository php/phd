<?php
namespace phpdotnet\phd;

class TestIndex extends Index {
    public function getIndexes(): array {
        return $this->indexes;
    }
}
