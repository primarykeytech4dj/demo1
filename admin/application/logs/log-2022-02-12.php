<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-02-12 05:48:23 --> Query error: Duplicate entry '21-22-1-1-25023-E/O/21-22/0025667' for key 'fiscalyrwise_company_wise_bill_wise_invoice_orders' - Invalid query: INSERT INTO `invoice_orders` (`created`, `created_by`, `fiscal_yr`, `invoice_no`, `is_active`, `modified`, `modified_by`, `order_code`) VALUES ('2022-02-12 05:48:23','1','21-22','25023',1,'2022-02-12 05:48:23','1','E/O/21-22/0025667')
ERROR - 2022-02-12 10:51:16 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-12 11:41:30 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-12 12:17:34 --> Severity: Notice --> Undefined offset: 0 /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 1604
ERROR - 2022-02-12 13:05:16 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-12 13:48:59 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-12 14:59:55 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-12 15:33:25 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-12 16:59:36 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-02-12 17:00:07 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-02-12 17:03:06 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-02-12 17:50:53 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-02-12 17:54:06 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-02-12 17:55:28 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-02-12 17:59:47 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-02-12 17:59:55 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-02-12 18:02:14 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-02-12 18:04:55 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-02-12 18:05:21 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-02-12 18:06:03 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-02-12 18:06:41 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-02-12 18:42:00 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-12 18:42:00 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-12 18:42:00 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-12 18:42:00 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-12 18:42:00 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-12 18:42:00 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-12 18:42:00 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-12 18:42:00 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-12 18:42:01 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/system/core/Exceptions.php:271) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-12 18:42:45 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-12 18:42:45 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-12 18:42:45 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-12 18:42:45 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-12 18:42:45 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-12 18:42:45 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-12 18:42:45 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-12 18:42:45 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-12 18:42:48 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/system/core/Exceptions.php:271) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-12 18:51:27 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-12 18:51:27 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-12 18:51:27 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-12 18:51:27 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-12 18:51:27 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-12 18:51:27 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-12 18:51:27 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-12 18:51:27 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-12 18:51:30 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/system/core/Exceptions.php:271) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-12 19:55:00 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-12 20:12:03 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-12 20:18:52 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-12 20:23:29 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-12 20:30:43 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-12 20:30:47 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-12 20:34:37 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-12 21:02:36 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-12 21:02:36 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-12 21:02:36 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-12 21:23:51 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-12 21:23:51 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-12 21:23:51 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-12 21:23:51 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-12 21:23:51 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-12 21:23:51 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-12 21:23:51 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-12 21:23:51 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-12 21:23:53 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/system/core/Exceptions.php:271) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-12 21:24:31 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-12 21:24:31 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-12 21:24:31 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-12 21:24:31 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-12 21:24:31 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-12 21:24:31 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-12 21:24:31 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-12 21:24:31 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-12 21:24:31 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/system/core/Exceptions.php:271) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-12 21:27:42 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-12 21:27:42 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-12 21:27:42 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-12 21:27:42 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-12 21:27:42 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-12 21:27:42 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-12 21:27:42 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-12 21:27:42 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-12 21:27:43 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/system/core/Exceptions.php:271) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-12 21:28:47 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-12 21:28:47 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-12 21:28:47 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-12 21:28:47 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-12 21:28:47 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-12 21:28:47 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-12 21:28:47 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-12 21:28:47 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-12 21:28:49 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/system/core/Exceptions.php:271) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-12 23:12:48 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-12 23:12:48 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-12 23:12:48 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-12 23:12:48 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753