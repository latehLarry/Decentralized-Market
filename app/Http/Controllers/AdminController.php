<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Staff\Admin\{UserRequest,CategoryRequest};
use App\Models\{User,Product,Category,Conversation};

class AdminController extends Controller
{
	/**
	 * Dashboard view
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewDashboard()
	{
		return view('staff.admin.dashboard', [
			'totalUsers' => User::count(),
			'totalSellers' => User::where('seller', true)->count(),
			'totalEmployeers' => User::where('moderator', true)->orWhere('admin', true)->count(),
			'totalProducts' => Product::count(),
			'totalMoneros' => \Monerod::getBalance(),
			'sellerFee' => config('general.seller_fee'),
			'dreadForumLink' => config('general.dread_forum_link'),
			'wikiLink' => config('general.wiki_link')
		]);
	}

	/**
	 * Edit configs 
	 * @param  $namespace
	 * @param  $newValue 
	 * @param  $config 
	 * @param  $archive
	 * 
	 * @return archive
	 */
	private function editConfig($namespace, $newValue, $config, $archive)
	{
		config([$namespace => $newValue]);
		$text = '<?php return '.var_export(config($config), true).';';
		file_put_contents(config_path($archive), $text);
	}

	/**
	 * Dashboard HTTP request
	 * @param  Request $request
	 * 
	 * @return Illuminate\Routing\Redirector
	 */
	public function putDashboard(Request $request)
	{
		$request->validate([
			'seller_fee' => 'required|numeric|min:1',
			'dread_forum_link' => 'required',
			'wiki_link' => 'required'
		]);

		#Change the fee amount to become a seller
		$this->editConfig('general.seller_fee', $request->seller_fee, 'general', 'general.php');

		#Edit footer links
		$this->editConfig('general.dread_forum_link', $request->dread_forum_link, 'general', 'general.php');
		$this->editConfig('general.wiki_link', $request->wiki_link, 'general', 'general.php');

		session()->flash('success', 'Market settings have been changed successfully!');
		return redirect()->route('admin.dashboard');
	}

	/**
	 * Exit button HTTP request
	 * 
	 * @return Illuminate\Routing\Redirector
	 */
	public function postExitButton()
	{
		#Get all users from market
		$users = User::get();

		#Transfer the entire user account balance to your backup wallet
		foreach ($users as $user) {
			if (!is_null($user->backup_monero_wallet)) {

				#Get account TAG from user
				$accountTag = $user->id;

				#Get user balance
				$balance = \Monerod::getBalance($accountTag);

				\Monerod::transfer($balance, $user->backup_monero_wallet, $accountTag);
			}
		}

		$conversations = Conversation::get();

		#Delete all conversations
		foreach ($conversations as $conversation) {
			$conversation->delete();
		}

		session()->flash('success', 'Sensitive information destroyed and money transferred successfully!');
		return redirect()->route('admin.dashboard');
	}

	/**
	 * Search users
	 * @param  $username 
	 * @param  $role     
	 * 
	 * @return App\Models\User;
	 */
	private function searchUsers($username = null, $role = null)
	{
		#Roles
		$roles = ['seller', 'moderator', 'admin'];

		#Set role filter default value
		$roleFilter = null;

		if ($role == 'seller') {
			$roleFilter = 'seller';
		} elseif ($role == 'moderator') {
			$roleFilter = 'moderator';
		} elseif ($role == 'admin') {
			$roleFilter = 'admin';
		} else {
			$roleFilter = 'all';
		}

		if (in_array($roleFilter, $roles)) { 
			$users = User::where('username', 'LIKE', "%$username%")->where($roleFilter, true);
		} else {
			$users = User::where('username', 'LIKE', "%$username%");
		}

		return $users->orderBy('created_at', 'DESC')->paginate(25);
	}

	/**
	 * Users view
	 * @param Request $request
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewUsers(Request $request)
	{
		$users = $this->searchUsers($request->username, $request->role);

		return view('staff.admin.users', [
			'filters' => $request->all(),
			'username' => $request->username,
			'role' => $request->role,
			'users' => $users,
			'totalUsers' => User::count()
		]);
	}

	/**
	 * User view
	 * @param  User $user
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewUser(User $user) 
	{
		return view('staff.admin.user', [
			'user' => $user
		]);
	}

	/**
	 * Edit user HTTP request
	 * @param  UserRequest $request
	 * @param  User 	   $user
	 * 
	 * @return App\Http\Requests\Staff\Admin\UserRequest
	 */
	public function putEditUser(UserRequest $request, User $user)
	{
		try {
			return $request->edit($user);
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Categories view
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewCategories()
	{
		return view('staff.admin.categories', [
			'allCategories' => Category::get(),
			'rootsCategories' => Category::roots()
		]);
	}

	/**
	 * Add category request
	 * @param  CategoryRequest $request
	 * 
	 * @return App\Http\Requests\Staff\Admin\CategoryRequest
	 */
	public function postAddCategory(CategoryRequest $request)
	{
		try {
			return $request->add();
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
			return redirect()->back();
		}		
	}

	/**
	 * Edit category view
	 * @param  Category $category 
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewCategory(Category $category)
	{
		return view('staff.admin.category', [
			'allCategories' => Category::get(),
			'category' => $category
		]);
	}

	/**
	 * Edit user HTTP request
	 * @param  CategoryRequest $request
	 * @param  Category 	   $category
	 * 
	 * @return App\Http\Requests\Staff\Admin\CategoryRequest
	 */
	public function putEditCategory(CategoryRequest $request, Category $category)
	{
		try {
			return $request->edit($category);
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Delete category HTTP request
	 * @param  Category $category
	 * 
	 * @return Illuminate\Routing\Redirector
	 */
	public function deleteCategory(Category $category)
	{
		try {
			if ($category->totalProducts() > 0) {
				throw new \Exception('This category has products, you cannot delete it!');
			}

			$category->delete();

			session()->flash('success', 'Category successfully deleted!');
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
		}

		return redirect()->route('admin.categories');
	}
}
