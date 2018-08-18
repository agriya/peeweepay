<?php
/* SVN: $Id: config.php 91 2008-07-08 13:13:19Z rajesh_04ag02 $ */
/**
 * Custom configurations
 */
// site actions that needs random attack protection...
$config['site']['license_key'] = '13480-6640-144-1330341231-63923f49';
$config['site']['_hashSecuredActions'] = array(
    'edit',
    'delete',
    'update',
    'download',
	'verify',
	'dashboard',
	'connect',
	'download',
	'profile_image',
);
$config['avatar']['file'] = array(
    'allowedMime' => array(
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/png'
    ) ,
    'allowedExt' => array(
        'jpg',
        'jpeg',
        'gif',
        'png'
    ) ,
    'allowedSize' => '5',
    'allowedSizeUnits' => 'MB',
    'allowEmpty' => true
);
$config['product']['file'] = array(
	'allowedMime' => '*',
	'allowedExt' => '*',
    'allowedSize' => '5',
    'allowedSizeUnits' => 'MB',
	'allowEmpty' => true
);
$config['product_image']['file'] = array(
    'allowedMime' => array(
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/png'
    ) ,
    'allowedExt' => array(
        'jpg',
        'jpeg',
        'gif',
        'png'
    ) ,
    'allowedSize' => '5',
    'allowedSizeUnits' => 'MB',
    'allowEmpty' => true
);
class ConstUserTypes
{
    const Admin = 1;
    const User = 2;
}
class ConstAttachment
{
    const UserAvatar = 1;
    const ProductPhoto = 1;
}
class ConstMoreAction
{
    const Inactive = 1;
    const Active = 2;
    const Delete = 3;
    const OpenID = 4;
    const Export = 5;
	const Approved = 6;
    const Disapproved = 7;
    const Featured = 8;
    const Notfeatured = 9;
	const Checked = 10;
    const Unchecked = 11;
    const Verified = 12;
    const Unverified = 13;
	const Suspend = 14;
    const Unsuspend = 15;
    const Enabled = 16;
    const Disabled = 17;
}
// Banned ips types
class ConstBannedTypes
{
    const SingleIPOrHostName = 1;
    const IPRange = 2;
    const RefererBlock = 3;
}
// Banned ips durations
class ConstBannedDurations
{
    const Permanent = 1;
    const Days = 2;
    const Weeks = 3;
}
class ConstPaymentGateways
{
    const PayPal = 1;
    const TwoCheckout = 2;
    const GlobalGateway = 3;
}
class ConstPaymentStatus
{
    const Completed = 'COMPLETED';
    const Incomplete = 'INCOMPLETE';    
}
class ConstPaymentGatewayFlow
{
	const BuyerSiteSeller = 'Buyer -> Site -> Seller';
	const BuyerSellerSite = 'Buyer -> Seller -> Site';
}
class ConstPaymentGatewayFee
{	
	const Seller = 'Seller';
	const Site = 'Site';
	const SiteAndSeller = 'Site and Seller';
}
class ConstShipmentCosts{
	const Order = 1;
	const Item = 2;
}
class ConstViewType{
	const NormalView = 1;
	const EmbedView = 2;
}
class ConsGroupedCountry
{
    const Worldwide = -9;
}
class ConstProfileImage
{
    const Gravatar = 1;
    const Twitter = 2;
	const Facebook = 3;
	const Upload = 4;
}
// CDN...
$config['cdn']['images'] = null; // 'http://images.localhost/';
$config['cdn']['css'] = null; // 'http://static.localhost/';

/*
date_default_timezone_set('Asia/Calcutta');

Configure::write('Config.language', 'spa');
setlocale (LC_TIME, 'es');
*/
/*
 ** to do move to settings table
*/
$config['sitemap']['models'] = array(
    'Product'
);
$config['site']['is_admin_settings_enabled'] = true;
$config['site']['exception_array'] = array(
            'pages/view',
            'pages/display',
            'users/register',
            'users/login',
            'users/logout',
            'users/reset',
            'users/forgot_password',
            'users/openid',
            'users/activation',
            'users/resend_activation',
            'users/view',
            'users/show_captcha',
            'users/captcha_play',
            'images/view',
            'devs/robots',
            'devs/sitemap',
            'contacts/add',
            'users/admin_login',
            'users/admin_logout',
            'languages/change_language',
            'contacts/show_captcha',
            'contacts/captcha_play',
			'products/index',    
			'products/add',           
			'products/delete',
			'products/edit',
			'products/v',
			'products/verify',
			'products/buy',
			'products/payment_success',
			'products/processpayment',
			'products/payment_cancel',
			'products/download',
			'products/upload',
			'products/thumbnail',
			'products/shipment_map',
            'abuse_reports/add',
            'spam_reports/add',
            'contact_sellers/add',
            'users/dashboard',
			'users/connect',
			'users/oauth_callback',
			'users/oauth_facebook',
			'users/update',
			'countries/check_country',
			'subscriptions/add',
			'crons/update_product_status',
			'users/profile_image'
		);
if ($_SERVER['HTTP_HOST'] == 'http://localhost/tinypay/' && !in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '118.102.143.2', '119.82.115.146', '122.183.135.202', '122.183.136.34','122.183.136.36'))) {
	$config['site']['is_admin_settings_enabled'] = false;
	$config['site']['admin_demomode_updation_not_allowed_array'] = array(
		'pages/admin_edit',
		'pages/admin_delete',
		'users/admin_change_password',
	);
}
?>
