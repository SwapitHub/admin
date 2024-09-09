<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
		|--------------------------------------------------------------------------
		| API Routes
		|--------------------------------------------------------------------------
		|
		| Here is where you can register API routes for your application. These
		| routes are loaded by the RouteServiceProvider within a group which
		| is assigned the "api" middleware group. Enjoy building your API!
		|
	*/
Route::any('ping', [App\Http\Controllers\API\RegisterController::class, 'ping']);

Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});
Route::prefix('v1')->group(function () {
    Route::post('user-registration', [App\Http\Controllers\API\RegisterController::class, 'registerUser']);
    Route::any('login', [App\Http\Controllers\API\RegisterController::class, 'login']);
    Route::post('password/email', [App\Http\Controllers\API\ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [App\Http\Controllers\API\ResetPasswordController::class, 'reset']);
    // Route::middleware('auth:sanctum')->group(function () {
    Route::any('country-list', [App\Http\Controllers\API\CountryController::class, 'index']);
    Route::any('getstate-list', [App\Http\Controllers\API\CountryController::class, 'fetchStateBasedOnCountry']);
    Route::any('overnight-shipping-charge', [App\Http\Controllers\API\CountryController::class, 'fetchOvernightShippingCharge']);
    Route::any('newsletter', [App\Http\Controllers\API\NewsletterController::class, 'index']);
    Route::get('token', [App\Http\Controllers\API\SiteinfoController::class, 'getCsrfToken']);
    Route::get('diamondshape', [App\Http\Controllers\API\DiamondController::class, 'diamondShape']);
    Route::any('contact', [App\Http\Controllers\API\FaqController::class, 'contactUs']);
    Route::get('languages', [App\Http\Controllers\API\LocalizationController::class, 'index']);
    Route::get('banners', [App\Http\Controllers\API\BannerController::class, 'index']);
    Route::get('siteinfo', [App\Http\Controllers\API\SiteinfoController::class, 'index']);
    Route::any('get-data', [App\Http\Controllers\API\SiteinfoController::class, 'getMetaData']);
    Route::get('menu', [App\Http\Controllers\API\MenuController::class, 'index']);
    Route::get('brand', [App\Http\Controllers\API\MenuController::class, 'brand']);
    Route::get('get-menu/{slug}', [App\Http\Controllers\API\MenuController::class, 'getMenuName']);
    Route::get('rings', [App\Http\Controllers\API\MenuController::class, 'rings']);
    Route::get('products', [App\Http\Controllers\API\ProductController::class, 'index']);
    Route::get('weddingband-products/', [App\Http\Controllers\API\WeddingBandProducts::class, 'index']);
    Route::get('product/{entity_id}', [App\Http\Controllers\API\ProductController::class, 'productDetails']);
    Route::get('faq', [App\Http\Controllers\API\FaqController::class, 'index']);
    Route::get('gemstone-faq', [App\Http\Controllers\API\FaqController::class, 'genstoneFaq']);
    Route::get('homecontent', [App\Http\Controllers\API\SiteinfoController::class, 'homeContent']);
    // Route::get('homecontent-bottom',[App\Http\Controllers\API\SiteinfoController::class,'ourstoryMission']);
    Route::get('metalcolor', [App\Http\Controllers\API\SiteinfoController::class, 'metalColor']);
    Route::post('findimage', [App\Http\Controllers\API\ProductController::class, 'getImageForListing']);
    Route::any('getactiveproduct', [App\Http\Controllers\API\ProductController::class, 'getActiveProductDetails']);
    Route::get('product-style', [App\Http\Controllers\API\ProductController::class, 'productStyle']);
    Route::any('cart', [App\Http\Controllers\API\CartController::class, 'index']);
    Route::get('homepage-data', [App\Http\Controllers\API\SiteinfoController::class, 'otherHomeData']);
    Route::get('footer-pages', [App\Http\Controllers\API\PageController::class, 'index']);
    Route::get('contact-faq', [App\Http\Controllers\API\PageController::class, 'contactFaq']);
    Route::get('diamonds', [App\Http\Controllers\API\DiamondController::class, 'getDiaminds']);
    Route::any('getcart-items', [App\Http\Controllers\API\CartController::class, 'cartItems']);
    Route::get('remove-cartitem/{id}', [App\Http\Controllers\API\CartController::class, 'removeCartItem']);
    Route::get('update-ring-size', [App\Http\Controllers\API\CartController::class, 'updateCart']);
    Route::any('user-account', [App\Http\Controllers\API\UserDashboardController::class, 'index']);
    Route::any('add_to_wishlist', [App\Http\Controllers\API\WishlistController::class, 'index']);
    Route::any('wishlist-items', [App\Http\Controllers\API\WishlistController::class, 'getWishlistItem']);
    Route::any('update_preferences/{id}', [App\Http\Controllers\API\UserDashboardController::class, 'updateUserData']);
    Route::get('remove_wishlist_item/{id}', [App\Http\Controllers\API\WishlistController::class, 'deleteItem']);
    Route::get('check_product_in_wishlist', [App\Http\Controllers\API\WishlistController::class, 'checkProductInWishlist']);
    Route::get('search', [App\Http\Controllers\API\ProductController::class, 'globleSearch']);
    Route::get('search-suggestion', [App\Http\Controllers\API\ProductController::class, 'searhSuggestion']);
    Route::get('gemstone-attributes', [App\Http\Controllers\API\GemstoneAttributeController::class, 'index']);
    Route::any('save-users-address', [App\Http\Controllers\API\AddressController::class, 'index']);
    Route::any('get-users-address', [App\Http\Controllers\API\AddressController::class, 'getUserAddress']);
    Route::get('get_product_price', [App\Http\Controllers\API\ProductController::class, 'fetchProductPriceing']);
    Route::any('checkout', [App\Http\Controllers\API\CheckOutController::class, 'checkout']);
    Route::get('order-history', [App\Http\Controllers\API\OrdersController::class, 'index']);
    Route::get('order-detail', [App\Http\Controllers\API\OrdersController::class, 'historyDetail']);
    Route::any('tokenize-card', [App\Http\Controllers\API\CheckOutController::class, 'tokenizeCard']);
    Route::post('create-charge', [App\Http\Controllers\API\CheckOutController::class, 'testCharge']);
    Route::get('widget/{name}', [App\Http\Controllers\API\PageController::class, 'widgetCallByName']);
    Route::get('check-postal-code/{code}', [App\Http\Controllers\API\CheckOutController::class, 'checkValidPosalCode']);
    Route::get('coveted-products/{type}', [App\Http\Controllers\API\ProductController::class, 'covetedProducts']);
    Route::get('/invoice/{order_id}', [App\Http\Controllers\InvoiceController::class, 'invoicePdf'])->name('sale.orders.invoice.download');
    Route::any('check', [App\Http\Controllers\API\MenuController::class, 'check']);
    Route::any('cms-metadata', [App\Http\Controllers\API\MenuController::class, 'cmsMetaData']);
    // });
});

	// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
		// return $request->user();
	// });
