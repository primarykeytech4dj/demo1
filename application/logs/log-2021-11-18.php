<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-11-18 02:12:31 --> Severity: Warning --> array_walk_recursive() expects parameter 2 to be a valid callback, class 'SimpleXMLElement' does not have a method 'news' /home/patanja1/public_html/emarkit/application/libraries/Pktlib.php 884
ERROR - 2021-11-18 22:13:38 --> Query error: Column 'shipping_address_id' cannot be null - Invalid query: INSERT INTO `orders` (`project_name`, `amount_before_tax`, `amount_after_tax`, `customer_id`, `date`, `fiscal_yr`, `order_status_id`, `shipping_address_id`, `billing_address_id`, `order_code`, `modified`, `created`) VALUES ('Order Through App', '590.0', '590.0', 1330, '2021-11-18', '21-22', 1, NULL, 0, 'E/O/21-22/0016036', '2021-11-18 22:13:38', '2021-11-18 22:13:38')
ERROR - 2021-11-18 22:13:38 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::_error_message() /home/patanja1/public_html/emarkit/application/libraries/Pktdblib.php 114
