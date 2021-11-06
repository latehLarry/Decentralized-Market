<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUIDs;
use App\Models\User;

class Conversation extends Model
{
    use HasFactory, UUIDs;
    
    /**
     * Get all messages from the conversation
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conversationMessages()
    {
    	return $this->hasMany(ConversationMessage::class, 'conversation_id', 'id')->orderBy('created_at', 'DESC');
    }

    /**
     * Returns the total number of messages from conversation
     * 
     * @return int
     */
    public function totalMessages()
    {
    	return $this->conversationMessages()->count();
    }

    /**
     * Total unread messages
     * 
     * @return int
     */
    public function unreadMessages()
    {
        #Get auth user
        $user = auth()->user();

    	return $this->conversationMessages()->where('read', false)
                                            ->where('issuer_id', '!=', $user->id)
                                            ->count();
    }

    /**
     * Mark all unread messages as read
     * 
     * @return App\Models\Conversation
     */
    public function markMessagesAsRead()
    {
        #Get auth user
        $user = auth()->user();
        
        return $this->conversationMessages()->where('read', false)
                                            ->where('issuer_id', '!=', $user->id)
                                            ->update(['read' => true]);
    }

    /**
     * Get the user who is chatting with the authenticated member
     * 
     * @return string
     */
    public function otherUser()
    {
        #Get auth user
        $user = auth()->user();

        if ($user->id === $this->issuer_id) {
            $otherUser = User::find($this->receiver_id);
        } elseif (!is_null($this->issuer_id) and $user->id === $this->receiver_id) {
            $otherUser = User::find($this->issuer_id);
        } else {
            $otherUser = $user->ghostUser();
        }

        return $otherUser->username;
    }
}
