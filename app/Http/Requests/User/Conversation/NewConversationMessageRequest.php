<?php

namespace App\Http\Requests\User\Conversation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use App\Tools\PGP;
use App\Models\{User,Conversation,ConversationMessage};

class NewConversationMessageRequest extends FormRequest
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
            'message' => 'required|max:1000',
            'encrypted' => 'required|boolean',
            //'captcha' => 'required|captcha'
        ];
    }

    /**
     * Get custom messages from requisition rules.
     * 
     * @return array
     */
    public function messages()
    {
        return [
            'captcha.captcha' => 'The captcha is incorrect' 
        ];
    }

    /**
     * Create new message
     * @param  Conversation 
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function new(Conversation $conversation)
    {
        #Get auth user
        $user = auth()->user();

        #Get receiver
        $receiver = User::where('username', $conversation->otherUser())->first();

        $conversationMessage = new ConversationMessage(); 
        $conversationMessage->issuer_id = $user->id; #The authenticated user who is the sender of the message
        $conversationMessage->receiver_id = $receiver->id;
        $conversationMessage->conversation_id = $conversation->id;
        $conversationMessage->message = $this->encrypted == true ? Crypt::encryptString(PGP::encryptMessage($receiver->pgp_key, $this->message)) 
                                                                 : Crypt::encryptString($this->message);
        $conversationMessage->save();

        return redirect()->route('conversationmessages', ['conversation' => $conversation->id]);
    }
}
