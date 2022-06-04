<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Authentication\Login\Index');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);



/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// Ajax
$routes->post('ajax/fileupload', 'Common\Ajax::fileupload');
$routes->post('ajax/ajaxoccupied', 'Common\Ajax::ajaxoccupied');
$routes->post('ajax/ajaxreserved', 'Common\Ajax::ajaxreserved');
$routes->post('ajax/ajaxstripepayment', 'Common\Ajax::ajaxstripepayment');
$routes->get('stripe3d', 'Site\Stripe\Index::index');

$routes->post('ajaxsearchevents', 'Common\Ajax::ajaxsearchevents');
$routes->post('ajaxsearchfacility', 'Common\Ajax::ajaxsearchfacility');

// Cron
$routes->get('cartremoval', 'Common\Cron::cartremoval');	

// Validation
$routes->post('validation/emailvalidation', 'Common\Validation::emailvalidation');

$routes->get('event/pdf/(:any)', 'Site\Event\Index::downloadeventflyer/$1');
$routes->match(['get', 'post'], '/', 'Site\Home\Index::index');	
$routes->match(['get','post'], 'login', 'Site\Login\Index::index', ['filter' => 'siteauthentication1']);
$routes->match(['get','post'], 'register', 'Site\Register\Index::index', ['filter' => 'siteauthentication1']);
$routes->get('verification/(:any)', 'Site\Register\Index::verification/$1');
$routes->match(['get','post'], 'events', 'Site\Event\Index::lists');
$routes->match(['get','post'], 'events/detail/(:num)', 'Site\Event\Index::detail/$1');
$routes->match(['get','post'], 'facility', 'Site\Facility\Index::lists');
$routes->match(['get','post'], 'facility/detail/(:num)', 'Site\Facility\Index::detail/$1');
$routes->get('aboutus', 'Site\Aboutus\Index::index');
$routes->get('aboutus/detail/(:num)', 'Site\Aboutus\Index::detail/$1');
$routes->get('faq', 'Site\Faq\Index::index');
$routes->get('banner', 'Site\Banner\Index::index');
$routes->get('contactus', 'Site\Contactus\Index::index');
$routes->get('termsandconditions', 'Site\Termsandconditions\Index::index');
$routes->get('privacypolicy', 'Site\Privacypolicy\Index::index');
$routes->match(['get','post'], 'checkout', 'Site\Checkout\Index::index', ['filter' => 'siteauthentication2']);
$routes->get('paymentsuccess', 'Site\Checkout\Index::success');
$routes->match(['get','post'], 'cart', 'Site\Cart\Index::action');
$routes->match(['get','post'], 'contactus', 'Site\Contactus\Index::index');

$routes->get('logout', 'Site\Logout\Index::index');

$routes->group('myaccount', ['filter' => 'siteauthentication2'], function($routes){
	$routes->match(['get','post'], 'dashboard', 'Site\Myaccount\Dashboard\Index::index');
	
    $routes->match(['get','post'], 'events', 'Site\Myaccount\Event\Index::index');
    $routes->match(['get','post'], 'events/add', 'Site\Myaccount\Event\Index::action'); 
    $routes->match(['get','post'], 'events/edit/(:num)', 'Site\Myaccount\Event\Index::action/$1');
    $routes->get('events/view/(:num)', 'Site\Myaccount\Event\Index::view/$1');
    $routes->get('events/export/(:num)', 'Site\Myaccount\Event\Index::export/$1');
    $routes->get('events/eventreport/(:num)', 'Site\Myaccount\Event\Index::eventreport/$1');
    $routes->post('events/importbarnstall', 'Site\Myaccount\Event\Index::importbarnstall');

    $routes->match(['get','post'], 'facility', 'Site\Myaccount\Facility\Index::index');
    $routes->match(['get','post'], 'facility/add', 'Site\Myaccount\Facility\Index::action'); 
    $routes->match(['get','post'], 'facility/edit/(:num)', 'Site\Myaccount\Facility\Index::action/$1');
    $routes->get('facility/view/(:num)', 'Site\Myaccount\Facility\Index::view/$1');
    $routes->get('facility/export/(:num)', 'Site\Myaccount\Facility\Index::export/$1');
    $routes->post('facility/importbarnstall', 'Site\Myaccount\Facility\Index::importbarnstall');

    $routes->match(['get','post'], 'stallmanager', 'Site\Myaccount\Stallmanager\Index::index');
    $routes->match(['get','post'], 'stallmanager/add', 'Site\Myaccount\Stallmanager\Index::action'); 
    $routes->match(['get','post'], 'stallmanager/edit/(:num)', 'Site\Myaccount\Stallmanager\Index::action/$1');
    $routes->match(['get','post'], 'subscription', 'Site\Myaccount\Subscription\Index::index');
    $routes->match(['get','post'], 'account','Site\Myaccount\AccountInfo\Index::index');

    $routes->match(['get','post'], 'bookings','Site\Myaccount\Reservation\Index::index');
    $routes->match(['get', 'post'], 'stripe/(:any)', 'Site\Myaccount\Reservation\Index::index/$1'); 
    $routes->get('bookings/view/(:num)', 'Site\Myaccount\Reservation\Index::view/$1');
    $routes->post('bookings/searchbookeduser', 'Site\Myaccount\Reservation\Index::bookeduser');

    $routes->match(['get','post'], 'pastactivity','Site\Myaccount\PastActivity\Index::index');
    $routes->get('pastactivity/view/(:num)', 'Site\Myaccount\PastActivity\Index::view/$1');

    $routes->match(['get','post'], 'payments','Site\Myaccount\PaymentInfo\Index::index');
    $routes->get('payments/view/(:num)', 'Site\Myaccount\PaymentInfo\Index::view/$1');
});

