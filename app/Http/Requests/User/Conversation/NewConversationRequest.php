<?php

namespace App\Http\Requests\User\Conversation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use App\Tools\PGP;
use App\Models\{User,Conversation,ConversationMessage};

class NewConversationRequest extends FormRequest
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
            'username' => 'required|exists:users,username',
            'message' => 'required|max:1000',
            'encrypted' => 'nullable|boolean',
            'captcha' => 'required|captcha'
        ];
    }

    /**
     * Get custom messages from requisition rules
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
     * New conversation
     * @return Illuminate\Routing\Redirector
     */
    public function new()
    {
        $issuer = auth()->user(); #The authenticated user is the sender
        $receiver = User::where('username', $this->username)->first(); #The informed user is the receiver

        if ($issuer == $receiver) {
            throw new \Exception('You can\'t send a message to yourself!');
        }

        #Check if the conversation with the informed user exists
        $conversation = Conversation::where(function($query) use($issuer,$receiver) {
            $query->where('issuer_id', $issuer->id);
            $query->where('receiver_id', $receiver->id);
        })->orWhere(function($query) use($issuer,$receiver) {
            $query->where('issuer_id', $receiver->id);
            $query->where('receiver_id', $issuer->id);
        })->first();

        #Creates a new conversation between the two users if it doesn't exist
        if (is_null($conversation)) {
            $conversation = new Conversation();
            $conversation->issuer_id = $issuer->id;
            $conversation->receiver_id = $receiver->id;
            $conversation->save();
        }

        $conversationMessage = new ConversationMessage(); 
        $conversationMessage->issuer_id = $issuer->id;
        $conversationMessage->receiver_id = $receiver->id;
        $conversationMessage->conversation_id = $conversation->id;
        $conversationMessage->message = $this->encrypted ? Crypt::encryptString(PGP::encryptMessage($receiver->pgp_key, $this->message)) 
                                                         : Crypt::encryptString($this->message);
        $conversationMessage->save();

        return redirect()->route('conversationmessages', ['conversation' => $conversation->id]);
    }
}
