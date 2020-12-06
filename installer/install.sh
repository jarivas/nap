
#!/bin/bash
php install.php
cd ..
sudo chown -R jose:www-data config
sudo chown -R jose:www-data data
sudo chown -R jose:www-data log