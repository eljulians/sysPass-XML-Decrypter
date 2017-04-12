SysPass XML decrypter
=====================

XML decrypter for [sysPass](https://github.com/nuxsmin/sysPass/).

## Supported export formats

 - LastPass.

## Usage

 - Clone the repository:

 ```
 git clone https://github.com/julenpardo/sysPass-XML-Decrypter
 ```


 - Install Composer dependencies:

 ```
 cd sysPass-XML-Decrypter
 composer install
 ```

 - Execute the script:
 ```
 ./decrypt.php <path-to-encrypted-xml> <export-key> <master-key> <dest-file> [format]
 ```

## Requirements

 - PHP >= 7.0
 - php-xml
 - php-mbstring
 - php-mcrypt


Tested with PHP 7.0.15 and XML exported with sysPass 2.1.5.17041201.
