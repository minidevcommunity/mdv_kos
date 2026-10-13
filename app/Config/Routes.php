<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->addRedirect('/', 'login');

$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::authenticate');
$routes->post('/logout', 'Auth::logout');

// Reset Password Routes
$routes->get('/forgot-password', 'Auth::forgotPassword');
$routes->post('/forgot-password', 'Auth::sendResetLink');
$routes->get('/reset-password', 'Auth::resetPassword');
$routes->get('/reset-password/(:any)', 'Auth::resetPassword/$1');
$routes->post('/reset-password', 'Auth::updatePassword');

// Registration Routes
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::processRegister');

// Email Verification Routes (Temporary Users)
$routes->get('/verify-email', 'Auth::verifyEmail');
$routes->post('/verify-email', 'Auth::processVerifyEmail');
$routes->post('/resend-verification', 'Auth::resendVerification');

$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);
