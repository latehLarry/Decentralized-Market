<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{Hash,Crypt};
use App\Http\Requests\Staff\NoticeRequest;
use App\Models\{User,Product,Dispute,Report,Notice,HelpRequest,Conversation,ConversationMessage};

class StaffController extends Controller
{
	/**
	 * Filter products HTTP requst
	 * @param  $productId
	 * @param  $sellerUsername
	 * 
	 * @return App\Models\Product
	 */
	private function filterProduct($productId = null, $sellerUsername = null)
	{
		$seller = User::where('username', $sellerUsername)->first();
		$product = Product::where('id', $productId)->first();

		if (is_null($sellerUsername) and is_null($productId)) {
			return Product::where('deleted', false)->paginate(25);
		} elseif (!is_null($seller) and !is_null($product)) {
			return Product::where('seller_id', $seller->id)->where('id', $product->id)->where('deleted', false)->paginate(25);
		} elseif (!is_null($seller) and is_null($productId)) {
			return Product::where('seller_id', $seller->id)->where('deleted', false)->paginate(25);
		} elseif (!is_null($sellerUsername) and is_null($seller)) {
			return Product::where('seller_id', 'undefined')->paginate(25);
		} elseif (!is_null($product) and is_null($sellerUsername)) {
			return Product::where('id', $product->id)->paginate(25);
		} elseif (!is_null($productId) and is_null($product)) {
			return Product::where('id', 'undefined')->paginate(25);
		}
	}

	/**
	 * Products view
	 * @param Request $request
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewProducts(Request $request)
	{
		return view('staff.products', [
			'filters' => $request->all(),
			'productId' => $request->product_id,
			'sellerUsername' => $request->seller_username,
			'products' => $this->filterProduct($request->product_id, $request->seller_username),
			'totalProducts' => Product::where('deleted', false)->count()
		]);
	}

	/**
	 * Mark to featured HTTP request
	 * @param  Product $product
	 * 
	 * @return Illuminate\Routing\Redirector
	 */
	public function putFeatured(Product $product)
	{
		if (!$product->featured) {
			$product->featured = true;
			session()->flash('success', 'Product marked as featured!');
		} else {
			session()->flash('success', 'Product excluded from the highlights!');
			$product->featured = false;
		}

		$product->save();

		return redirect()->route('staff.products');
	}

	/**
	 * Disputes view
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewDisputes()
	{
		return view('staff.disputes', [
			'disputes' => Dispute::paginate(25)
		]);
	}

	/**
	 * Reports view
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewReports()
	{
		return view('staff.reports', [
			'reports' => Report::paginate(25)
		]);
	}

	/**
	 * Delete report HTTP request
	 * @param  Report $reprot 
	 * 
	 * @return Illuminate\Routing\Redirector
	 */
	public function deleteReport(Report $report)
	{
		$report->delete();

		session()->flash('success', 'Report successfully removed!');
		return redirect()->back();
	}

