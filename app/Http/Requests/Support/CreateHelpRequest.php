<?php

namespace App\Http\Requests\Support;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use App\Models\{HelpRequest,HelpRequestReply};

class CreateHelpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:50',
            'message' => 'required|max:1000'
        ];
    }

    /**
     * Database persist
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function new()
    {
        #Get auth user
        $user = auth()->user();

        if ($user->helpRequests->where('closed', false)->count() > 0) {
            throw new \Exception('You already have an open call for help!');
        }

        #Create new help request
        $helpRequest = new HelpRequest();
        $helpRequest->user_id = $user->id;
        $helpRequest->title = Crypt::encryptString($this->title);
        $helpRequest->save();

        #Create first message from help
        $helpRequestReply = new HelpRequestReply();
        $helpRequestReply->helprequest_id = $helpRequest->id;
        $helpRequestReply->user_id = $user->id;
        $helpRequestReply->message = Crypt::encryptString($this->message);
        $helpRequestReply->save();

        session()->flash('success', 'Help request created successfully!');
        return redirect()->route('helprequest', ['helpRequest' => $helpRequest->id]);
    }
}
