#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

define('DEST_FILE', 'decrypted_accounts.txt');

use \Decrypter\Decrypter;
use \Decrypter\Exporter;


function main()
{
    exitIfNotCLI();
    $args = getArguments();

    $decrypter = new Decrypter(
        $args['xmlFile'],
        $args['exportKey'],
        $args['masterKey']
    );

    echo "Starting decryption...\n";

    $decryptedAccounts = $decrypter->decrypt();

    Exporter::export($decryptedAccounts, 'lastpass', $args['destFile']);

    echo "=============================\n\n";
    echo "Decryption ended with " . count($decryptedAccounts) . " accounts.\n";
}

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

    if ($argc < 5) {
        echo "Execution: ./cli.php <path-to-xml> <export-key> <master-key> <dest-file>\n";
        exit(2);
    }

    $xmlFile = $argv[1];
    $exportKey = $argv[2];
    $masterKey = $argv[3];
    $destFile = $argv[4];
    $exportFormat = $argv[5] ?? '<default>';

    if (!file_exists($xmlFile)) {
        echo "The file '$xmlFile' does not exist or couldn't be read.\n";
        exit(3);
    }

    return [
        'xmlFile' => $xmlFile,
        'exportKey' => $exportKey,
        'masterKey' => $masterKey,
        'destFile' => $destFile
    ];
}

try {
    main();
} catch (Exception $exception) {
    echo "\nSome error happened...\n\n";
    print_r($exception);
    exit(4);
}
