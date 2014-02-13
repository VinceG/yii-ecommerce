Yii eCommerce
===========
- Status: Work in progress. 

Introduction
============

This is a WIP eCommerce platform built using Yii. For more info please visit:
http://vadimg.com/category/yii-e-commerce/

Only the admin side has been worked on so far, so there is no front end side for the actual application.

Requirements
============

- Apache 2 Web Server 
- MySQL 5.1+ with InnoDB support.
- PHP 5.3+ configured with the following extensions:
  - PDO
  - PDO MySQL driver
  - GD2
  - Mcrypt
  - CUrl
  - Imap

Installation
============

1. Clone the repository into your document root
2. Create a virutal host that will point to frontend/www (see example below)
3. Chmod 0777 frontend/runtime and frontend/www/assets
3. Create file params-local.php under common/config based off the file common/config/params-config.sample.php
4. Run the php console/yiic.php migrate 
5. Add a new user using php console/yiic.php user create
6. Visit localhost.com/yiicommerce/admin and login using admin@admin.com/1q2w3e


Contributing
============

Any contribtion is welcome. please fork the repository make any changes and submit a pull request.

Installation
============

#### Virtual Host Example
```
<VirtualHost *:80>
    <Directory "var/www/yii-ecommerce/frontend/www">
	    Options Indexes FollowSymLinks
	    AllowOverride All
	    Order deny,allow
	    Allow from all
	    Satisfy all
    </Directory>

    ServerAdmin youremail@admin.com
    DocumentRoot "var/www/yii-ecommerce/frontend/www"
    ServerName yiicommercedev.com
    ServerAlias yiicommercedev.com
    ErrorLog /var/www/logs/yiicommercedev-error.log
    CustomLog /var/www/logs/yiicommercedev-access.log common
</VirtualHost>
```

Authors
=======
Vincent Gabriel

License
=======

The MIT License

Copyright (c) 2012 Vincent Gabriel

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
of the Software, and to permit persons to whom the Software is furnished to do
so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.