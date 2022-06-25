<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-02-17 05:51:37 --> Severity: Notice --> Undefined index: bill_from_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-02-17 05:51:37 --> Severity: Notice --> Undefined index: bill_to_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-02-17 05:51:37 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 110
ERROR - 2022-02-17 05:51:37 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 111
ERROR - 2022-02-17 05:52:43 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 05:54:30 --> Severity: Notice --> Undefined index: bill_from_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-02-17 05:54:30 --> Severity: Notice --> Undefined index: bill_to_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-02-17 05:54:30 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 110
ERROR - 2022-02-17 05:54:30 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 111
ERROR - 2022-02-17 05:56:29 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 05:58:55 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-02-17 10:20:39 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:20:39 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:20:39 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:21:42 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:21:42 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:21:42 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:22:46 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:22:46 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:22:46 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:23:50 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:23:50 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:23:50 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:24:54 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:24:54 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:24:54 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:25:58 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:25:58 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:25:58 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:27:02 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:27:02 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:27:02 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:28:06 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:28:06 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:28:06 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:29:10 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:29:10 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:29:10 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:30:14 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:30:14 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:30:14 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:31:18 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:31:18 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:31:18 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:32:23 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:32:23 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:32:23 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:33:26 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:33:26 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:33:26 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:34:30 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:34:30 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:34:30 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:35:34 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:35:34 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:35:34 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:36:38 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:36:38 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:36:38 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:37:42 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:37:42 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:37:42 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:38:46 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:38:46 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:38:46 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:39:50 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:39:50 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:39:50 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:40:55 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:40:55 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:40:55 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:41:58 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:41:58 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:41:58 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:43:03 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:43:03 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:43:03 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:44:06 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:44:06 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:44:06 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:45:10 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:45:10 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:45:10 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:46:14 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:46:14 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:46:14 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:47:19 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:47:19 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:47:19 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:48:22 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:48:22 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:48:22 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:49:28 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:49:28 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:49:28 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:50:32 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:50:32 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:50:32 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:51:36 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:51:36 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:51:36 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:52:40 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:52:40 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:52:40 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:53:10 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 10:53:10 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 10:53:10 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 10:57:50 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 11:22:17 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 11:32:41 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 11:32:41 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 11:32:41 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 11:32:41 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 11:32:41 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 11:32:41 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 11:32:41 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 11:32:41 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 11:32:41 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 11:32:41 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 11:32:41 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 11:32:41 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 11:32:41 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 11:32:41 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 11:32:41 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 11:32:41 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 11:32:43 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/system/core/Exceptions.php:271) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-17 12:34:26 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 13:02:22 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:02:22 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:02:22 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:02:22 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:02:22 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:02:22 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:02:22 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:02:22 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:02:22 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:02:22 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:02:22 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:02:22 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:02:22 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:02:22 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:02:22 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:02:22 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:02:24 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/system/core/Exceptions.php:271) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-17 13:03:24 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:03:24 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:03:24 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:03:24 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:03:24 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:03:24 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:03:24 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:03:24 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:03:24 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:03:24 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:03:24 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:03:24 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:03:24 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:03:24 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:03:24 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:03:24 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:03:26 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/system/core/Exceptions.php:271) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-17 13:03:51 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:03:51 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:03:51 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:03:51 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:03:51 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:03:51 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:03:51 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:03:51 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:03:51 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:03:51 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:03:51 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:03:51 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:03:51 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:03:51 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:03:51 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:03:51 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:03:52 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/system/core/Exceptions.php:271) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-17 13:04:35 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:04:35 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:04:35 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:04:35 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:04:35 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:04:35 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:04:35 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:04:35 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:04:35 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:04:35 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:04:35 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:04:35 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:04:35 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:04:35 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:04:35 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:04:35 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:04:36 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/system/core/Exceptions.php:271) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-17 13:05:32 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:05:32 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:05:32 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:05:32 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:05:32 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:05:32 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:05:32 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:05:32 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:05:32 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:05:32 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:05:32 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:05:32 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:05:32 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:05:32 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:05:32 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:05:32 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:05:36 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/system/core/Exceptions.php:271) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-17 13:10:41 --> Severity: Notice --> Undefined variable: value /home/patanja1/public_html/emarkit/admin/application/modules/products/views/admin_edit_gst_rates.php 55
ERROR - 2022-02-17 13:10:41 --> Severity: Notice --> Undefined variable: value /home/patanja1/public_html/emarkit/admin/application/modules/products/views/admin_edit_gst_rates.php 59
ERROR - 2022-02-17 13:10:41 --> Severity: Notice --> Undefined variable: value /home/patanja1/public_html/emarkit/admin/application/modules/products/views/admin_edit_gst_rates.php 62
ERROR - 2022-02-17 13:10:41 --> Severity: Notice --> Undefined variable: value /home/patanja1/public_html/emarkit/admin/application/modules/products/views/admin_edit_gst_rates.php 65
ERROR - 2022-02-17 13:10:41 --> Severity: Notice --> Undefined variable: value /home/patanja1/public_html/emarkit/admin/application/modules/products/views/admin_edit_gst_rates.php 65
ERROR - 2022-02-17 13:10:41 --> Severity: Notice --> Undefined variable: value /home/patanja1/public_html/emarkit/admin/application/modules/products/views/admin_edit_gst_rates.php 67
ERROR - 2022-02-17 13:16:13 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 13:28:54 --> Severity: Notice --> Undefined index: bill_from_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-02-17 13:28:54 --> Severity: Notice --> Undefined index: bill_to_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-02-17 13:28:54 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 110
ERROR - 2022-02-17 13:28:54 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 111
ERROR - 2022-02-17 13:45:04 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 13:45:32 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:45:32 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:45:32 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:45:32 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:45:32 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:45:32 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:45:32 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:45:32 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:45:32 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:45:32 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:45:32 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:45:32 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:45:32 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 13:45:32 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 13:45:32 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 13:45:32 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 13:45:33 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/system/core/Exceptions.php:271) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-17 14:38:33 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 14:43:18 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 14:43:18 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 14:43:18 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 14:43:18 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 14:43:18 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 14:43:18 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 14:43:18 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 14:43:18 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 14:43:18 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 14:43:18 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 14:43:18 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 14:43:18 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 14:43:18 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 14:43:18 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 14:43:18 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 14:43:18 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 14:43:21 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/system/core/Exceptions.php:271) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-17 14:46:09 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 14:49:06 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_number() /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 2597
ERROR - 2022-02-17 14:49:06 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php:2587) /home/patanja1/public_html/emarkit/admin/system/core/Common.php 570
ERROR - 2022-02-17 14:49:34 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_number() /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 2597
ERROR - 2022-02-17 14:49:56 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 2598
ERROR - 2022-02-17 14:51:52 --> Could not find the language line ""
ERROR - 2022-02-17 14:52:36 --> Severity: error --> Exception: Call to a member function display_error() on int /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 2598
ERROR - 2022-02-17 14:58:53 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home/patanja1/public_html/emarkit/admin/application/libraries/Pktdblib.php 151
ERROR - 2022-02-17 15:07:27 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::transStart() /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 2588
ERROR - 2022-02-17 15:10:46 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::transBegin() /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 2588
ERROR - 2022-02-17 15:42:02 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 15:45:18 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 15:55:50 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 15:55:50 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 15:55:50 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 15:55:50 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 15:55:50 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 15:55:50 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 15:55:50 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 15:55:50 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 15:55:50 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 15:55:50 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 15:55:50 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 15:55:50 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 15:55:50 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1752
ERROR - 2022-02-17 15:55:50 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 15:55:50 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 15:55:50 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1762
ERROR - 2022-02-17 15:55:51 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/system/core/Exceptions.php:271) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-17 15:58:15 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 15:58:15 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1754
ERROR - 2022-02-17 15:58:15 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1756
ERROR - 2022-02-17 15:58:15 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1763
ERROR - 2022-02-17 15:58:15 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 15:58:15 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1754
ERROR - 2022-02-17 15:58:15 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1756
ERROR - 2022-02-17 15:58:15 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1763
ERROR - 2022-02-17 15:58:15 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 15:58:15 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1754
ERROR - 2022-02-17 15:58:15 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1756
ERROR - 2022-02-17 15:58:15 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1763
ERROR - 2022-02-17 15:58:15 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1753
ERROR - 2022-02-17 15:58:15 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1754
ERROR - 2022-02-17 15:58:15 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1756
ERROR - 2022-02-17 15:58:15 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1763
ERROR - 2022-02-17 15:58:16 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php:1747) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-17 16:06:37 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1754
ERROR - 2022-02-17 16:06:37 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 16:06:37 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1757
ERROR - 2022-02-17 16:06:37 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1764
ERROR - 2022-02-17 16:06:37 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1754
ERROR - 2022-02-17 16:06:37 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 16:06:37 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1757
ERROR - 2022-02-17 16:06:37 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1764
ERROR - 2022-02-17 16:06:37 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1754
ERROR - 2022-02-17 16:06:37 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 16:06:37 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1757
ERROR - 2022-02-17 16:06:37 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1764
ERROR - 2022-02-17 16:06:37 --> Severity: Notice --> Undefined index: base_uom /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1754
ERROR - 2022-02-17 16:06:37 --> Severity: Notice --> Undefined offset: 1 /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1755
ERROR - 2022-02-17 16:06:37 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1757
ERROR - 2022-02-17 16:06:37 --> Severity: Notice --> Undefined index: base_price /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php 1764
ERROR - 2022-02-17 16:06:37 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php:1753) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-17 16:08:55 --> Severity: Notice --> Undefined index: bill_from_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-02-17 16:08:55 --> Severity: Notice --> Undefined index: bill_to_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-02-17 16:08:55 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 110
ERROR - 2022-02-17 16:08:55 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 111
ERROR - 2022-02-17 16:28:15 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 16:51:09 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 16:54:53 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 16:56:13 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 16:57:11 --> Severity: Warning --> simplexml_load_string(): Entity: line 1: parser error : Opening and ending tag mismatch: META line 1 and head /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 16:57:11 --> Severity: Warning --> simplexml_load_string(): &quot;&gt;&lt;META HTTP-EQUIV=&quot;refresh&quot; CONTENT=&quot;0;URL=/cgi-sys/defaultwebpage.cgi&quot;&gt;&lt;/head&gt; /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 16:57:11 --> Severity: Warning --> simplexml_load_string():                                                                                ^ /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 16:57:11 --> Severity: Warning --> simplexml_load_string(): Entity: line 1: parser error : Opening and ending tag mismatch: META line 1 and html /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 16:57:11 --> Severity: Warning --> simplexml_load_string(): &quot;refresh&quot; CONTENT=&quot;0;URL=/cgi-sys/defaultwebpage.cgi&quot;&gt;&lt;/head&gt;&lt;body&gt;&lt;/body&gt;&lt;/html /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 16:57:11 --> Severity: Warning --> simplexml_load_string():                                                                                ^ /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 16:57:11 --> Severity: Warning --> simplexml_load_string(): Entity: line 2: parser error : Premature end of data in tag head line 1 /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 16:57:11 --> Severity: Warning --> simplexml_load_string():  /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 16:57:11 --> Severity: Warning --> simplexml_load_string(): ^ /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 16:57:11 --> Severity: Warning --> simplexml_load_string(): Entity: line 2: parser error : Premature end of data in tag html line 1 /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 16:57:11 --> Severity: Warning --> simplexml_load_string():  /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 16:57:11 --> Severity: Warning --> simplexml_load_string(): ^ /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 16:57:49 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-02-17 16:57:49 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-02-17 16:57:49 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-02-17 17:03:14 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 17:03:19 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 17:08:39 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php:1756) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-17 17:15:31 --> Severity: Notice --> Undefined index: bill_from_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-02-17 17:15:31 --> Severity: Notice --> Undefined index: bill_to_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-02-17 17:15:31 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 110
ERROR - 2022-02-17 17:15:31 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 111
ERROR - 2022-02-17 17:20:49 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 17:20:54 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 17:25:09 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 18:40:25 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php:1756) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-17 18:40:29 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/patanja1/public_html/emarkit/admin/application/modules/products/controllers/Products.php:1756) /home/patanja1/public_html/emarkit/admin/system/helpers/url_helper.php 606
ERROR - 2022-02-17 18:48:27 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-02-17 18:50:00 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-02-17 18:50:13 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-02-17 19:53:45 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 19:54:25 --> Severity: Warning --> simplexml_load_string(): Entity: line 1: parser error : Opening and ending tag mismatch: META line 1 and head /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 19:54:25 --> Severity: Warning --> simplexml_load_string(): &quot;&gt;&lt;META HTTP-EQUIV=&quot;refresh&quot; CONTENT=&quot;0;URL=/cgi-sys/defaultwebpage.cgi&quot;&gt;&lt;/head&gt; /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 19:54:25 --> Severity: Warning --> simplexml_load_string():                                                                                ^ /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 19:54:25 --> Severity: Warning --> simplexml_load_string(): Entity: line 1: parser error : Opening and ending tag mismatch: META line 1 and html /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 19:54:25 --> Severity: Warning --> simplexml_load_string(): &quot;refresh&quot; CONTENT=&quot;0;URL=/cgi-sys/defaultwebpage.cgi&quot;&gt;&lt;/head&gt;&lt;body&gt;&lt;/body&gt;&lt;/html /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 19:54:25 --> Severity: Warning --> simplexml_load_string():                                                                                ^ /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 19:54:25 --> Severity: Warning --> simplexml_load_string(): Entity: line 2: parser error : Premature end of data in tag head line 1 /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 19:54:25 --> Severity: Warning --> simplexml_load_string():  /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 19:54:25 --> Severity: Warning --> simplexml_load_string(): ^ /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 19:54:25 --> Severity: Warning --> simplexml_load_string(): Entity: line 2: parser error : Premature end of data in tag html line 1 /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 19:54:25 --> Severity: Warning --> simplexml_load_string():  /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 19:54:25 --> Severity: Warning --> simplexml_load_string(): ^ /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 20:05:55 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-02-17 20:06:19 --> Severity: Warning --> simplexml_load_string(): Entity: line 1: parser error : Opening and ending tag mismatch: META line 1 and head /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 20:06:19 --> Severity: Warning --> simplexml_load_string(): &quot;&gt;&lt;META HTTP-EQUIV=&quot;refresh&quot; CONTENT=&quot;0;URL=/cgi-sys/defaultwebpage.cgi&quot;&gt;&lt;/head&gt; /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 20:06:19 --> Severity: Warning --> simplexml_load_string():                                                                                ^ /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 20:06:19 --> Severity: Warning --> simplexml_load_string(): Entity: line 1: parser error : Opening and ending tag mismatch: META line 1 and html /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 20:06:19 --> Severity: Warning --> simplexml_load_string(): &quot;refresh&quot; CONTENT=&quot;0;URL=/cgi-sys/defaultwebpage.cgi&quot;&gt;&lt;/head&gt;&lt;body&gt;&lt;/body&gt;&lt;/html /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 20:06:19 --> Severity: Warning --> simplexml_load_string():                                                                                ^ /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 20:06:19 --> Severity: Warning --> simplexml_load_string(): Entity: line 2: parser error : Premature end of data in tag head line 1 /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 20:06:19 --> Severity: Warning --> simplexml_load_string():  /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 20:06:19 --> Severity: Warning --> simplexml_load_string(): ^ /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 20:06:19 --> Severity: Warning --> simplexml_load_string(): Entity: line 2: parser error : Premature end of data in tag html line 1 /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 20:06:19 --> Severity: Warning --> simplexml_load_string():  /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 20:06:19 --> Severity: Warning --> simplexml_load_string(): ^ /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4383
ERROR - 2022-02-17 20:21:24 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
