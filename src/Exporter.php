<?php

namespace Decrypter;

class Exporter
{
    public static function export($accounts, $format, $destFile)
    {
        $format = strtolower($format);

        switch ($format) {
            case 'lastpass':
            default:
                self::exportLastpass($accounts, $destFile);
                break;
        }
    }

    protected static function exportLastpass($accounts, $destFile)
    {
        $csv = fopen($destFile, 'w');
        $header = [
            'url', 'type', 'username', 'password', 'hostname',
            'extra', 'name', 'grouping'
        ];

        fputcsv($csv, $header);

        foreach ($accounts as $account) {
            $line = [
                $account['url'],
                '', // Type.
                $account['login'],
                $account['password'],
                '', // Hostname.
                'Customer: ' . $account['customer'],
                $account['name'],
                $account['category']
            ];

            fputcsv($csv, $line);
        }
    }

}