$routes->match(['get', 'post'], '/administrator', 'Admin\Login\Index::index', ['filter' => 'adminauthentication1']);	
$routes->group('administrator', ['filter' => 'adminauthentication2'], function($routes){    
	$routes->get('logout', 'Admin\Logout\Index::index');
    $routes->match(['get', 'post'], 'profile', 'Admin\Profile\Index::index');

	// Users
    $routes->match(['get', 'post'], 'users', 'Admin\Users\Index::index');
    $routes->match(['get', 'post'], 'users/action', 'Admin\Users\Index::action');
    $routes->get('users/action/(:num)', 'Admin\Users\Index::action/$1');
    $routes->post('users/DTusers', 'Admin\Users\Index::DTusers');
	
	// Event
    $routes->match(['get', 'post'], 'event', 'Admin\Event\Index::index');
    $routes->match(['get', 'post'], 'event/action', 'Admin\Event\Index::action');
    $routes->get('event/action/(:num)', 'Admin\Event\Index::action/$1');
    $routes->post('event/DTevent', 'Admin\Event\Index::DTevent');
    $routes->get('event/view/(:num)', 'Admin\Event\Index::view/$1');
    $routes->post('events/importbarnstall', 'Admin\Event\Index::importbarnstall');

    // Facility
    $routes->match(['get', 'post'], 'facility', 'Admin\Facility\Index::index');
    $routes->match(['get', 'post'], 'facility/action', 'Admin\Facility\Index::action');
    $routes->match(['get', 'post'],'facility/action/(:num)', 'Admin\Facility\Index::action/$1');
    $routes->post('facility/DTfacility', 'Admin\Facility\Index::DTfacility');
    $routes->get('facility/view/(:num)', 'Admin\Facility\Index::view/$1');
    $routes->post('facility/importbarnstall', 'Admin\Facility\Index::importbarnstall');

    // Settings
    $routes->match(['get', 'post'], 'settings', 'Admin\Settings\Index::index');

    // Faq
    $routes->match(['get', 'post'], 'faq', 'Admin\Faq\Index::index');
    $routes->match(['get', 'post'], 'faq/action', 'Admin\Faq\Index::action');
    $routes->get('faq/action/(:num)', 'Admin\Faq\Index::action/$1');
    $routes->post('faq/DTfaq', 'Admin\Faq\Index::DTfaq');

    // Banner
    $routes->match(['get', 'post'], 'banner', 'Admin\Banner\Index::index');
    $routes->match(['get', 'post'], 'banner/action', 'Admin\Banner\Index::action');
    $routes->get('banner/action/(:num)', 'Admin\Banner\Index::action/$1');
    $routes->post('banner/DTbanner', 'Admin\Banner\Index::DTbanner');

    // Abouts Us
    $routes->match(['get', 'post'], 'aboutus', 'Admin\Aboutus\Index::index');
    $routes->match(['get', 'post'], 'aboutus/action', 'Admin\Aboutus\Index::action');
    $routes->get('aboutus/action/(:num)', 'Admin\Aboutus\Index::action/$1');
    $routes->post('aboutus/DTaboutus', 'Admin\Aboutus\Index::DTaboutus');

     // Terms and Conditions
    $routes->match(['get', 'post'], 'termsandconditions', 'Admin\Termsandconditions\Index::index');

    // Privacy Policy
    $routes->match(['get', 'post'], 'privacypolicy', 'Admin\Privacypolicy\Index::index');

     //Contactus
    $routes->match(['get', 'post'], 'contactus', 'Admin\Contactus\Index::index');
    $routes->match(['get', 'post'], 'contactus/DTcontactus', 'Admin\Contactus\Index::DTcontactus');

    // Plan
    $routes->match(['get', 'post'], 'plan', 'Admin\Plan\Index::index');
    $routes->match(['get', 'post'], 'plan/action', 'Admin\Plan\Index::action');
    $routes->get('plan/action/(:num)', 'Admin\Plan\Index::action/$1');
    $routes->post('plan/DTplan', 'Admin\Plan\Index::DTplan');
    
    //Payments
    $routes->get('payments', 'Admin\Payments\Index::index');
    $routes->post('payments/DTpayments', 'Admin\Payments\Index::DTpayments');
    $routes->get('payments/view/(:num)', 'Admin\Payments\Index::view/$1');

    //Reservations
    $routes->match(['get','post'],'reservations', 'Admin\Reservations\Index::index');
    $routes->post('reservations/DTreservations', 'Admin\Reservations\Index::DTreservations');
    $routes->get('reservations/view/(:num)', 'Admin\Reservations\Index::view/$1');

    //Newsletter
    $routes->get('newsletter', 'Admin\Newsletter\Index::index');
    $routes->post('newsletter/DTnewsletter', 'Admin\Newsletter\Index::DTnewsletter');

    // Report
    $routes->match(['get','post'],'eventreport', 'Admin\Report\Index::eventreport');

	// Settings
    $routes->match(['get', 'post'], 'settings', 'Admin\Settings\Index::index');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
