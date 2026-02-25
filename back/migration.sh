#!/bin/sh

sleep 5


until php /var/www/html/seed.php; do
  sleep 5
done

apache2-foreground