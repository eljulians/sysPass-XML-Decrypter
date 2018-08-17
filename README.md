SysPass XML decrypter
=====================

XML decrypter for [sysPass](https://github.com/nuxsmin/sysPass/).

## Supported export formats

 - LastPass
 - Comma-delimited CSV (default) - suitable for CSV generic import in KeePass

## Requirements

 - PHP >= 7.0 
 - php-xml
 - php-mbstring
 - php-mcrypt
 - composer
 - git
 
## Installation

 - Clone the repository:

 ```
 git clone https://github.com/julenpardo/sysPass-XML-Decrypter
 ```

 - Install Composer dependencies:

 ```
 cd sysPass-XML-Decrypter
 composer install
 ```

## Usage

 - Execute the script:
 ```
 ./decrypt.php <path-to-encrypted-xml> <xml-export-password> <syspass-master-password> <dest-file> [format]
 ```
 
 * format: lastpass - if ommitted, the default is to export in CSV comma-delimited format

Tested with:

* PHP 7.0.15 and sysPass 2.1.5.17041201
* PHP 7.2.7 and sysPass  2.1 (2.1.15.17101701)

