<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use App\Models\{Product,Report};

class ReportRequest extends FormRequest
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
            'cause' => 'required',
            'other_cause' => 'nullable|max:20',
            'message' => 'nullable|max:500',
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
     * Database persist
     * @param  Product $product
     * 
     * @return Illuminate\Routing\Redirector
     */
    public function report(Product $product)
    {
        #Get auth user
        $user = auth()->user();

        $arrayCauses = config('general.reporting_causes');

        $report = Report::where('user_id', $user->id)->where('product_id', $product->id)->first();

        if ($user->id == $product->seller->id) {
            throw new \Exception('You cannot report your own product!');
        }

        if (!in_array($this->cause, array_keys($arrayCauses))) {
            throw new \Exception('The report cause is non-existent or unavailable!');
        }

        if (!is_null($report)) {
            throw new \Exception('You have an open complaint request for this product!');
        }
        
        $report = new Report();
        $report->user_id = $user->id;
        $report->product_id = $product->id;

        if ($this->cause == 'other' and !is_null($this->other_cause)) {
            $report->cause = 'other: '.$this->other_cause;
        } else {
            $report->cause = $arrayCauses[$this->cause];
        }

        $report->message = Crypt::encryptString($this->message);
        $report->save();

        session()->flash('success', 'Thanks for your report! We will analyze your request and take action if your complaint is valid.');
        return redirect()->route('report', ['product' => $product->id]);
    }
}
