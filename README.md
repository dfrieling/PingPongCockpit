requirements
* php-imagick
* php-ssh2
* /etc/php/7.0/cgi/php.ini: php_value upload_max_filesize 10M und php_value post_max_size 20M
* nginx.conf in server or location context: client_max_body_size 10M; #allow file upload up to that limit
* need config/ssh_config.php 