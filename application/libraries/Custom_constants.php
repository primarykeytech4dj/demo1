<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 |----------------------------------------------------------------------
 |
 |	Class is used for configuring global constants. Rather than use
 |	the CodeIgniter constants file this library should be used in
 |	case of updates to CodeIgniter overwriting the constants file.
 |
 |----------------------------------------------------------------------
 */

class Custom_Constants {
	
	/*	
	 |	FALSE or int. Number of login attempts before IP is
	 |	blacklisted. FALSE turns off IP blacklisting.
	 */
	 
		const num_login_attempts = 10;
		
	/*	-------------------------------------------------	*/
	
	/*	
	 |	Int. Number of minutes since last activity until
	 |	session is lost.
	 */
	 
		const user_timeout = 30;
		
	/*	-------------------------------------------------	*/
		
	/*	
	 |	Int. How many minutes an IP is blacklisted for
	 |	after being locked out. Setting only applied
	 |	if num_login_attempts is not FALSE.
	 */
		
		const black_list_timeout = 15;
		
	/*	-------------------------------------------------	*/
	
	/*	
	 |	Int. How many minutes with no activity until
	 |	a blacklisted IP is removed from the
	 |	blacklist. Setting only applied if
	 |	num_login_attempts is not FALSE.
	 */
		
		const black_list_reset_time = 60;
		
	/*	-------------------------------------------------	*/
	
	/*	
	 |	Int. Time in hours how long validation strings
	 |	are valid for.
	 */
		
		const passwd_reset_valid_time = 24;
		const email_ver_string_time = 24;
		const mobile_ver_string_time = 5; // Minutes
		
	/*	-------------------------------------------------	*/
	
	/*	
	 |	String. URL's for respective pages. Make sure to
	 |	reflect changes in your routes config. These
	 |	URL's are appended to CodeIgniters base_url()
	 */
		const login_url = "login/index";
		const home = "home";

		const not_found 				= "404_override";
		const login_page_url 				= "login/index";
		const otp_login_url 				= "otp_login";
		//const dashboard_url 				= "dashboard";
		const admin_page_url 				= "admin";
		const customer_page_url = "dashboard";
		const forgot_username_url = "login/forgot_username";
		const reset_password_url = "login/reset_password";
		const reset_password_form_url = "login/reset-password-form";
		const email_verification_url = "login/email_verification";
		const mobile_verification_url 		= "login/mobile_verification";
		const account_verification_url 		= "login/account_verification";
		const upload_kyc_url 				= "upload_kyc";
		const admin_approval_url 			= "need_admin_approval";
		const register_url 					= "customers/register";
		const logout_url 					= "login/logout";
		const new_email_ver_link_url 		= "login/send_new_verification_email";
		const new_otp_url 					= "login/send_new_otp";
		const change_email_before_ver_url 	= "login/change_email_address";
		const change_mobile_before_ver_url 	= "login/change_mobile_number";

		/*Address constant starts*/
		const new_address_url = "address/newaddress";
		const admin_address_listing_url = "address/adminindex";
		const edit_address_url = "address/editaddress";
		const admin_address_view = "address/adminview";
		/*Address constant ends*/

		/*Vendor constant starts*/
		const dashboard_url = "dashboard";
		/*Vendor constant ends*/

		/*order constants*/

		const confirm_order = 'orders/confirmation';


		
	/*	-------------------------------------------------	*/
	
	/*	
	 |	Array of pages which require login to access. This
	 |	will match any URI beginning with this string.
	 |
	 |	Example.	If "admin/portal" is in the array then,
	 |				http://domain.com/admin would not be blocked
	 |				http://domain.com/admin/portal would be blocked
	 |				http://domain.com/admin/portal/page would also be blocked
	 |
	 |	This array is used for determining whether it is safe
	 |	to display the 404 error page.
	 */

		public static $protected_pages = array(
											"checkout", "order", "dashboard"
										);
		
	/*	-------------------------------------------------	*/
		
	/*	
	 |	String. If your login is part of a public site
	 |	this URL should be set to the public site.
	 |
	 |	Example.	Login is login.example.com
	 |				Main site is www.example.com
	 |
	 |	Display is how it will be presented in HTML.
	 |	If display is not set then display will default
	 |	to the full URL.
	 |
	 |	Both of these constants can be removed if
	 |	you don't want to use them.
	 */
	 
		const main_site_url 	= "https://emarkit.patanjaliudaipur.com/";
		const admin_url 		= "https://emarkit.patanjaliudaipur.com/admin/";
		const main_site_display = "Emarkit";
		const company_id = 1;
		
	/*	-------------------------------------------------	*/

	/*	
	 |	Boolean. Turns on/off white listing for
	 |	new users registering. If a new user tries
	 |	to register with white list set to TRUE
	 |	and their email is not whitelisted then they
	 |	will not be able to register.
	 */
	 
		const white_list = FALSE;
		
	/*	-------------------------------------------------	*/

	/*	
	 |	String. The email address that any password resets,
	 |	forgotten username requests or email verifications
	 |	come from.
	 */
	 
		const mailer_address = 'emarkit@expedeglobal.com';
		const mailer_name = 'Emarkit';
		
	/*	-------------------------------------------------	*/

	/*	
	 |	String. Default account type. Set this to whatever
	 |	your default user type is for your application.
	 |	Or use the default 'basic'.
	 */
	 
		const default_account_type = 'basic';

	/*	
	 |	Int. Default account check. Set this to 4 if you don't want to keep restrictions for
	 |	1 = Email / Mobile Verification,
	 |	2 = KYC Upload,
	 |	3 = Admin Approval,
	 |	4 = Ready to use,
	 */
	 
		const default_account_status = 1;

	/*	
	 |	Email verified. Set this to "yes" if you don't want to keep restrictions for email verification
	 */
	 
		const email_verified = FALSE;

	/*	
	 |	Mobile verified. Set this to "yes" if you don't want to keep restrictions for mobile verification
	 */
	 
		const mobile_verified = FALSE;
		
	/*	-------------------------------------------------	*/
	
	/*	
	 |	Boolean. Enables or disables user registration.
	 */
	 
		const registration_disable = FALSE;
		
	/*	-------------------------------------------------	*/

	/*	
	 |	Boolean. Enables or disables ability for user to login
	 |	with their email address as well as their username.
	 */
	 
		const email_login_allowed = TRUE;
		
	/*	-------------------------------------------------	*/

}

?>
