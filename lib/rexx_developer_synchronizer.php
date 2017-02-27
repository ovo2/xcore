<?php

class rexx_developer_synchronizer extends rex_developer_synchronizer_default {
    public function __construct($dirname, $table, array $files, array $metadata = array()) {
        parent::__construct($dirname, $table, $files, $metadata);

        $this->baseDir = rex_path::addon('project', 'developer/' . $dirname . '/');
    }
}
