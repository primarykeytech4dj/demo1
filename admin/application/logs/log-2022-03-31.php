<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-03-31 05:48:12 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-03-31 10:11:54 --> Severity: Notice --> Undefined index: bill_from_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-03-31 10:11:54 --> Severity: Notice --> Undefined index: bill_to_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-03-31 10:11:54 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 110
ERROR - 2022-03-31 10:11:54 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 111
ERROR - 2022-03-31 10:15:12 --> Severity: Notice --> Undefined index: bill_from_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-03-31 10:15:12 --> Severity: Notice --> Undefined index: bill_to_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-03-31 10:15:12 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 110
ERROR - 2022-03-31 10:15:12 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 111
ERROR - 2022-03-31 10:15:58 --> Severity: Notice --> Undefined index: bill_from_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-03-31 10:15:58 --> Severity: Notice --> Undefined index: bill_to_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-03-31 10:15:58 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 110
ERROR - 2022-03-31 10:15:58 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 111
ERROR - 2022-03-31 10:16:36 --> Severity: Notice --> Undefined index: bill_from_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-03-31 10:16:36 --> Severity: Notice --> Undefined index: bill_to_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-03-31 10:16:36 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 110
ERROR - 2022-03-31 10:16:36 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 111
ERROR - 2022-03-31 10:16:52 --> Severity: Notice --> Undefined index: invoice_address_id /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/admin_edit.php 196
ERROR - 2022-03-31 10:37:28 --> Query error: Duplicate entry '222' for key 'product_id' - Invalid query: INSERT INTO `product_details` (`in_stock_qty`, `product_id`) VALUES ('','222')
ERROR - 2022-03-31 10:37:42 --> Query error: Duplicate entry '222' for key 'product_id' - Invalid query: INSERT INTO `product_details` (`id`, `product_id`, `in_stock_qty`, `modified`, `created`) VALUES ('', '222', '200', '2022-03-31 10:37:42', '2022-03-31 10:37:42')
ERROR - 2022-03-31 10:37:42 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home/patanja1/public_html/emarkit/admin/application/libraries/Pktdblib.php 114
ERROR - 2022-03-31 10:40:49 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-03-31 10:40:59 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-03-31 10:42:27 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-03-31 11:12:33 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-03-31 11:24:03 --> Severity: Notice --> Undefined offset: 0 /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 1608
ERROR - 2022-03-31 11:44:06 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-03-31 13:57:03 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-03-31 13:57:20 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-03-31 14:10:09 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-03-31 14:10:15 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-03-31 14:45:28 --> Severity: Notice --> Undefined offset: 0 /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 1608
ERROR - 2022-03-31 16:04:15 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-03-31 16:14:49 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-03-31 16:17:05 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-03-31 16:17:26 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-03-31 18:31:00 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
