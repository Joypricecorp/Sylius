```
# sylius:remove-expired-carts
* * * * * /usr/bin/php /var/www/current/bin/console sylius:remove-expired-carts --env=prod --no-debug > /var/www/shared/var/logs/sylius.remove-expired-carts.log 2> /var/www/shared/app/logs/sylius.remove-expired-carts.error

# sylius:cancel-unpaid-orders
* * * * * /usr/bin/php /var/www/current/bin/console sylius:cancel-unpaid-orders --env=prod --no-debug > /var/www/shared/var/logs/sylius.cancel-unpaid-orders.log 2> /var/www/shared/app/logs/sylius.cancel-unpaid-orders.error


# dos:paysbuy:currency:update
# * * 1 * * /usr/bin/php /var/www/current/bin/console dos:paysbuy:currency:update --env=prod --no-debug > /var/www/shared/var/logs/dos.paysbuy-currency-update.log 2> /var/www/shared/app/logs/dos.paysbuy-currency-update.error


```
