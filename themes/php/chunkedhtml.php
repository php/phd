<?php
class chunkedhtml extends phpweb {
    public function __construct(array $IDs, array $IDMap, $filename, $ext = "html") {
        parent::__construct($IDs, $IDMap, $filename, $ext, true);
    }
    public function header($id) {
        return "<html><body>\n";
    }
    public function footer($id) {
        return "</body></html>\n";
    }

}

