
#!/bin/bash
php install.php
cd ..
sudo chown -R $USER:www-data config
sudo chown -R $USER:www-data data
sudo chown -R $USER:www-data log
sudo chmod -R 775 .