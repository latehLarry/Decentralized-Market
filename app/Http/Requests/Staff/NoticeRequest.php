<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Notice;

class NoticeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user() && auth()->user()->isModerator() || auth()->user()->isAdmin();
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
            'notice' => 'required',
        ];
    }

    /**
     * Database persist
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function add()
    {
        $notice = new Notice();
        $notice->user_id = auth()->user()->id;
        $notice->title = $this->title;
        $notice->notice = $this->notice;
        $notice->save();

        session()->flash('success', 'Notice created successfully!');
        return redirect()->route('staff.notices');
    }

    /**
     * Database persist
     * @param  Notice $notice
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function edit(Notice $notice)
    {
        $notice->title = $this->title;
        $notice->notice = $this->notice;
        $notice->save();

        session()->flash('success', 'Notice edited successfully');
        return redirect()->route('staff.notices');   
    }
}
