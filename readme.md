# DBS Projekt WS19

## Setup

### OCI8 Install Guide for Debian based Linux Ditros

1. Download Oracle Instant Client Basic and SDK zips:  
https://www.oracle.com/database/technologies/instant-client/linux-x86-64-downloads.html
2. Unzip both to /opt/oracle
3. Install pecl and other dependencies: `# apt install php-pear php7.3 php7.3-dev build-essential`
4. Install oci8: `# pecl install oci8` (when asked input: `instantclient,/opt/oracle`)
5. Add `extension=oci8` to php.ini

### Set DB credentials in credentials.php

### Run `$ php -S localhost:8080`


