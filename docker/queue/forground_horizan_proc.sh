# [program:horizon]
# command=/usr/bin/php /var/www/html/artisan horizon
# autostart=true
# autorestart=true
# user=sail
# redirect_stderr=true
# stdout_logfile=/var/www/html/storage/logs/horizon.log
# stopwaitsecs=360

#!/bin/bash
while :; do
   php /var/www/html/artisan horizon
   sleep 2
done