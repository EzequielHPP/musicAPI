<?php

namespace App\Http\Controllers\V1\Requests;

use App\Models\UserTokens;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CreateAlbum extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        $authorization = $request->header('Authorization');
        if ($authorization == null) {
            return false;
        }
        $token = UserTokens::where('token', $authorization)->firstOrFail();
        if ($token === null) {
            return false;
        }

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
            'name' => 'required|max:255',
            'release_date' => 'required|date',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'A name is required',
            'release_date.required' => 'A Release Date is required',
        ];
    }
}
