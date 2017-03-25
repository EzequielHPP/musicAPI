<?php

namespace App\Http\Controllers\V1\Requests;

use App\Models\UserTokens;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AuthorizationHeader extends FormRequest
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
            //
        ];
    }
}
