<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-04-13 10:02:07 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'group by r.zone_no' at line 1 - Invalid query: select l.id, concat(l.first_name, " ",l.surname) as person_name, l.username from login l INNER JOIN user_roles ur on ur.login_id=l.id left join routes r on r.login_id=l.id where ur.login_id=l.id and ur.role_id=7 AND l.is_active=true order by person_name ASC group by r.zone_no
ERROR - 2022-04-13 10:02:07 --> Severity: Warning --> Invalid argument supplied for foreach() /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 4475
ERROR - 2022-04-13 10:02:17 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'group by r.zone_no' at line 1 - Invalid query: select l.id, concat(l.first_name, " ",l.surname) as person_name, l.username from login l INNER JOIN user_roles ur on ur.login_id=l.id left join routes r on r.login_id=l.id where ur.login_id=l.id and ur.role_id=7 AND l.is_active=true order by person_name ASC group by r.zone_no
ERROR - 2022-04-13 10:02:17 --> Severity: Warning --> Invalid argument supplied for foreach() /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 4475
ERROR - 2022-04-13 10:02:46 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'group by r.zone_no' at line 1 - Invalid query: select l.id, concat(l.first_name, " ",l.surname) as person_name, l.username from login l INNER JOIN user_roles ur on ur.login_id=l.id left join routes r on r.login_id=l.id where ur.login_id=l.id and ur.role_id=7 AND l.is_active=true order by person_name ASC group by r.zone_no
ERROR - 2022-04-13 10:02:46 --> Severity: Warning --> Invalid argument supplied for foreach() /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 4476
ERROR - 2022-04-13 10:15:35 --> Severity: Notice --> Undefined index: bill_from_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-04-13 10:15:35 --> Severity: Notice --> Undefined index: bill_to_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-04-13 10:15:35 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 110
ERROR - 2022-04-13 10:15:35 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 111
ERROR - 2022-04-13 10:40:39 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-04-13 10:40:44 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-04-13 10:41:39 --> Severity: Notice --> Undefined variable: address /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4300
ERROR - 2022-04-13 10:41:39 --> Severity: Notice --> Undefined variable: address /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4301
ERROR - 2022-04-13 10:41:39 --> Severity: Notice --> Undefined variable: address /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4302
ERROR - 2022-04-13 10:41:39 --> Severity: Notice --> Undefined variable: address /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4303
ERROR - 2022-04-13 10:41:39 --> Severity: Notice --> Undefined variable: address /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4303
ERROR - 2022-04-13 10:41:39 --> Severity: Notice --> Undefined variable: address /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4305
ERROR - 2022-04-13 10:41:39 --> Severity: Notice --> Undefined variable: address /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4307
ERROR - 2022-04-13 10:41:39 --> Severity: Notice --> Undefined variable: address /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4308
ERROR - 2022-04-13 10:41:39 --> Severity: Notice --> Undefined variable: address /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4310
ERROR - 2022-04-13 10:41:39 --> Severity: Warning --> simplexml_load_string(): Entity: line 1: parser error : Opening and ending tag mismatch: META line 1 and head /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4384
ERROR - 2022-04-13 10:41:39 --> Severity: Warning --> simplexml_load_string(): &quot;&gt;&lt;META HTTP-EQUIV=&quot;refresh&quot; CONTENT=&quot;0;URL=/cgi-sys/defaultwebpage.cgi&quot;&gt;&lt;/head&gt; /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4384
ERROR - 2022-04-13 10:41:39 --> Severity: Warning --> simplexml_load_string():                                                                                ^ /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4384
ERROR - 2022-04-13 10:41:39 --> Severity: Warning --> simplexml_load_string(): Entity: line 1: parser error : Opening and ending tag mismatch: META line 1 and html /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4384
ERROR - 2022-04-13 10:41:39 --> Severity: Warning --> simplexml_load_string(): &quot;refresh&quot; CONTENT=&quot;0;URL=/cgi-sys/defaultwebpage.cgi&quot;&gt;&lt;/head&gt;&lt;body&gt;&lt;/body&gt;&lt;/html /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4384
ERROR - 2022-04-13 10:41:39 --> Severity: Warning --> simplexml_load_string():                                                                                ^ /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4384
ERROR - 2022-04-13 10:41:39 --> Severity: Warning --> simplexml_load_string(): Entity: line 2: parser error : Premature end of data in tag head line 1 /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4384
ERROR - 2022-04-13 10:41:39 --> Severity: Warning --> simplexml_load_string():  /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4384
ERROR - 2022-04-13 10:41:39 --> Severity: Warning --> simplexml_load_string(): ^ /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4384
ERROR - 2022-04-13 10:41:39 --> Severity: Warning --> simplexml_load_string(): Entity: line 2: parser error : Premature end of data in tag html line 1 /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4384
ERROR - 2022-04-13 10:41:39 --> Severity: Warning --> simplexml_load_string():  /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4384
ERROR - 2022-04-13 10:41:39 --> Severity: Warning --> simplexml_load_string(): ^ /home/patanja1/public_html/emarkit/admin/application/modules/tally/controllers/Tally.php 4384
ERROR - 2022-04-13 10:54:59 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-04-13 10:55:02 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-04-13 11:23:43 --> Query error: Duplicate entry '1101' for key 'username' - Invalid query: UPDATE `login` SET `first_name` = 'Ravi', `surname` = 'Yadav', `username` = '1101', `password_hash` = '$2y$10$8E3zIyIfRbvFQ008xuJCkeNY0ORxNdPSGPZY5pQoWR0Lu1nQ.fJHu', `employee_id` = '175', `email` = '1101@expedeglobal.com', `email_verified` = 'yes'
WHERE `id` = '175'
ERROR - 2022-04-13 11:25:37 --> Query error: Duplicate entry '1101' for key 'username' - Invalid query: UPDATE `login` SET `first_name` = 'Ravi', `surname` = 'Yadav', `username` = '1101', `password_hash` = '$2y$10$HUhDVtsJPByXOgWw3fmBwO2v8BDrdYGEBJQdMUu7f6PxYn4oqgAD.', `employee_id` = '175', `email` = '1101@expedeglobal.com', `email_verified` = 'yes'
WHERE `id` = '175'
ERROR - 2022-04-13 12:42:24 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-04-13 12:56:29 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-04-13 13:30:02 --> Severity: Notice --> Undefined offset: 0 /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 289
ERROR - 2022-04-13 13:30:02 --> Severity: Warning --> array_merge(): Expected parameter 2 to be an array, null given /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 289
ERROR - 2022-04-13 13:30:02 --> Severity: Notice --> Undefined index: bill_from_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-04-13 13:30:02 --> Severity: Notice --> Undefined index: bill_to_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-04-13 13:30:02 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 110
ERROR - 2022-04-13 13:30:02 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 111
ERROR - 2022-04-13 13:30:15 --> Severity: Notice --> Undefined index: invoice_address_id /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/admin_edit.php 196
ERROR - 2022-04-13 14:25:07 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-04-13 14:38:05 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-04-13 14:38:10 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-04-13 16:09:56 --> Severity: Notice --> Undefined index: invoice_address_id /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/admin_add.php 187
ERROR - 2022-04-13 16:11:45 --> Severity: Notice --> Undefined index: bill_from_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-04-13 16:11:45 --> Severity: Notice --> Undefined index: bill_to_date /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 36
ERROR - 2022-04-13 16:11:45 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 110
ERROR - 2022-04-13 16:11:45 --> Severity: Notice --> Undefined index: fuel_surcharge /home/patanja1/public_html/emarkit/admin/application/modules/orders/views/email/order.php 111
ERROR - 2022-04-13 16:12:05 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-04-13 16:18:46 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-04-13 16:27:55 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-04-13 16:32:42 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-04-13 16:37:03 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-04-13 16:48:05 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-04-13 16:55:09 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-04-13 16:55:09 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-04-13 16:55:09 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-04-13 16:57:12 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-04-13 16:57:12 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-04-13 16:57:12 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-04-13 16:57:13 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 115
ERROR - 2022-04-13 16:57:13 --> Severity: Notice --> Undefined index: username /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 116
ERROR - 2022-04-13 16:57:13 --> Severity: Notice --> Undefined index: password /home/patanja1/public_html/emarkit/admin/application/modules/login/views/login_default.php 117
ERROR - 2022-04-13 17:02:55 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') order by o.id ASC' at line 1 - Invalid query: select o.id,o.customer_id,o.date,o.shipping_address_id, o.order_code, o.amount_after_tax, concat(c.first_name," ", c.surname) as customer, ar.area_name as area, c.company_name, a.site_name from orders o inner join customers c on c.id=o.customer_id left join address a on a.id=o.shipping_address_id left join areas ar on ar.id=a.area_id where order_code not in (Select order_code from invoice_orders) and o.order_status_id=3 and o.date>"2021-12-06" and o.customer_id in () order by o.id ASC
ERROR - 2022-04-13 18:51:22 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-04-13 18:52:01 --> Module controller failed to run: customers/customer_with_default_address
ERROR - 2022-04-13 18:52:01 --> Severity: Warning --> Invalid argument supplied for foreach() /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 1321
ERROR - 2022-04-13 18:52:06 --> Module controller failed to run: customers/customer_with_default_address
ERROR - 2022-04-13 18:52:06 --> Severity: Warning --> Invalid argument supplied for foreach() /home/patanja1/public_html/emarkit/admin/application/modules/orders/controllers/Orders.php 1321
ERROR - 2022-04-13 18:56:10 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Error' does not have a method 'error_404' /home/patanja1/public_html/emarkit/admin/system/core/CodeIgniter.php 532
ERROR - 2022-04-13 19:14:36 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 19:14:36 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 19:15:01 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 19:15:01 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 19:15:16 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 19:15:16 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 19:18:20 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 19:34:10 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 19:34:10 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 19:34:43 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 19:34:43 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 19:35:07 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 19:35:07 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 19:49:22 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 19:49:23 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 19:49:35 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 19:49:35 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 19:49:37 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 19:54:34 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 20:56:51 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 20:56:51 --> Module controller failed to run: setup/loadModuleWiseSetup
ERROR - 2022-04-13 20:56:52 --> Module controller failed to run: setup/loadModuleWiseSetup