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
	    const company_id = 1;
		const num_login_attempts = 10;
		
		/*Tally Setup*/
		const tally_current_company = 'Expede Global - (21-22)';
		const tally_server = 'https://localhost';
	/*	-------------------------------------------------	*/
	
	/*	
	 |	Int. Number of minutes since last activity until
	 |	session is lost.
	 */
	 
		const user_timeout = 90;
		
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

		const module_list_url = "active_modules/adminindex";
	 	const new_module_url = "active_modules/newmodule";
		const edit_module_url = "active_modules/editmodule";
		const module_view_url = "active_modules/adminview";

		const login_page_url = "login";
		const customer_login_page_url = "login/login";
		const admin_page_url = "admin";
		const admin_login_url = "login/admin";

		const customer_page_url = "customers/myaccount";
		const forgot_username_url = "login/forgot_username";
		const reset_password_url = "login/reset_password";
		const reset_password_form_url = "login/reset-password-form";
		const email_verification_url = "login/email_verification";
		const new_login_url = "login/createuser";
		const register_user_url = "login/adduser";
		//const employees_listing_url = "employees";
		const edit_user_url = "editUser";
		//const edit_employee_url = "Editemployee";
		const logout_url = "login/logout";
		const new_email_ver_link_url = "login/send_new_verification_email";
		const change_email_before_ver_url = "login/change-email-address";
		//const new_customer_url = "newcustomer";
		//const edit_customer_url = "Editcustomer";
		//const customer_listing_url = "customer";
		const new_site_url = "customer_sites/newsite";
		const new_courier_site_url = "customer_sites/newcouriersite";
		const site_url = "customer_sites/admin_index";
		const site_service_url = "customer_sites/services";
		const edit_site_service_url = "customer_sites/editservice";


		//Billing related constants
		const bill_url = "billings/bills";

		/*Address constant starts*/
		const new_address_url = "address/newaddress";
		const admin_address_listing_url = "address/adminindex";
		const edit_address_url = "address/editaddress";
		const admin_address_view = "address/adminview";
		/*Address constant ends*/

		/*Bank Account constant starts*/
		const new_bank_account_url = "bank_accounts/newbankaccount";
		const admin_bank_account_listing_url = "bank_accounts/adminindex";
		const edit_bank_account_url = "bank_accounts/editbankaccount";
		const admin_bank_account_view = "bank_accounts/adminview";
		/*Bank Account constant ends*/
		//const user_address_url = "employeeAddress";

		/*Slider related Constants*/
		const admin_slider_listing_url = "sliders/adminindex";
		const new_slider_url = "sliders/newslider";
		const edit_slider_url = "sliders/editslider";
		const admin_view_view = "sliders/adminview";

		/* FAQ related constants*/
		const admin_faq_listing_url = "faqs/adminindex";
		const new_faq_url = "faqs/newfaq";
		const edit_faq_url = "faqs/editfaq";
		const admin_faq_view = "faqs/adminview";

		/* pages related constants*/
		const admin_page_listing_url = "pages/adminindex";
		const new_page_url = "pages/newpage";
		const edit_page_url = "pages/editpage";
		const admin_page_view = "pages/adminview";

                /*blog category up related constants*/
		const admin_blogs_category_listing_url = "blogs/blogcategories";
		const new_blogs_category_url = "blogs/newblogcategory";
		const edit_blogs_category_url = "blogs/editblogcategory";
		const admin_blogs_category_view = "blogs/readblogcategory";
		

		/* blogs related constants*/
		const admin_blog_listing_url = "blogs";
		const new_blog_url = "blogs/newblog";
		const edit_blog_url = "blogs/editblog";
		const admin_blog_view = "blogs/adminview";

		/* enquiry related constants*/
		const admin_enquiries_listing_url = "enquiries/adminindex";
		const new_enquiry_url = "enquiries/newenquiry";
		const edit_enquiry_url = "enquiries/editenquiry";
		const admin_enquiry_view = "enquiries/adminview";

		const admin_enquiries_listing_url_v2 = "enquiries_v2/adminindex";
		const new_enquiry_url_v2 = "enquiries_v2/newenquiry";
		const edit_enquiry_url_v2 = "enquiries_v2/editenquiry";
		const admin_enquiry_view_v2 = "enquiries_v2/adminview";

		/*Companies related Constants*/
		const admin_companies_listing_url = "companies/adminindex";
		const new_company_url = "companies/newcompany";
		const edit_company_url = "companies/editcompany";
		const admin_company_view = "companies/adminview";
		const edit_infra_url = "companies/editinfrastructure";
		const new_infra_url = "companies/newinfrastructure";

		/*products related Constants*/
		const admin_product_listing_url = "products/adminindex";
		const new_product_url = "products/newproduct";
		const edit_product_url = "products/editproduct";
		const admin_product_view = "products/adminview";

		/*product categories related Constants*/
		const admin_product_category_listing_url = "products/adminindexcategory";
		const new_product_category_url = "products/newcategory";
		const edit_product_category_url = "products/editcategory";
		const admin_product_category_view = "products/adminviewcategory";

		/*employee related constants*/
		const admin_employees_listing_url = "employees/adminindex";
		const new_employee_url = "employees/newemployee";
		const edit_employee_url = "employees/editemployee";
		const admin_employee_view = "employees/adminview";
		const new_other_staff_url = "employees/otherstaff";
		const edit_other_staff_url = "employees/editotherstaff";
		const admin_other_staff_listing_url = "employees/adminotherstaff";


		/*customer without companies related constants*/
		const admin_customer_listing_url = "customers/adminindex";
		const new_customer_url = "customers/newcustomer";
		const edit_customer_url = "customers/editcustomer";
		const edit_personal_info_url = "customers/editpersonalinfo";
		const admin_customer_view = "customers/adminview";
		const register_url = "customers/register";
		const customer_account = "customers/dashboard";

		/*customer w.r.t. companies related constants*/
		const admin_customer_listing_url_v2 = "customers_v2/adminindex";
		const new_customer_url_v2 = "customers_v2/newcustomer";
		const edit_customer_url_v2 = "customers_v2/editcustomer";
		const admin_customer_view_v2 = "customers_v2/adminview";

		/*student without college related constants*/
		const admin_student_listing_url = "students/adminindex";
		const new_student_url = "students/newstudent";
		const edit_student_url = "students/editstudent";
