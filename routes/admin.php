<?php

use App\Http\Controllers\AddonController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AizUploadController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\ZoneController;
use App\Models\Quotation;
use App\Addons\MultiVendor\Http\Controllers\MultiVendorController;
use App\Http\Controllers\BookingController;
use  App\Http\Controllers\EmployeeController;
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::post('/update', [UpdateController::class, 'step0'])->name('update');
Route::get('/update/step1', [UpdateController::class, 'step1'])->name('update.step1');
Route::get('/update/step2', [UpdateController::class, 'step2'])->name('update.step2');
Route::get('/convert_for_update', [UpdateController::class, 'convertForMultivendor']);

Route::get('/refresh-csrf', function () {
    return csrf_token();
});

Route::get('/clear-cache-all', function() {
    Artisan::call('cache:clear');
    dd("Cache Clear All");

});

// Route::get('/refresh-rrr/3', function () {
//      return view('backend.product.quotation.check');
//     //dd(Quotation::all());
//});

Route::post('/aiz-uploader', [AizUploadController::class, 'show_uploader']);
Route::post('/aiz-uploader/upload', [AizUploadController::class, 'upload']);
Route::get('/aiz-uploader/get_uploaded_files', [AizUploadController::class, 'get_uploaded_files']);
Route::delete('/aiz-uploader/destroy/{id}', [AizUploadController::class, 'destroy']);
Route::post('/aiz-uploader/get_file_by_ids', [AizUploadController::class, 'get_preview_files']);
Route::get('/aiz-uploader/download/{id}', [AizUploadController::class, 'attachment_download'])->name('download_attachment');


Route::get('/demo/cron_1', [DemoController::class, 'cron_1']);
Route::get('/demo/cron_2', [DemoController::class, 'cron_2']);
Route::get('/insert_trasnalation_keys', [DemoController::class, 'insert_trasnalation_keys']);
Route::get('/customer-products/admin', [SettingController::class, 'initSetting']);

