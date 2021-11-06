<?php

namespace App\Http\Requests\Support;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use App\Models\{HelpRequestReply,HelpRequest};

class CreateReplyHelpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'message' => 'required|max:1000',
        ];
    }

    /**
     * Database persist
     * @param  HelpRequest $helpRequest
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function new(HelpRequest $helpRequest)
    {
        #Get auth user
        $user = auth()->user();

        if ($helpRequest->closed == true) {
            throw new \Exception('This help request has already been marked closed, it is not possible to post a message!');
        }

        $replyHelpRequest = new HelpRequestReply();
        $replyHelpRequest->helprequest_id = $helpRequest->id;
        $replyHelpRequest->user_id = $user->id;
        $replyHelpRequest->message = Crypt::encryptString($this->message);
        $replyHelpRequest->save();

        session()->flash('success', 'Message created successfully!');
        return redirect()->route('helprequest', ['helpRequest' => $helpRequest->id]);
    }
}
