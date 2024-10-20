<?php
namespace phpdotnet\phd;

class TestPHPBigXHTML extends Package_PHP_BigXHTML {
    public function update($event, $value = null) {
        switch($event) {
        case Render::CHUNK:
            parent::update($event, $value);
            break;
        case Render::STANDALONE:
            parent::update($event, $value);
            break;
        case Render::INIT:
            echo "Filename: " . $this->createFileName() . "\n";
            echo "Content:\n" . $this->header();
            break;
        //No verbose
        }
    }

    public function close() {
        echo $this->footer(true);
    }

    public function appendData($data) {
        if ($this->appendToBuffer) {
            $this->buffer .= $data;
            return;
        }
        if ($this->flags & Render::CLOSE) {
            echo $data;
            echo $this->footer();
            $this->flags ^= Render::CLOSE;
        } elseif ($this->flags & Render::OPEN) {
            echo $data."<hr />";
            $this->flags ^= Render::OPEN;
        } else {
            echo $data;
        }
    }

}
