SysPass XML decrypter
=====================

XML decrypter for [sysPass](https://github.com/nuxsmin/sysPass/).

## Supported export formats

 - LastPass.

## Usage

 - Install Composer dependencies:
 ```
 composer install
 ```

 - Execute the script:
 ```
 ./cli.php <path-to-encrypted-xml> <export-key> <master-key> <dest-file> [format]
 ```

## Requirements

 - PHP >= 7.0
 - php-xml
 - php-mbstring
 - php-mcrypt


Tested with PHP 7.0.15 and XML exported with sysPass 2.1.5.17041201.
