<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,UserController,SellerController,ProductController,IndexController,StaffController,AdminController,OrderController};

####### AUTH
Route::middleware('guest')->group(function() {
	Route::get('/login', [AuthController::class, 'viewLogin'])->name('login');
	Route::post('/login', [AuthController::class, 'postLogin'])->name('post.login');

	Route::get('/register', [AuthController::class, 'viewRegister'])->name('register');
	Route::post('/register', [AuthController::class, 'postRegister'])->name('post.register');

	Route::get('/reset/password', [AuthController::class, 'viewResetPassword'])->name('resetpassword');
	Route::post('/reset/password', [AuthController::class, 'postResetPassword'])->name('post.resetpassword');
	Route::put('/reset/password', [AuthController::class, 'putResetPassword'])->name('put.resetpassword');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/master-key', [AuthController::class, 'viewMasterKey'])->name('masterkey');

Route::get('/verify-login', [AuthController::class, 'viewVerifyLogin'])->name('verifylogin');
Route::post('/verify-login', [AuthController::class, 'postVerifyLogin'])->name('post.verifylogin');
####### END AUTH

####### SET PGP KEY
Route::middleware(['auth', 'two_factor_authentication'])->group(function() {
	Route::get('/set/pgp-key', [UserController::class, 'viewSetPGPKey'])->name('setpgpkey');
	Route::post('/set/pgp-key', [UserController::class, 'postSetPGPKey'])->name('post.setpgpkey');
	Route::put('/set/pgp-key', [UserController::class, 'putSetPGPKey'])->name('put.setpgpkey');
	Route::get('/set/pgp-key/cancel', [UserController::class, 'cancelSetPGPKey'])->name('cancelsetpgpkey');
});
####### END SET PGP KEY

Route::middleware(['auth', 'pgp_key', 'two_factor_authentication'])->group(function() {
	####### INDEX
	Route::get('/', [IndexController::class, 'viewHome'])->name('home');
	Route::get('/category/{slug}', [IndexController::class, 'viewCategory'])->name('category');

	Route::get('/product/{product}', [IndexController::class, 'viewProduct'])->name('product');

	Route::get('/cart', [IndexController::class, 'viewCart'])->name('cart');
	Route::post('/cart/add/product/{product}', [IndexController::class, 'postAddToCart'])->name('post.addtocart');
	Route::post('/cart/clear', [IndexController::class, 'postClearCart'])->name('post.clearcart');
	Route::post('/cart/remove/product/{product}', [IndexController::class, 'postRemoveToCart'])->name('post.removetocart');

	Route::post('/checkout', [IndexController::class, 'postCheckout'])->name('post.checkout');

	Route::get('/seller/profile/{seller}', [IndexController::class, 'viewSeller'])->name('seller');

	Route::post('/fan/{seller}', [IndexController::class, 'postFan'])->name('post.fan');

	Route::get('/about', [IndexController::class, 'viewAbout'])->name('about');

	Route::get('/product/report/{product}', [IndexController::class, 'viewReport'])->name('report');
	Route::post('/product/report/{product}', [IndexController::class, 'postReport'])->name('post.report');

	Route::get('/notices/diary', [IndexController::class, 'viewNoticeDiary'])->name('noticediary');
	Route::get('/notice/{notice}', [IndexController::class, 'viewNotice'])->name('notice');

	Route::get('/support', [IndexController::class, 'viewSupport'])->name('support');
	Route::post('/support', [IndexController::class, 'postCreateHelpRequest'])->name('post.createhelprequest');
	Route::get('/support/help-request/{helpRequest}', [IndexController::class, 'viewHelpRequest'])->name('helprequest');
	Route::post('/support/help-request/{helpRequest}', [IndexController::class, 'postHelpRequest'])->name('post.helprequest'); #Create new reply

	Route::get('/result', [IndexController::class, 'viewResult'])->name('result');
	####### END INDEX

	####### USER
	Route::prefix('user')->group(function() {
		Route::get('/settings', [UserController::class, 'viewSettings'])->name('settings');
		Route::put('/settings/change-avatar', [UserController::class, 'putChangeAvatar'])->name('put.changeavatar');
		Route::put('/settings/change-password', [UserController::class, 'putChangePassword'])->name('put.changepassword');
		Route::put('/settings/change-pin', [UserController::class, 'putChangePIN'])->name('put.changepin');
		Route::post('/settings/change-currency', [UserController::class, 'postChangeCurrency'])->name('post.changecurrency');
		Route::put('/settings/backup-wallet', [UserController::class, 'putChangeBackupWallet'])->name('put.changebackupwallet');
		Route::put('/settings/change-two-factor-authentication', [UserController::class, 'putTwoFactorAuthentication'])->name('put.changetwofactorauthentication');
		Route::post('/settings/change-pgp-key', [UserController::class, 'postChangePGPKey'])->name('post.changepgpkey'); #Create verification code
		Route::put('/settings/change-pgp-key', [UserController::class, 'putChangePGPKey'])->name('put.changepgpkey'); #Confirm verification code and change PGP key
		Route::get('/settings/cancel-pgp-key-change', [UserController::class, 'cancelPGPKeyChange'])->name('cancelpgpkeychange'); #Cancel pgp key change request
		
		Route::get('/index', [UserController::class, 'viewAccountIndex'])->name('accountindex');
		Route::post('/index/transfer', [UserController::class, 'postTransfer'])->name('post.transfer');
		Route::post('/index/withdraw', [UserController::class, 'postWithdraw'])->name('post.withdraw');

		Route::get('/conversations', [UserController::class, 'viewConversations'])->name('conversations');
		Route::post('/conversations', [UserController::class, 'postNewConversation'])->name('post.conversations'); #Create new covnersation
		Route::get('/conversations/{conversation}/messages', [UserController::class, 'viewConversationMessages'])->name('conversationmessages');
		Route::post('/conversations/{conversation}/messages', [UserController::class, 'postNewConversationMessage'])->name('post.conversationmessages'); #Create new message

		Route::get('/favorites', [UserController::class, 'viewFavorites'])->name('favorites');
		Route::post('/favorites/{product}', [UserController::class, 'postFavorites'])->name('post.favorites'); #Add or remove product to favorites

		Route::get('/orders/{status}', [UserController::class, 'viewOrders'])->name('orders');

		Route::get('/history', [UserController::class, 'viewHistory'])->name('history'); #History of transitions
		Route::delete('/history', [UserController::class, 'clearHistory'])->name('clear.history'); #Clear history

		Route::get('/statistics', [UserController::class, 'viewStatistics'])->name('statistics');

		Route::get('/affiliate', [UserController::class, 'viewAffiliate'])->name('affiliate');

		Route::get('/notifications', [UserController::class, 'viewNotifications'])->name('notifications');
		Route::delete('/notifications', [UserController::class, 'deleteNotifications'])->name('delete.notifications');

		Route::get('/pgp-key', [UserController::class, 'viewPgpKey'])->name('pgpkey');
	});
	####### END USER

	####### SELLER
	Route::prefix('seller')->group(function() {
		Route::get('/become', [SellerController::class, 'viewBecome'])->name('becomeseller');
		Route::post('/become', [SellerController::class, 'postBecome'])->name('post.becomeseller');

		Route::middleware('seller')->group(function() {
			Route::get('/dashboard', [SellerController::class, 'viewDashboard'])->name('seller.dashboard');
			Route::put('/dashboard', [SellerController::class, 'putDashboard'])->name('put.seller.dashboard'); #Edit public profile

			Route::get('/sales/{status}', [SellerController::class, 'viewSales'])->name('sales');
		});
	});
	####### END SELLER

	####### STAFF
	Route::group(['middleware' => 'staff', 'prefix' => 'staff'], function() {
		Route::get('/notices', [StaffController::class, 'viewNotices'])->name('staff.notices');
		Route::post('/notices', [StaffController::class, 'postAddNotice'])->name('post.staff.addnotice');
		Route::get('/notices/{notice}', [StaffController::class, 'viewNotice'])->name('staff.notice');
		Route::put('/notices/{notice}/edit', [StaffController::class, 'putEditNotice'])->name('put.staff.editnotice');
		Route::delete('/notices/{notice}/delete', [StaffController::class, 'deleteNotice'])->name('delete.staff.notice');

		Route::get('/products', [StaffController::class, 'viewProducts'])->name('staff.products');
		Route::put('/products/{product}/featured', [StaffController::class, 'putFeatured'])->name('put.staff.featuredproduct'); #Mark product to featured

		Route::get('/disputes', [StaffController::class, 'viewDisputes'])->name('staff.disputes');

		Route::get('/reports', [StaffController::class, 'viewReports'])->name('staff.reports');
		Route::delete('/reports/{report}/delete', [StaffController::class, 'deleteReport'])->name('delete.staff.report');

		Route::get('/support', [StaffController::class, 'viewSupport'])->name('staff.support');
		Route::post('/support/{helpRequest}/close', [StaffController::class, 'postCloseHelpRequest'])->name('post.staff.closehelprequest');
		Route::delete('/support/{helpRequest}/delete', [StaffController::class, 'deleteHelpRequest'])->name('delete.staff.helprequest');

		Route::post('/orders/{dispute}/resolve/dispute', [StaffController::class, 'postResolveDispute'])->name('post.resolvedispute');

		Route::get('/mass-message', [StaffController::class, 'viewMassMessage'])->name('staff.massmessage');
		Route::post('/mass-message', [StaffController::class, 'postMassMessage'])->name('post.staff.massmessage');
	});

	Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function() {
		Route::get('/dashboard', [AdminController::class, 'viewDashboard'])->name('admin.dashboard');
		Route::put('/dashboard', [AdminController::class, 'putDashboard'])->name('put.admin.dashboard');
		Route::post('/exit-button', [AdminController::class, 'postExitButton'])->name('post.admin.exitbutton');

		Route::get('/categories', [AdminController::class, 'viewCategories'])->name('admin.categories');
		Route::post('/categories', [AdminController::class, 'postAddCategory'])->name('post.admin.addcategories');
		Route::get('/categories/{category}', [AdminController::class, 'viewCategory'])->name('admin.category');
		Route::put('/categories/{category}/edit', [AdminController::class, 'putEditCategory'])->name('put.admin.editcategory');
		Route::get('/categories/{category}/delete', [AdminController::class, 'deleteCategory'])->name('delete.admin.category');

		Route::get('/users', [AdminController::class, 'viewUsers'])->name('admin.users');
		Route::get('/users/{user}', [AdminController::class, 'viewUser'])->name('admin.user');
		Route::put('/users/{user}/edit', [AdminController::class, 'putEditUser'])->name('put.admin.edituser');
	});
	####### END STAFF

	####### PRODUCT
	Route::get('/{section}/product/images/{product?}', [ProductController::class, 'viewImages'])->name('images');
	Route::post('/{section}/product/images/{product?}', [ProductController::class, 'postImage'])->name('post.image');
	Route::post('/{section}/product/images/delete/{image}/{product?}', [ProductController::class, 'postDeleteImage'])->name('post.deleteimage'); 

	Route::get('/{section}/product/offers/{product?}', [ProductController::class, 'viewOffers'])->name('offers');
	Route::post('/{section}/product/offers/{product?}', [ProductController::class, 'postOffer'])->name('post.offer');
	Route::post('/{section}/product/offers/delete/{offer}/{product?}', [ProductController::class, 'postDeleteOffer'])->name('post.deleteoffer');

	Route::get('/{section}/product/deliveries/{product?}', [ProductController::class, 'viewDeliveries'])->name('deliveries');
	Route::post('/{section}/product/deliveries/{product?}', [ProductController::class, 'postDelivery'])->name('post.delivery');
	Route::post('/{section}/product/deliveries/delete/{delivery}/{product?}', [ProductController::class, 'postDeleteDelivery'])->name('post.deletedelivery');

	Route::get('/{section}/product/informations/{product?}', [ProductController::class, 'viewInformations'])->name('informations');
	Route::post('/{section}/product/informations/{product?}', [ProductController::class, 'postInformations'])->name('post.informations');

	Route::post('/product/delete/{product}', [ProductController::class, 'postDeleteProduct'])->name('post.deleteproduct'); #Delete product
	####### END PRODUCT

	####### ORDER
	Route::get('/orders/{order}/view', [OrderController::class, 'viewOrder'])->name('order');
	Route::post('/orders/{feedback}/submit', [OrderController::class, 'postFeedback'])->name('post.feedback');
	Route::post('/orders/{dispute}/dispute/message', [OrderController::class, 'postCreateDisputeMessage'])->name('post.createdisputemessage');
	Route::post('/orders/{order}/finalizearly', [OrderController::class, 'postFinalizearly'])->name('post.finalizearly');
	Route::post('/orders/{order}/status/{status}', [OrderController::class, 'postChangeOrderStatus'])->name('post.changeorderstatus');
	####### END ORDER
});