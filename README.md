INSTALLATION
------------

git clone https://github.com/pr0100t0wa/surge.git
cd surge
php yii migrate
composer install


TESTS
------------
tests/bin/yii migrate
vendor/bin/codecept run unit Models:TenderTest


LOAD DATA TO MAIN DB
--------------------
php yii tender