Auth::routes(['register' => false]);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/quotation/list/details/{quotation_number}', [CategoryController::class, 'quotation_list_details'])->name('quotation.list.details');
Route::get('/quotationc/list/details/{quotation_number}', [CategoryController::class, 'quotation_list_detailsc'])->name('quotationc.list.details');


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {

    Route::get('/', [AdminController::class, 'admin_dashboard'])->name('admin.dashboard');

    Route::post('/language', [LanguageController::class, 'changeLanguage'])->name('language.change');

    Route::resource('categories', CategoryController::class);
    Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::get('/categories/destroy/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categories/featured', [CategoryController::class, 'updateFeatured'])->name('categories.featured');


    //quotation routes start
    Route::get('/quotation/home', [CategoryController::class, 'quotation_create'])->name('quotation.home');
    Route::get('/quotation/list', [CategoryController::class, 'quotation_list'])->name('quotation.list');
    Route::get('/quotation/list/delete/{quotation_number}', [CategoryController::class, 'delete_quotation'])->name('quotation.list.delete');
    Route::get('/quotation/list/edit/{quotation_number}', [CategoryController::class, 'edit_quotation'])->name('quotation.list.edit');
    Route::get('/quotation/search', [CategoryController::class, 'product_search'])->name('quotation.search');
    Route::post('/quotation/store', [CategoryController::class, 'storeQuotaiton'])->name('quotation.storeQuotaiton');
    Route::get('/quotation/list/details/{quotation_number}', [CategoryController::class, 'quotation_list_details'])->name('quotation.list.details');
    Route::get('/quotation/list/duplicate/{quotation_number}', [CategoryController::class, 'duplicate_quotation'])->name('quotation.list.duplicate');
    //quotation routes end

    //pos route start
    Route::get('/pos/dashboard',[CategoryController::class,'pos_dashboard'])->name('pos.dashboard');
    Route::get('/pos/search',[CategoryController::class,'pos_search'])->name('pos.search'); //ajax path
    Route::get('/pos/customer_search',[CategoryController::class,'customer_search'])->name('pos.customer_search');
    Route::post('/pos_sys/customer_store',[CategoryController::class,'customer_store'])->name('pos.customer_store');
    Route::get('/pos_sys/product_select_search',[CategoryController::class,'product_select_search'])->name('pos.product_select_search');
    Route::post('/pos_sys/create_order',[CategoryController::class,'create_order'])->name('pos.create_order');

    //pos route end
    Route::post('/pos_sys/match_inventory_product',[CategoryController::class,'match_inventory_product'])->name('pos.match_inventory_product');
    Route::get('/pos_sys/serial_search',[CategoryController::class,'match_product_serial'])->name('pos.serial_search');
   

    //inventory route start
    Route::get('pos_sys/inventory/home',[CategoryController::class,'inventory_index'])->name('pos.inventory.home');
    Route::post('pos_sys/inventory/check_duplicate',[CategoryController::class,'check_duplicate_product'])->name('pos.inventory.check_duplicate_product');
    Route::post('pos_sys/inventory/store',[CategoryController::class,'store_inventory_data'])->name('pos.inventory.store');
    Route::get('/pos/supplier_search',[CategoryController::class,'supplier_search'])->name('pos.inventory.supplier_search');
    Route::post('/pos/supplier_store',[CategoryController::class,'supplier_information_store'])->name('pos.inventory.supplier_store');
    //inventory route end

     //delivery booking start
     Route::get('/booking',[BookingController::class,'index'])->name('booking');
     Route::post('/booking',[BookingController::class,'store'])->name('booking');
     Route::get('/booking_list',[BookingController::class,'bookList'])->name('booking_list');
     Route::get('/edit/{i}',[BookingController::class,'edit'])->name('edit');
     Route::post('/update/{id}',[BookingController::class,'updatedata']);
     Route::post('delete/{id}', [BookingController::class,'delete'])->name('delete');
     Route::post('/book/search', [BookingController::class, 'Book_search'])->name('book.search'); //ajax path

     //test ajax
     Route::POST('/ajaxData',[BookingController::class,'getUserData']);
     Route::resource('/userData',BoookingController::class);

     //delivery booking end

    // Report Route Start
    Route::get('/summary_report',[CategoryController::class,'summary_report'])->name('summary.report');
    Route::post('/summary_report/bydate',[CategoryController::class,'summary_report_by_date'])->name('summary.report.bydate');
    Route::post('/summary_report/bymonth',[CategoryController::class,'summary_report_by_month'])->name('summary.report.bymonth');
    Route::post('/summary_report/byyear',[CategoryController::class,'summary_report_by_year'])->name('summary.report.byyear');
    Route::get('/product_sale_report',[CategoryController::class,'product_sale_report'])->name('product_sale.report');
    Route::post('/product_sale_report_date_Wise',[CategoryController::class,'product_sale_report_report_wise'])->name('product_sale.report_date_wise');
    Route::get('/stock_report',[CategoryController::class,'stock_report'])->name('stock_report');
    Route::get('/cash_report',[CategoryController::class,'cash_report'])->name('cash_report');
    // Report Route End

    //Employee Manage Route start
    Route::get('/employee_manage/attendance_generate_report_view',[EmployeeController::class,'attendence_report_generate_view'])->name('employee.attendance.report_generate_view');
    Route::post('/employee_manage/attendance_generate_report_store',[EmployeeController::class,'attendence_report_generate_excell_store'])->name('employee.attendance.report_generate_store');
    Route::get('/employee_manage/attendance_generate_report',[EmployeeController::class,'attendence_report_generate'])->name('employee.attendance.report_generate');

    //Employee Manage Route End

    Route::resource('brands', BrandController::class);
    Route::get('/brands/edit/{id}', [BrandController::class, 'edit'])->name('brands.edit');
    Route::get('/brands/destroy/{id}', [BrandController::class, 'destroy'])->name('brands.destroy');

    Route::resource('attributes', AttributeController::class)->except(['destroy']);
    Route::get('/attributes/edit/{id}', [AttributeController::class, 'edit'])->name('attributes.edit');

    Route::resource('attribute_values', AttributeValueController::class)->except(['destroy']);;
    Route::get('/attribute_values/edit/{id}', [AttributeValueController::class, 'edit'])->name('attribute_values.edit');




    // Product
    Route::resource('/product', ProductController::class);
    Route::group(['prefix' => 'product'], function () {
        Route::post('/new-attribte', [ProductController::class, 'new_attribute'])->name('product.new_attribute');
        Route::post('/get-attribte-value', [ProductController::class, 'get_attribute_values'])->name('product.get_attribute_values');
        Route::post('/new-option', [ProductController::class, 'new_option'])->name('product.new_option');
        Route::post('/get-option-choices', [ProductController::class, 'get_option_choices'])->name('product.get_option_choices');

        Route::post('/sku-combination', [ProductController::class, 'sku_combination'])->name('product.sku_combination');

        Route::get('/{id}/barcode', [ProductController::class, 'generate_barcode'])->name('product.barcode');
        Route::post('/create/barcode', [ProductController::class, 'create_barcode'])->name('product.create_barcode');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
        Route::get('/duplicate/{id}', [ProductController::class, 'duplicate'])->name('product.duplicate');
        Route::post('/update/{id}', [ProductController::class, 'update'])->name('product.update');
        Route::post('/published', [ProductController::class, 'updatePublished'])->name('product.published');
        Route::get('/destroy/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
        Route::get('/product_search/{search_item}', [ProductController::class, 'product_search'])->name('product.search');

        Route::post('/get_products_by_subcategory', [ProductController::class, 'get_products_by_subcategory'])->name('product.get_products_by_subcategory');
    });


    Route::resource('customers', CustomerController::class);
    Route::get('customers_ban/{customer}', [CustomerController::class, 'ban'])->name('customers.ban');
    Route::get('/customers/login/{id}', [CustomerController::class, 'login'])->name('customers.login');
    Route::get('/customers/destroy/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    Route::get('/newsletter', [NewsletterController::class, 'index'])->name('newsletters.index');
    Route::post('/newsletter/send', [NewsletterController::class, 'send'])->name('newsletters.send');
    Route::post('/newsletter/test/smtp', [NewsletterController::class, 'testEmail'])->name('test.smtp');

    Route::resource('profile', ProfileController::class);

    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/update/activation', [SettingController::class, 'updateActivationSettings'])->name('settings.update.activation');
    Route::get('/general-setting', [SettingController::class, 'general_setting'])->name('general_setting.index');
    Route::get('/payment-method', [SettingController::class, 'payment_method'])->name('payment_method.index');
    Route::get('/file_system', [SettingController::class, 'file_system'])->name('file_system.index');
    Route::get('/social-login', [SettingController::class, 'social_login'])->name('social_login.index');
    Route::get('/smtp-settings', [SettingController::class, 'smtp_settings'])->name('smtp_settings.index');
    Route::post('/env_key_update', [SettingController::class, 'env_key_update'])->name('env_key_update.update');
    Route::post('/payment_method_update', [SettingController::class, 'payment_method_update'])->name('payment_method.update');

    Route::get('/third-party-settings', [SettingController::class, 'third_party_settings'])->name('third_party_settings.index');
    Route::post('/google_analytics', [SettingController::class, 'google_analytics_update'])->name('google_analytics.update');
    Route::post('/google_recaptcha', [SettingController::class, 'google_recaptcha_update'])->name('google_recaptcha.update');
    Route::post('/facebook_chat', [SettingController::class, 'facebook_chat_update'])->name('facebook_chat.update');
    Route::post('/facebook_pixel', [SettingController::class, 'facebook_pixel_update'])->name('facebook_pixel.update');

    // Currency
    Route::get('/currency', [CurrencyController::class, 'index'])->name('currency.index');
    Route::post('/currency/update', [CurrencyController::class, 'updateCurrency'])->name('currency.update');
    Route::post('/your-currency/update', [CurrencyController::class, 'updateYourCurrency'])->name('your_currency.update');
    Route::get('/currency/create', [CurrencyController::class, 'create'])->name('currency.create');
    Route::post('/currency/store', [CurrencyController::class, 'store'])->name('currency.store');
    Route::post('/currency/currency_edit', [CurrencyController::class, 'edit'])->name('currency.edit');
    Route::post('/currency/update_status', [CurrencyController::class, 'update_status'])->name('currency.update_status');

    // Language
    Route::resource('/languages', LanguageController::class);
    Route::post('/languages/update_rtl_status', [LanguageController::class, 'update_rtl_status'])->name('languages.update_rtl_status');
    Route::post('/languages/update_language_status', [LanguageController::class, 'update_language_status'])->name('languages.update_language_status');
    Route::get('/languages/destroy/{id}', [LanguageController::class, 'destroy'])->name('languages.destroy');
    Route::post('/languages/key_value_store', [LanguageController::class, 'key_value_store'])->name('languages.key_value_store');

    // website setting
    Route::group(['prefix' => 'website', 'middleware' => ['permission:website_setup']], function () {

        Route::view('/header', 'backend.website_settings.header')->name('website.header');
        Route::view('/footer', 'backend.website_settings.footer')->name('website.footer');
        Route::view('/banners', 'backend.website_settings.banners')->name('website.banners');
        Route::view('/pages', 'backend.website_settings.pages.index')->name('website.pages');
        Route::view('/appearance', 'backend.website_settings.appearance')->name('website.appearance');
        Route::resource('custom-pages', PageController::class);
        Route::get('/custom-pages/edit/{id}', [PageController::class, 'edit'])->name('custom-pages.edit');
        Route::get('/custom-pages/destroy/{id}', [PageController::class, 'destroy'])->name('custom-pages.destroy');
    });

    Route::resource('roles', RoleController::class);
    Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::get('/roles/destroy/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::resource('staffs', StaffController::class);
    Route::get('/staffs/destroy/{id}', [StaffController::class, 'destroy'])->name('staffs.destroy');

    // Offers
    Route::resource('offers', OfferController::class);
    Route::get('/offers/destroy/{id}', [OfferController::class, 'destroy'])->name('offers.destroy');
    Route::post('/offers/update_status', [OfferController::class, 'update_status'])->name('offers.update_status');
    Route::post('/offers/product_discount', [OfferController::class, 'product_discount'])->name('offers.product_discount');
    Route::post('/offers/product_discount_edit', [OfferController::class, 'product_discount_edit'])->name('offers.product_discount_edit');

    //Subscribers
    Route::get('/subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');

    // Orders
    Route::resource('orders', OrderController::class);
    Route::post('/orders/update_delivery_status', [OrderController::class, 'update_delivery_status'])->name('orders.update_delivery_status');
    Route::post('/orders/update_payment_status', [OrderController::class, 'update_payment_status'])->name('orders.update_payment_status');
    Route::get('/orders/destroy/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/orders/invoice/{order_id}', [InvoiceController::class, 'invoice_download'])->name('orders.invoice.download');
    Route::get('/orders/print/{order_id}/{type?}', [InvoiceController::class, 'invoice_print'])->name('orders.invoice.print');
    Route::post('/orders/advance_payment/store', [CategoryController::class, 'store_advance_payment'])->name('orders.advance_payment');
    Route::get('/orders/advance_payment/check', [CategoryController::class, 'get_advance_payment'])->name('orders.advance_payment.check');
    Route::post('/orders/shipment_cost/update', [CategoryController::class, 'update_shipment_cost'])->name('orders.shiment_cost.update');
    Route::get('/orders/shipment_cost/get_shipping_cost', [CategoryController::class, 'get_shipment_cost'])->name('orders.shiment_cost.get');
    Route::post('/orders/update_delivery_status/cancel_cause', [CategoryController::class, 'order_cancel_cause'])->name('orders.update_delivery_status.order_cancel_cause');

    // purchase order invoice
    Route::get('/orders/purchase_order/view', [CategoryController::class, 'purchase_order_home'])->name('orders.purchase_order.home');
    Route::get('/orders/purchase_order/view/{invoice_number}', [CategoryController::class, 'purchase_order_view'])->name('orders.purchase_order.view');
    Route::get('/orders/purchase_order/delete', [CategoryController::class, 'purchase_order_delete'])->name('orders.purchase_order.delete');
   //Return product 
    Route::get('/orders/return_products/back', [OrderController::class, 'return_products'])->name('orders.return_products');
    Route::get('/orders/return_products/specefic_order', [OrderController::class, 'return_products_specefic_data'])->name('orders.return_products_specefic_data');
    Route::post('/orders/return_products/store_return_order', [OrderController::class, 'store_return_product'])->name('orders.store_return_order');
    Route::get('/orders/return_products/list', [OrderController::class, 'return_product_list'])->name('orders.return_product_list');
    Route::get('/orders/return_products/list/{invoice}', [OrderController::class, 'return_product_list_details'])->name('orders.return_product_list_details');
    Route::get('/orders/return_products/list_delete', [OrderController::class, 'return_product_list_delete'])->name('orders.return_product_list_delete');
    Route::get('/orders/cancel_cause/list', [OrderController::class, 'order_cancel_list'])->name('orders.cancel_list');
    Route::post('/orders/cancel_cause/search_by_date', [OrderController::class, 'order_cancel_search_by_date'])->name('orders.cancel_search_by_date');
    //user history route start
    Route::post('/user_history/order/adv_payment', [CategoryController::class, 'advance_paymnet_history'])->name('user_history.orders.adv_payment');
    Route::post('/user_history/order/payment_status', [CategoryController::class, 'order_payment_status_history'])->name('user_history.orders.payment_status');
    Route::post('/user_history/order/delivery_status', [CategoryController::class, 'order_delivery_status_history'])->name('user_history.orders.delivery_status');
    Route::post('/user_history/order/shipment_cost', [CategoryController::class, 'order_shipment_cost_history'])->name('user_history.orders.shipment_cost');
    Route::get('/user_history/list', [CategoryController::class, 'user_history_list'])->name('user_history.list');
    Route::post('/user_history/list/by_date', [CategoryController::class, 'user_history_list_by_date'])->name('user_history_date.list');
    
    //user edit history within product controller update method
    //user history route end

    //Coupons
    Route::resource('coupon', CouponController::class);
    Route::post('/coupon/get_form', [CouponController::class, 'get_coupon_form'])->name('coupon.get_coupon_form');
    Route::post('/coupon/get_form_edit', [CouponController::class, 'get_coupon_form_edit'])->name('coupon.get_coupon_form_edit');
    Route::get('/coupon/destroy/{id}', [CouponController::class, 'destroy'])->name('coupon.destroy');

    //Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/published', [ReviewController::class, 'updatePublished'])->name('reviews.published');

    Route::any('/uploaded-files/file-info', [AizUploadController::class, 'file_info'])->name('uploaded-files.info');
    Route::resource('/uploaded-files', AizUploadController::class);
    Route::get('/uploaded-files/destroy/{id}', [AizUploadController::class, 'destroy'])->name('uploaded-files.destroy');


    Route::resource('addons', AddonController::class);
    Route::post('/addons/activation', [AddonController::class, 'activation'])->name('addons.activation');

    //Shipping Configuration
    Route::get('/shipping_configuration', [SettingController::class, 'shipping_configuration'])->name('shipping_configuration.index');
    Route::post('/shipping_configuration/update', [SettingController::class, 'shipping_configuration_update'])->name('shipping_configuration.update');

    Route::resource('countries', CountryController::class);
    Route::post('/countries/status', [CountryController::class, 'updateStatus'])->name('countries.status');

    Route::resource('states', StateController::class);
    Route::post('/states/status', [StateController::class, 'updateStatus'])->name('states.status');

    Route::resource('cities', CityController::class);
    Route::get('/cities/edit/{id}', [CityController::class, 'edit'])->name('cities.edit');
    Route::get('/cities/destroy/{id}', [CityController::class, 'destroy'])->name('cities.destroy');
    Route::post('/cities/status', [CityController::class, 'updateStatus'])->name('cities.status');

    Route::resource('zones', ZoneController::class);
    Route::get('/zones/destroy/{id}', [ZoneController::class, 'destroy'])->name('zones.destroy');


    Route::view('/system/update', 'backend.system.update')->middleware('permission:system_update')->name('system_update');
    Route::view('/system/server-status', 'backend.system.server_status')->middleware('permission:server_status')->name('server_status');

    // tax
    Route::resource('taxes', TaxController::class);
    Route::post('/tax/status_update', [TaxController::class, 'updateStatus'])->name('tax.status_update');
    Route::get('/taxes/destroy/{id}', [TaxController::class, 'destroy'])->name('taxes.destroy');

    //chats
    Route::resource('chats', ChatController::class);
    Route::post('/refresh/chats', [ChatController::class, 'refresh'])->name('chats.refresh');
    Route::post('/chat-reply', [ChatController::class, 'reply'])->name('chats.reply');

    Route::get('/update/step1', [UpdateController::class, 'step1']);
});

Route::get('/addons/multivendor', [MultiVendorController::class, 'helloFromMultiVendor']);
