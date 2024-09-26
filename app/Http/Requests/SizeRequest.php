<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SizeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required|min:6|max:12'
        ];
    }

    public function messages(){
        return [
            'name.required'=>"Name không được để trống!",
            'email.required'=>"Email không được để trống!",
            'password.required'=>"Password không được để trống!",
            'email.email'=>"Cần nhập vào là email!",
            'password.min'=>"Password cần ít nhất 6 kí tự!",
            'password.max'=>"Password nhiều nhất 12 kí tự!"
        ];
    }
}
