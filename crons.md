```
# sylius:remove-expired-carts
* * * * * /usr/bin/php /var/www/current/app/console sylius:remove-expired-carts --env=prod --no-debug > /var/www/shared/app/logs/sylius.remove-expired-carts.log 2> /var/www/shared/app/logs/sylius.remove-expired-carts.error

# sylius:cancel-unpaid-orders
* * * * * /usr/bin/php /var/www/current/app/console sylius:cancel-unpaid-orders --env=prod --no-debug > /var/www/shared/app/logs/sylius.cancel-unpaid-orders.log 2> /var/www/shared/app/logs/sylius.cancel-unpaid-orders.error


# dos:paysbuy:currency:update
* * 1 * * /usr/bin/php /var/www/current/app/console dos:paysbuy:currency:update --env=prod --no-debug > /var/www/shared/app/logs/dos.paysbuy-currency-update.log 2> /var/www/shared/app/logs/dos.paysbuy-currency-update.error


```
