<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//------------------------------//
//-----------FrontEnd-----------//
//------------------------------//

Route::group(['namespace'=>'Frontend'], function(){
    
    Route::get('/member-blog','BlogController@index');
    Route::post('/blog/rate','BlogController@rate')->name("blog-rate");
    Route::get('/index','ProductController@trangchu')->name('index');
    Route::post('index','ProductController@search');
    Route::get('email','ProductController@email')->name('email');  
    Route::get('search','SearchController@search');
    Route::post('search','SearchController@search_advance');
    Route::post('index','SearchController@search_range')->name('search-range');
    Route::get('/single-blog/{id}','BlogController@single')->name('blog-single');
    Route::post('/single-blog/{id}','BlogController@comment');
    Route::post('/logout-1','AccountController@logout');
    Route::get('/detail-product/{id}','ProductController@detail')->name('detail-product');
    Route::post('add-to-cart','ProductController@addCart')->name('add-to-cart');
    Route::get('cart','ProductController@cart')->name('cart');
    Route::post('ajax-handle-cart','ProductController@handleCart')->name('ajax-handle-cart');
    Route::get('checkout','ProductController@checkout');
    Route::post('checkout','MemberController@register');
});
Route::group(['namespace'=>'Frontend'],function(){
    Route::get('/register1','MemberController@index');
    Route::post('/register1','MemberController@register');
    Route::get('/member/login','MemberController@log')->name('login.member');
    Route::post('/member/login','MemberController@login');
});
Route::group(['prefix' => 'user','middleware' => 'user'],function(){
    Route::post('/account','Frontend\AccountController@update');  
    Route::get('/account','Frontend\AccountController@index')->name('user.account');
    Route::post('/account','Frontend\AccountController@update');
    Route::get('my-product','Frontend\ProductController@index')->name('my-product');
    Route::get('/add-product','Frontend\ProductController@add')->name('add.product');
    Route::post('/add-product','Frontend\ProductController@store')->name('add.product.post');
    Route::get('/edit-product/{id}','Frontend\ProductController@edit');
    Route::post('/edit-product/{id}','Frontend\ProductController@update');
    Route::get('/delete-product/{id}','Frontend\ProductController@delete');
});


//----------------------------------------------------------------
//----------------------------------------------------------------
//----------------------------------------------------------------
//------------------------------//
//-----------Admin--------------//
//------------------------------//





Route::group([
    'prefix' => 'admin', 'middleware' => 'admin'
], function(){
   
    Route::get('/form','admin\DashboardController@form')->name('admin-form');
    Route::get('/icon','admin\DashboardController@icon')->name('admin-icon');
    Route::get('/starter','admin\DashboardController@starter');
    Route::get('/table','admin\DashboardController@table')->name('admin-table');
    Route::get('/dashboard','admin\UserController@index');
    Route::get('/profile','admin\UserController@profile')->name('admin-profile');
    Route::post('/profile','admin\UserController@update');
    Route::get('/country','admin\countryController@index')->name('admin-country');
    Route::get('/add','admin\countryController@add')->name('add-country');
    Route::post('/add','admin\countryController@store');
    Route::get('/country/{id}','admin\countryController@destroy');
    Route::get('/blog1','admin\BlogController@main')->name('all-blog');
    Route::get('/addBlog','admin\BlogController@huy')->name('admin-addBlog');
    Route::post('/addBlog','admin\BlogController@store');
    Route::get('/editBlog/{id}','admin\BlogController@edit');
    Route::post('/editBlog/{id}','admin\BlogController@update');
    Route::get('/deleteBlog/{id}','admin\BlogController@destroy');
    
});
   
   
Route::get('/home', 'HomeController@index')->name('home');
Auth::routes();



