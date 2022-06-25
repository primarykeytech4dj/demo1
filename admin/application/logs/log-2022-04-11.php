<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-04-11 05:52:58 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-04-11 09:23:22 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-04-11 09:23:22 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-04-11 09:23:22 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-04-11 09:48:19 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-04-11 09:48:19 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-04-11 09:48:19 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-04-11 10:21:05 --> Severity: Notice --> Undefined offset: 0 /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 289
ERROR - 2022-04-11 10:21:05 --> Severity: Warning --> array_merge(): Expected parameter 2 to be an array, null given /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 289
ERROR - 2022-04-11 10:21:05 --> Severity: Notice --> Undefined index: bill_from_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-04-11 10:21:05 --> Severity: Notice --> Undefined index: bill_to_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-04-11 10:21:05 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 110
ERROR - 2022-04-11 10:21:05 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 111
ERROR - 2022-04-11 10:23:31 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-04-11 10:23:31 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-04-11 10:23:31 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-04-11 11:03:49 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-04-11 11:03:49 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-04-11 11:03:49 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-04-11 11:03:49 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-04-11 11:03:49 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-04-11 11:03:49 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-04-11 11:12:24 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-04-11 11:12:24 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-04-11 11:12:24 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-04-11 11:12:32 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-04-11 11:12:32 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-04-11 11:12:32 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-04-11 11:15:47 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-04-11 11:48:55 --> Severity: Notice --> Undefined offset: 0 /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 1642
ERROR - 2022-04-11 12:22:27 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-04-11 12:22:27 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-04-11 12:22:27 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-04-11 12:22:37 --> Severity: Warning --> Use of undefined constant php - assumed 'php' (this will throw an Error in a future version of PHP) /home/patanja1/public_html/emarkit/admin/application/modules/routes/views/admin_add.php 23
ERROR - 2022-04-11 16:10:19 --> Severity: Notice --> Undefined index: invoice_no /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 1960
ERROR - 2022-04-11 16:10:19 --> Severity: Notice --> Undefined index: invoice_no /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 1960
ERROR - 2022-04-11 16:10:19 --> Severity: Notice --> Undefined index: invoice_no /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 1960
ERROR - 2022-04-11 16:10:19 --> Severity: Notice --> Undefined index: invoice_no /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 1960
ERROR - 2022-04-11 16:11:28 --> Severity: Notice --> Undefined index: invoice_no /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 1960
ERROR - 2022-04-11 16:11:28 --> Severity: Notice --> Undefined index: invoice_no /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 1960
ERROR - 2022-04-11 16:11:28 --> Severity: Notice --> Undefined index: invoice_no /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 1960
ERROR - 2022-04-11 16:11:28 --> Severity: Notice --> Undefined index: invoice_no /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 1960
ERROR - 2022-04-11 18:33:33 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-04-11 19:53:28 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-04-11 19:53:28 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-04-11 19:53:28 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