	/**
	 * All notices view
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewNotices()
	{
		return view('staff.notices', [
			'notices' => Notice::paginate(25)
		]);
	}

	/**
	 * Add notice HTTP request
	 * @param  NoticeRequest $request 
	 * 
	 * @return App\Http\Requests\Staff\NoticeRequest
	 */
	public function postAddNotice(NoticeRequest $request)
	{
		try {
			return $request->add();
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Notice view
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewNotice(Notice $notice)
	{
		return view('staff.notice', [
			'notice' => $notice
		]);
	}

	/**
	 * Edit notice HTTP request
	 * @param  Notice        $notice 
	 * @param  NoticeRequest $request 
	 * 
	 * @return App\Http\Requests\Staff\NoticeRequest
	 */
	public function putEditNotice(Notice $notice, NoticeRequest $request)
	{
		try {
			return $request->edit($notice);
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
			return redirect()->back();
		}
	}

	/**
	 * Delete notice HTTP request
	 * @param  Notice $notice 
	 * 
	 * @return Illuminate\Routing\Redirector
	 */
	public function deleteNotice(Notice $notice)
	{
		$notice->delete();

		session()->flash('success', 'Notice successfully deleted!');
		return redirect()->route('staff.notices');
	}

	/**
	 * Help requests view 
	 * @param  Request $request
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewSupport(Request $request)
	{
		$status = $request->status;

		#Set helpRequest
		$helpRequests = null;

		if ($status == 'closed') {
			$helpRequests = HelpRequest::where('closed', true)->orderBy('closed')->paginate(1);
		} elseif ($status == 'open') {
			$helpRequests = HelpRequest::where('closed', false)->orderBy('closed')->paginate(25);
		} else {
			$helpRequests = HelpRequest::orderBy('closed')->paginate(25);
		}

		$filters = $request->all();

		return view('staff.support', [
			'filters' => $filters,
			'status' => $status,
			'totalHelpRequests' => HelpRequest::count(),
			'helpRequests' => $helpRequests
		]);
	}

	/**
	 * Delete help HTTP request
	 * @param  HelpRequest $helpRequest
	 * 
	 * @return Illuminate\Routing\Redirector
	 */
	public function deleteHelpRequest(HelpRequest $helpRequest)
	{
		$helpRequest->delete();

		return redirect()->route('staff.support');
	}

	/**
	 * Closed help HTTP request
	 * @param  HelpRequest $helpRequest
	 * 
	 * @return Illuminate\Routing\Redirector
	 */
	public function postCloseHelpRequest(HelpRequest $helpRequest)
	{
		$helpRequest->closed = true;
		$helpRequest->save();

		session()->flash('success', 'Help request closed successfully!');
		return redirect()->back();
	}

	/**
	 * Resolve dispute
	 * @param  Request $request
	 * @param  Dispute $dispute
	 * 
	 * @return Illuminate\Routing\Redirector
	 */
	public function postResolveDispute(Request $request, Dispute $dispute)
	{
		try {
			if ($request->winner != $dispute->buyer->id and $request->winner != $dispute->seller->id) {
				throw new \Exception('Choose a valid winner!');
			}

			$dispute->winner_id = $request->winner;
			$dispute->save();

			session()->flash('success', 'Successfully defined winner!');
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
		}

		return redirect()->route('order', ['order' => $dispute->order->id]);
	}

	/**
	 * Mass message view
	 * 
	 * @return Illuminate\Support\Facades\View
	 */
	public function viewMassMessage()
	{
		return view('staff.massmessage');
	}

	/**
	 * Send a message to selected groups
	 * 
	 * @param  Request $request
	 * @return Illuminate\Routing\Redirector
	 */
	public function postMassMessage(Request $request)
	{
		$request->validate([
			'message' => 'required|max:5000',
			'group' => 'required|array'
		]);

		try {
			if (!in_array('buyers', $request->group) and !in_array('sellers', $request->group) and !in_array('staff', $request->group)) {
				throw new \Exception('User Group is invalid!');
			}

			#Set receivers
			$receivers = new Collection();

			if (in_array('staff', $request->group)) {
				#Get staff
				$staff = User::where('admin', true)->orWhere('moderator', true)->get();

				$receivers = $receivers->merge($staff);
			}

			if (in_array('sellers', $request->group)) {
				#Get sellers
				$sellers = User::where('seller', true)->get();

				$receivers = $receivers->merge($sellers);
			}

			if (in_array('buyers', $request->group)) {
				#Get buyers
				$buyers = User::where('seller', false)->where('moderator', false)
													  ->where('admin', false)
													  ->get();

				$receivers = $receivers->merge($buyers);
			}

			foreach ($receivers as $receiver) {

				$conversation = Conversation::where('issuer_id', null)
											->where('receiver_id', $receiver->id)
											->first();

				if (is_null($conversation)) { 
					$conversation = new Conversation();
					$conversation->receiver_id = $receiver->id;
					$conversation->save();
				}

				$conversationMessage = new ConversationMessage();
	 			$conversationMessage->conversation_id = $conversation->id;
	 			$conversationMessage->issuer_id = auth()->user()->id;
				$conversationMessage->message = Crypt::encryptString($request->message);
				$conversationMessage->save();
			}

			session()->flash('success', 'Message sent to users!');
		} catch (\Exception $exception) {
			session()->flash('error', $exception->getMessage());
		}

		return redirect()->route('staff.massmessage');
	}
}
