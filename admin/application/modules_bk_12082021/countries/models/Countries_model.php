<?php 

class Countries_model extends CI_Model {
	function construct() {
		parent::__construct();
	}

	function check_table($table=''){
    	if(empty($table))
    		return FALSE;

    	$query = $this->db->query('SHOW TABLES LIKE "'.$table.'"');
    	$res = $query->row_array();

    	return $res;
    }

    function tbl_countries(){
    	$check = $this->check_table('countries');
    	//print_r($check);exit;
    	if(!$check){
    		//echo "table does not exists<br>";exit;
    		$query = $this->db->query("CREATE TABLE IF NOT EXISTS `countries` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `name` varchar(255) NOT NULL,
				  `short_name` varchar(255) NOT NULL,
				  `currency` varchar(255) NOT NULL,
				  `currency_code` char(10) NOT NULL,
				  `is_active` tinyint(1) NOT NULL DEFAULT '0',
				  `created` datetime NOT NULL,
				  `modified` datetime NOT NULL,
				  PRIMARY KEY (id),
				  UNIQUE KEY (name)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;
				"
			);

    		if($query){
			$insert = $this->db->query("INSERT INTO `countries` (`id`, `name`, `short_name`, `currency`, `currency_code`, `is_active`, `created`, `modified`) VALUES
(1, 'India', 'IN', 'Indian Rupee', 'INR', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(2, 'New Zealand', 'NZ', 'New Zealand Dollars', 'NZD', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(3, 'Cook Islands', 'CK', 'New Zealand Dollars', 'NZD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(4, 'Niue', 'NU', 'New Zealand Dollars', 'NZD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(5, 'Pitcairn', 'PN', 'New Zealand Dollars', 'NZD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(6, 'Tokelau', 'TK', 'New Zealand Dollars', 'NZD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(7, 'Australian', 'AU', 'Australian Dollars', 'AUD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(8, 'Christmas Island', 'CX', 'Australian Dollars', 'AUD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(9, 'Cocos (Keeling) Islands', 'CC', 'Australian Dollars', 'AUD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(10, 'Heard and Mc Donald Islands', 'HM', 'Australian Dollars', 'AUD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(11, 'Kiribati', 'KI', 'Australian Dollars', 'AUD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(12, 'Nauru', 'NR', 'Australian Dollars', 'AUD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(13, 'Norfolk Island', 'NF', 'Australian Dollars', 'AUD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(14, 'Tuvalu', 'TV', 'Australian Dollars', 'AUD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(15, 'American Samoa', 'AS', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(16, 'Andorra', 'AD', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(17, 'Austria', 'AT', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(18, 'Belgium', 'BE', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(19, 'Finland', 'FI', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(20, 'France', 'FR', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(21, 'French Guiana', 'GF', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(22, 'French Southern Territories', 'TF', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(23, 'Germany', 'DE', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(24, 'Greece', 'GR', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(25, 'Guadeloupe', 'GP', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(26, 'Ireland', 'IE', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(27, 'Italy', 'IT', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(28, 'Luxembourg', 'LU', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(29, 'Martinique', 'MQ', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(30, 'Mayotte', 'YT', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(31, 'Monaco', 'MC', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(32, 'Netherlands', 'NL', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(33, 'Portugal', 'PT', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(34, 'Reunion', 'RE', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(35, 'Samoa', 'WS', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(36, 'San Marino', 'SM', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(37, 'Slovenia', 'SI', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(38, 'Spain', 'ES', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(39, 'Vatican City State (Holy See)', 'VA', 'Euros', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(40, 'South Georgia and the South Sandwich Islands', 'GS', 'Sterling', 'GBP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(41, 'United Kingdom', 'GB', 'Sterling', 'GBP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(42, 'Jersey', 'JE', 'Sterling', 'GBP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(43, 'British Indian Ocean Territory', 'IO', 'USD', 'USD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(44, 'Guam', 'GU', 'USD', 'USD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(45, 'Marshall Islands', 'MH', 'USD', 'USD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(46, 'Micronesia Federated States of', 'FM', 'USD', 'USD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(47, 'Northern Mariana Islands', 'MP', 'USD', 'USD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(48, 'Palau', 'PW', 'USD', 'USD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(49, 'Puerto Rico', 'PR', 'USD', 'USD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(50, 'Turks and Caicos Islands', 'TC', 'USD', 'USD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(51, 'United States', 'US', 'USD', 'USD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(52, 'United States Minor Outlying Islands', 'UM', 'USD', 'USD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(53, 'Virgin Islands (British)', 'VG', 'USD', 'USD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(54, 'Virgin Islands (US)', 'VI', 'USD', 'USD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(55, 'Hong Kong', 'HK', 'HKD', 'HKD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(56, 'Canada', 'CA', 'Canadian Dollar', 'CAD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(57, 'Japan', 'JP', 'Japanese Yen', 'JPY', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(58, 'Afghanistan', 'AF', 'Afghani', 'AFN', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(59, 'Albania', 'AL', 'Lek', 'ALL', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(60, 'Algeria', 'DZ', 'Algerian Dinar', 'DZD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(61, 'Anguilla', 'AI', 'East Caribbean Dollar', 'XCD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(62, 'Antigua and Barbuda', 'AG', 'East Caribbean Dollar', 'XCD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(63, 'Dominica', 'DM', 'East Caribbean Dollar', 'XCD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(64, 'Grenada', 'GD', 'East Caribbean Dollar', 'XCD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(65, 'Montserrat', 'MS', 'East Caribbean Dollar', 'XCD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(66, 'Saint Kitts', 'KN', 'East Caribbean Dollar', 'XCD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(67, 'Saint Lucia', 'LC', 'East Caribbean Dollar', 'XCD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(68, 'Saint Vincent Grenadines', 'VC', 'East Caribbean Dollar', 'XCD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(69, 'Argentina', 'AR', 'Peso', 'ARS', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(70, 'Armenia', 'AM', 'Dram', 'AMD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(71, 'Aruba', 'AW', 'Netherlands Antilles Guilder', 'ANG', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(72, 'Netherlands Antilles', 'AN', 'Netherlands Antilles Guilder', 'ANG', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(73, 'Azerbaijan', 'AZ', 'Manat', 'AZN', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(74, 'Bahamas', 'BS', 'Bahamian Dollar', 'BSD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(75, 'Bahrain', 'BH', 'Bahraini Dinar', 'BHD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(76, 'Bangladesh', 'BD', 'Taka', 'BDT', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(77, 'Barbados', 'BB', 'Barbadian Dollar', 'BBD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(78, 'Belarus', 'BY', 'Belarus Ruble', 'BYR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(79, 'Belize', 'BZ', 'Belizean Dollar', 'BZD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(80, 'Benin', 'BJ', 'CFA Franc BCEAO', 'XOF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(81, 'Burkina Faso', 'BF', 'CFA Franc BCEAO', 'XOF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(82, 'Guinea-Bissau', 'GW', 'CFA Franc BCEAO', 'XOF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(83, 'Ivory Coast', 'CI', 'CFA Franc BCEAO', 'XOF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(84, 'Mali', 'ML', 'CFA Franc BCEAO', 'XOF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(85, 'Niger', 'NE', 'CFA Franc BCEAO', 'XOF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(86, 'Senegal', 'SN', 'CFA Franc BCEAO', 'XOF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(87, 'Togo', 'TG', 'CFA Franc BCEAO', 'XOF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(88, 'Bermuda', 'BM', 'Bermudian Dollar', 'BMD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(89, 'Bhutan', 'BT', 'Indian Rupee', 'INR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(90, 'Bolivia', 'BO', 'Boliviano', 'BOB', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(91, 'Botswana', 'BW', 'Pula', 'BWP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(92, 'Bouvet Island', 'BV', 'Norwegian Krone', 'NOK', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(93, 'Norway', 'NO', 'Norwegian Krone', 'NOK', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(94, 'Svalbard and Jan Mayen Islands', 'SJ', 'Norwegian Krone', 'NOK', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(95, 'Brazil', 'BR', 'Brazil', 'BRL', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(96, 'Brunei Darussalam', 'BN', 'Bruneian Dollar', 'BND', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(97, 'Bulgaria', 'BG', 'Lev', 'BGN', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(98, 'Burundi', 'BI', 'Burundi Franc', 'BIF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(99, 'Cambodia', 'KH', 'Riel', 'KHR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(100, 'Cameroon', 'CM', 'CFA Franc BEAC', 'XAF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(101, 'Central African Republic', 'CF', 'CFA Franc BEAC', 'XAF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(102, 'Chad', 'TD', 'CFA Franc BEAC', 'XAF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(103, 'Congo Republic of the Democratic', 'CG', 'CFA Franc BEAC', 'XAF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(104, 'Equatorial Guinea', 'GQ', 'CFA Franc BEAC', 'XAF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(105, 'Gabon', 'GA', 'CFA Franc BEAC', 'XAF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(106, 'Cape Verde', 'CV', 'Escudo', 'CVE', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(107, 'Cayman Islands', 'KY', 'Caymanian Dollar', 'KYD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(108, 'Chile', 'CL', 'Chilean Peso', 'CLP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(109, 'China', 'CN', 'Yuan Renminbi', 'CNY', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(110, 'Colombia', 'CO', 'Peso', 'COP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(111, 'Comoros', 'KM', 'Comoran Franc', 'KMF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(112, 'Congo-Brazzaville', 'CD', 'Congolese Frank', 'CDF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(113, 'Costa Rica', 'CR', 'Costa Rican Colon', 'CRC', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(114, 'Croatia (Hrvatska)', 'HR', 'Croatian Dinar', 'HRK', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(115, 'Cuba', 'CU', 'Cuban Peso', 'CUP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(116, 'Cyprus', 'CY', 'Cypriot Pound', 'CYP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(117, 'Czech Republic', 'CZ', 'Koruna', 'CZK', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(118, 'Denmark', 'DK', 'Danish Krone', 'DKK', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(119, 'Faroe Islands', 'FO', 'Danish Krone', 'DKK', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(120, 'Greenland', 'GL', 'Danish Krone', 'DKK', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(121, 'Djibouti', 'DJ', 'Djiboutian Franc', 'DJF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(122, 'Dominican Republic', 'DO', 'Dominican Peso', 'DOP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(123, 'East Timor', 'TP', 'Indonesian Rupiah', 'IDR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(124, 'Indonesia', 'ID', 'Indonesian Rupiah', 'IDR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(125, 'Ecuador', 'EC', 'Sucre', 'ECS', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(126, 'Egypt', 'EG', 'Egyptian Pound', 'EGP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(127, 'El Salvador', 'SV', 'Salvadoran Colon', 'SVC', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(128, 'Eritrea', 'ER', 'Ethiopian Birr', 'ETB', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(129, 'Ethiopia', 'ET', 'Ethiopian Birr', 'ETB', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(130, 'Estonia', 'EE', 'Estonian Kroon', 'EEK', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(131, 'Falkland Islands (Malvinas)', 'FK', 'Falkland Pound', 'FKP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(132, 'Fiji', 'FJ', 'Fijian Dollar', 'FJD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(133, 'French Polynesia', 'PF', 'CFP Franc', 'XPF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(134, 'New Caledonia', 'NC', 'CFP Franc', 'XPF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(135, 'Wallis and Futuna Islands', 'WF', 'CFP Franc', 'XPF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(136, 'Gambia', 'GM', 'Dalasi', 'GMD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(137, 'Georgia', 'GE', 'Lari', 'GEL', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(138, 'Gibraltar', 'GI', 'Gibraltar Pound', 'GIP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(139, 'Guatemala', 'GT', 'Quetzal', 'GTQ', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(140, 'Guinea', 'GN', 'Guinean Franc', 'GNF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(141, 'Guyana', 'GY', 'Guyanaese Dollar', 'GYD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(142, 'Haiti', 'HT', 'Gourde', 'HTG', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(143, 'Honduras', 'HN', 'Lempira', 'HNL', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(144, 'Hungary', 'HU', 'Forint', 'HUF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(145, 'Iceland', 'IS', 'Icelandic Krona', 'ISK', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(146, 'Iran (Islamic Republic of)', 'IR', 'Iranian Rial', 'IRR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(147, 'Iraq', 'IQ', 'Iraqi Dinar', 'IQD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(148, 'Israel', 'IL', 'Shekel', 'ILS', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(149, 'Jamaica', 'JM', 'Jamaican Dollar', 'JMD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(150, 'Jordan', 'JO', 'Jordanian Dinar', 'JOD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(151, 'Kazakhstan', 'KZ', 'Tenge', 'KZT', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(152, 'Kenya', 'KE', 'Kenyan Shilling', 'KES', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(153, 'Korea North', 'KP', 'Won', 'KPW', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(154, 'Korea South', 'KR', 'Won', 'KRW', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(155, 'Kuwait', 'KW', 'Kuwaiti Dinar', 'KWD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(156, 'Kyrgyzstan', 'KG', 'Som', 'KGS', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(157, 'Lao People?s Democratic Republic', 'LA', 'Kip', 'LAK', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(158, 'Latvia', 'LV', 'Lat', 'LVL', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(159, 'Lebanon', 'LB', 'Lebanese Pound', 'LBP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(160, 'Lesotho', 'LS', 'Loti', 'LSL', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(161, 'Liberia', 'LR', 'Liberian Dollar', 'LRD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(162, 'Libyan Arab Jamahiriya', 'LY', 'Libyan Dinar', 'LYD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(163, 'Liechtenstein', 'LI', 'Swiss Franc', 'CHF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(164, 'Switzerland', 'CH', 'Swiss Franc', 'CHF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(165, 'Lithuania', 'LT', 'Lita', 'LTL', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(166, 'Macau', 'MO', 'Pataca', 'MOP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(167, 'Macedonia', 'MK', 'Denar', 'MKD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(168, 'Madagascar', 'MG', 'Malagasy Franc', 'MGA', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(169, 'Malawi', 'MW', 'Malawian Kwacha', 'MWK', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(170, 'Malaysia', 'MY', 'Ringgit', 'MYR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(171, 'Maldives', 'MV', 'Rufiyaa', 'MVR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(172, 'Malta', 'MT', 'Maltese Lira', 'MTL', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(173, 'Mauritania', 'MR', 'Ouguiya', 'MRO', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(174, 'Mauritius', 'MU', 'Mauritian Rupee', 'MUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(175, 'Mexico', 'MX', 'Peso', 'MXN', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(176, 'Moldova Republic of', 'MD', 'Leu', 'MDL', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(177, 'Mongolia', 'MN', 'Tugrik', 'MNT', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(178, 'Morocco', 'MA', 'Dirham', 'MAD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(179, 'Western Sahara', 'EH', 'Dirham', 'MAD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(180, 'Mozambique', 'MZ', 'Metical', 'MZN', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(181, 'Myanmar', 'MM', 'Kyat', 'MMK', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(182, 'Namibia', 'NA', 'Dollar', 'NAD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(183, 'Nepal', 'NP', 'Nepalese Rupee', 'NPR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(184, 'Nicaragua', 'NI', 'Cordoba Oro', 'NIO', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(185, 'Nigeria', 'NG', 'Naira', 'NGN', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(186, 'Oman', 'OM', 'Sul Rial', 'OMR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(187, 'Pakistan', 'PK', 'Rupee', 'PKR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(188, 'Panama', 'PA', 'Balboa', 'PAB', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(189, 'Papua New Guinea', 'PG', 'Kina', 'PGK', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(190, 'Paraguay', 'PY', 'Guarani', 'PYG', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(191, 'Peru', 'PE', 'Nuevo Sol', 'PEN', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(192, 'Philippines', 'PH', 'Peso', 'PHP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(193, 'Poland', 'PL', 'Zloty', 'PLN', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(194, 'Qatar', 'QA', 'Rial', 'QAR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(195, 'Romania', 'RO', 'Leu', 'RON', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(196, 'Russian Federation', 'RU', 'Ruble', 'RUB', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(197, 'Rwanda', 'RW', 'Rwanda Franc', 'RWF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(198, 'Sao Tome and Principe', 'ST', 'Dobra', 'STD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(199, 'Saudi Arabia', 'SA', 'Riyal', 'SAR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(200, 'Seychelles', 'SC', 'Rupee', 'SCR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(201, 'Sierra Leone', 'SL', 'Leone', 'SLL', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(202, 'Singapore', 'SG', 'Dollar', 'SGD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(203, 'Slovakia (Slovak Republic)', 'SK', 'Koruna', 'SKK', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(204, 'Solomon Islands', 'SB', 'Solomon Islands Dollar', 'SBD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(205, 'Somalia', 'SO', 'Shilling', 'SOS', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(206, 'South Africa', 'ZA', 'Rand', 'ZAR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(207, 'Sri Lanka', 'LK', 'Rupee', 'LKR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(208, 'Sudan', 'SD', 'Dinar', 'SDG', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(209, 'Suriname', 'SR', 'Surinamese Guilder', 'SRD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(210, 'Swaziland', 'SZ', 'Lilangeni', 'SZL', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(211, 'Sweden', 'SE', 'Krona', 'SEK', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(212, 'Syrian Arab Republic', 'SY', 'Syrian Pound', 'SYP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(213, 'Taiwan', 'TW', 'Dollar', 'TWD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(214, 'Tajikistan', 'TJ', 'Tajikistan Ruble', 'TJS', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(215, 'Tanzania', 'TZ', 'Shilling', 'TZS', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(216, 'Thailand', 'TH', 'Baht', 'THB', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(217, 'Tonga', 'TO', 'Pa?anga', 'TOP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(218, 'Trinidad and Tobago', 'TT', 'Trinidad and Tobago Dollar', 'TTD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(219, 'Tunisia', 'TN', 'Tunisian Dinar', 'TND', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(220, 'Turkey', 'TR', 'Lira', 'TRY', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(221, 'Turkmenistan', 'TM', 'Manat', 'TMT', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(222, 'Uganda', 'UG', 'Shilling', 'UGX', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(223, 'Ukraine', 'UA', 'Hryvnia', 'UAH', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(224, 'United Arab Emirates', 'AE', 'Dirham', 'AED', 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(225, 'Uruguay', 'UY', 'Peso', 'UYU', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(226, 'Uzbekistan', 'UZ', 'Som', 'UZS', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(227, 'Vanuatu', 'VU', 'Vatu', 'VUV', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(228, 'Venezuela', 'VE', 'Bolivar', 'VEF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(229, 'Vietnam', 'VN', 'Dong', 'VND', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(230, 'Yemen', 'YE', 'Rial', 'YER', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(231, 'Zambia', 'ZM', 'Kwacha', 'ZMK', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(232, 'Zimbabwe', 'ZW', 'Zimbabwe Dollar', 'ZWD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(233, 'Aland Islands', 'AX', 'Euro', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(234, 'Angola', 'AO', 'Angolan kwanza', 'AOA', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(235, 'Antarctica', 'AQ', 'Antarctican dollar', 'AQD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(236, 'Bosnia and Herzegovina', 'BA', 'Bosnia and Herzegovina convertible mark', 'BAM', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(237, 'Congo (Kinshasa)', 'CD', 'Congolese Frank', 'CDF', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(238, 'Ghana', 'GH', 'Ghana cedi', 'GHS', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(239, 'Guernsey', 'GG', 'Guernsey pound', 'GGP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(240, 'Isle of Man', 'IM', 'Manx pound', 'GBP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(241, 'Laos', 'LA', 'Lao kip', 'LAK', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(242, 'Macao S.A.R.', 'MO', 'Macanese pataca', 'MOP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(243, 'Montenegro', 'ME', 'Euro', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(244, 'Palestinian Territory', 'PS', 'Jordanian dinar', 'JOD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(245, 'Saint Barthelemy', 'BL', 'Euro', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(246, 'Saint Helena', 'SH', 'Saint Helena pound', 'GBP', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(247, 'Saint Martin (French part)', 'MF', 'Netherlands Antillean guilder', 'ANG', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(248, 'Saint Pierre and Miquelon', 'PM', 'Euro', 'EUR', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(249, 'Serbia', 'RS', 'Serbian dinar', 'RSD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."'),
(250, 'US Armed Forces', 'USAF', 'US Dollar', 'USD', 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."');");
			}
    		return $query;
    	}
    	else
    		return TRUE;
    	
    }

	function get_list($id = NULL) {

		if (empty($id)) {
			//print_r("reached here");exit;
            $query = $this->db->get('countries');
            return $query->result_array();
        }

        $query = $this->db->get_where('countries', array('id' => $id));
        return $query->row_array();
	}

	function get_active_list($id = NULL) {
		$condition['is_active'] = TRUE;
		if (empty($id))
        {
			//print_r("reached here");exit;
            $query = $this->db->get_where('countries', $condition);
            return $query->result_array();
        }
        $condition['id'] = $id;
        $query = $this->db->get_where('countries', $condition);
        return $query->row_array();
	}

	function get_dropdown_list($id = NULL) {
		//debug("reached here");exit;
		$condition['is_active'] = TRUE;
		$this->db->select('id, name');
        $query = $this->db->get_where('countries', $condition);
        return $query->result_array();
	}
}
?>