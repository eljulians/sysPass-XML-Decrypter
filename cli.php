#!/usr/bin/env php
<?php

require('vendor/autoload.php');

define('DEST_FILE', 'decrypted.xml');

use \Decrypter\Decrypter;

function exitIfNotCLI()
{
    $sapiName = strtoupper(php_sapi_name());

    if ($sapiName !== 'CLI') {
        echo "This script must be called from the command line.\n";
        exit(1);
    }
}

function getArguments()
{
    global $argc, $argv;

    if ($argc < 3) {
        echo "Execution: ./cli.php <path-to-xml> <export-key>\n";
        exit(2);
    }

    $xmlFile = $argv[1];
    $exportKey = $argv[2];

    if (!file_exists($xmlFile)) {
        echo "The file '$xmlFile' does not exist or couldn't be read.\n";
        exit(3);
    }

    return [
        'xmlFile' => $xmlFile,
        'exportKey' => $exportKey
    ];
}

exitIfNotCLI();
$args = getArguments();

$decryptedNodes = Decrypter::getDecryptedNodes($args['xmlFile'], $args['exportKey']);


foreach ($decryptedNodes as $decryptedNode) {
    file_put_contents(DEST_FILE, $decryptedNode, FILE_APPEND);
}
