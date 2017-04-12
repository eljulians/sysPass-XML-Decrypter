SysPass XML decrypter
=====================

XML decrypter for [sysPass](https://github.com/nuxsmin/sysPass/).

## Usage

 - Install Composer dependencies:
 ```
 composer install
 ```

 - Execute the script:
 ```
 ./cli.php <path-to-encrypted-xml> <export-key> <master-key> <dest-file>
 ```

## Required packages

 - php-xml
 - php-mbstring
 - php-mcrypt


Tested with PHP 7.0.15 and exported XML with sysPass 2.1.5.17041201.