/*		const edit_personal_info_url = "students/editpersonalinfo";
*/		const admin_student_view = "students/adminview";
/*		const register_url = "students/register";
*/		const student_account = "students/dashboard";
        
		/* Stock in and stock out related constants*/
		const admin_stock_listing_url = "stocks/adminindex";
		const new_stockout_url = "stocks/out";
		const direct_stockout_url = "stocks/dispatch";
		const multiple_stockout_url = "stocks/multidispatch";
		const edit_stockout_url = "stocks/editout";
		const new_stock_url = "stocks/newstock";
		const edit_stock_url = "stocks/editstock";
		const admin_stock_view = "stocks/adminview";
		
		
		/*Vendor with vendor categories related constants*/
		const admin_vendor_listing_url = "vendors/adminindex";
		const new_vendor_url = "vendors/newvendor";
		const edit_vendor_url = "vendors/editvendor";
		const admin_vendor_view = "vendors/adminview";
		const vendor_account = "vendors/dashboard";

		/*Follow up related constants*/
		const admin_followup_listing_url = "follow_ups/adminindex";
		const new_followup_url = "follow_ups/newfollowup";
		const edit_followup_url = "follow_ups/editfollowup";
		const admin_followup_view = "follow_ups/adminview";

		/*news related constants*/
		const admin_news_category_listing_url = "news/newscategories";
		const new_news_category_url = "news/newnewscategory";
		const edit_news_category_url = "news/editnewscategory";
		const admin_news_category_view = "news/readnewscategory";
		const admin_news_listing_url = "news";
		const new_news_url = "news/newnews";
		const edit_news_url = "news/editnews";
		const admin_news_view = "news/readnews";

		/*meeting related constants*/
		const admin_meeting_listing_url = "meetings/adminindex";
		const new_meeting_url = "meetings/newmeeting";
		const edit_meeting_url = "meetings/editmeeting";
		const admin_meeting_view = "meetings/adminview";

		/*upload document related constants*/
		const admin_document_listing_url = "upload_documents/adminindex";
		const new_upload_document_url = "upload_documents/newdocumentupload";
		const new_document_type_url = "upload_documents/newdocumenttype";

		/* enquiry related constants*/
		const admin_quotation_listing_url = "quotations/adminindex";
		const new_quotation_url = "quotations/newquotation";
		const edit_quotation_url = "quotations/editquotation";
		const admin_quotation_view = "quotations/adminview";

		/* order or Sales related constants*/
		const admin_order_listing_url = "orders/adminindex";
		const new_order_url = "orders/neworder";
		const new_order_url2 = "orders/neworder2";//for sales app
		const edit_order_url = "orders/editorder";
		const admin_order_view = "orders/adminview";
		const new_project_url = "orders/newproject";
		const edit_project_url = "orders/editproject";
		const admin_project_listing_url = "orders/adminprojects";

		/*messages related Constants*/
		const admin_message_listing_url = "communications/adminindex";
		const new_message_url = "communications/newmessage";
		const add_message_url = "communications/createmessage";
		const edit_message_url = "communications/editmessage";
		const admin_message_view = "communications/adminview";
		const customer_message_listing_url = "communications";
		const customer_message_view = "communications/view";
		const compose_message_url = "communications/compose";

		/*Social Media Share related constants*/
		const admin_social_listing_url = "social_media_share/adminindex";
		const new_social_url = "social_media_share/newsms";
		const new_packagewise_social_url = "social_media_share/newsmspackagewise";
		const edit_social_url = "social_media_share/editsms";
		const admin_social_view = "social_media_share/adminview";

		/*testimonials related constants*/
		const admin_testimonial_listing_url = "testimonials/adminindex";
		const new_testimonial_url = "testimonials/newtestimonial";
		const edit_testimonial_url = "testimonials/edittestimonial";
		const admin_testimonial_view = "testimonials/adminview";

		/*Subscription related constants*/
		const admin_subscription_listing_url = "subscriptions/adminindex";
		const new_subscription_url = "subscriptions/newSubscriptions";

		/*menu related constants*/
		const admin_menu_listing_url = "menus/adminindex";
		const new_menu_url = "menus/newmenu";
		const edit_menu_url = "menus/editmenu";
		/*Survey related constants*/
		const admin_survey_add = "survey/admin_add";

		/*gallery related constants*/
		const admin_gallery_listing_url = "gallery/adminindex";
		const new_gallery_url = "gallery/newgallery";
		const edit_gallery_url = "gallery/editgallery";
		const admin_gallery_view = "gallery/adminview";
		const delete_album_url = 'gallery/deletealbum';

		/*gallery categories related constants*/
		const admin_category_listing_url = "gallery/adminindexcategory";
		const new_category_url = "gallery/newcategory";
		const edit_category_url = "gallery/editcategory";
		const admin_category_view = "gallery/adminviewcategory";

		/*gallery Pictures related constants*/
		const admin_picture_listing_url ='gallery/albumpictures';
		
		/* advertisements related constants*/
		const admin_advertisement_listing_url = "advertisements/adminindex";
		const new_advertisement_url = "advertisements/newadvertisement";
		const edit_advertisement_url = "advertisements/editadvertisement";
		const admin_advertisement_view = "advertisements/adminview";
		
		/* advertisement section related constants*/
		const admin_advertisement_section_listing_url = "advertisements/adminindexsection";
		const new_advertisement_section_url = "advertisements/newadvertisementsection";
		const edit_advertisement_section_url = "advertisements/editadvertisementsection";
		const admin_advertisement_section_view = "advertisements/adminviewsection";

		/*Roles related constants*/
		const admin_role_listing_url = 'roles/adminindex';
		const new_role_url = 'roles/newrole';
		const edit_role_url = 'roles/editrole';
		const admin_role_view = 'roles/adminview';
		/* Roles related constant ends here*/

		/*Brands related constants*/
		const admin_brand_listing_url = 'brands/adminindex';
		const new_brand_url = 'brands/newbrand';
		const edit_brand_url = 'brands/editbrand';
		const admin_brand_view = 'brands/adminview';
		/* Brands related constant ends here*/

		


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
			"admin", 
			'companies/adminindex', "companies/newCompany", "companies/adminview", 'companies/editCompany', 
			"products/admincategoryindex", "products/newproductcategory", "products/editcategory", "products/adminviewcategory",
			"products/adminindex", "products/newproduct", "products/editproduct", "products/adminview",
			"employees/adminindex", "employees/newemployee", "employees/editemployee", "employees/adminview",
			"employees/editotherstaff","employees/otherstaff", "employees/adminotherstaff",
			"customers/adminindex", "customers/newcustomer", "customers/editcustomer", "customers/adminview",
			/*FAQ restriction**/
			"faqs/adminindex", "faqs/newfaq", "faqs/editfaq","faqs/adminview",
			/*enquiry Version 2 restriction*/
			/*enquiry restriction**/
			"enquiries/adminindex", "enquiries/newenquiry", "enquiries/editenquiry","enquiries/adminview",
			/*enquiry Version 2 restriction*/
			"enquiries_v2/adminindex", "enquiries_v2/newenquiry", "enquiries_v2/editenquiry","enquiries_v2/adminview",
			"quotations/adminindex", "quotations/newquotation", "quotations/editquotation", "quotations/adminview",
			"customers_v2/adminindex", "customers_v2/newcustomer", "customers_v2/editcustomer", "customers_v2/adminview",

			/*pages related*/
			"pages/adminindex", "pages/newpage", "pages/editpage", "pages/adminview",

			/*Social Media Share related*/
			"social_media_share/adminindex", "social_media_share/newsms", "social_media_share/editsms", "social_media_share/adminview",
			
			/* Survey */
			'survey/admin_add', 'survey/submit',
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
	 
		const main_site_url 	= "https://emarkit.patanjaliudaipur.com/admin/";
		const main_site_display = "Emarkit";
		
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
	 //print_r($_SERVER);exit;
		const mailer_address = 'emarkit@expedeglobal.com';
		const mailer_name = 'Emarkit';
		
	/*	-------------------------------------------------	*/

	/*	
	 |	String. Default account type. Set this to whatever
	 |	your default user type is for your application.
	 |	Or use the default 'basic'.
	 */
	 
		const default_account_type = 'admin';
		
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

	/*	
	 |	Boolean. Enables or disables ability for user to login
	 |	with their email address as well as their username.
	 */
	 
		const current_version = '3.3';
		
	/*	-------------------------------------------------	*/
	/* 10/20/2020 Kanak*/
	/*	
	 |	Boolean. Enables or disables ability for user to login
	 |	with their email address as well as their username.
	 */
	 
	const fieldmember_url = 'fieldmember_panel/adminindex';
    const assign_deliveryboy_url = 'fieldmember_panel/assignorders';
    const make_delivery = 'fieldmember_panel/make_delivery';
    const make_delivery2 = 'fieldmember_panel/make_delivery2';
	const payment_consolidation = 'fieldmember_panel/payment-consolidation';
	const morning_stock_report = 'fieldmember_panel/morning_stock_report';
	const evening_stock_report = 'fieldmember_panel/evening_stock_report';
	
	const new_customer_url_new = "customers/new_customer";
	const edit_customer_url_new = "customers/edit_customer";
	
	
	//const user_edit_url = "login/adminedituser";admin_edit_user
	const user_edit_url = "login/admin_edit_user";
	/*	-------------------------------------------------	*/

}

?>
