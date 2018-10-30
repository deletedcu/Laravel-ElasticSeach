<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\User;

class BenutzerPartnerRequest extends Request
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
           // Get user ID from url segment
            $userId = $this->segment(2);
            switch( $this->method() ){
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                
               return [
                    // 'username' => 'required|unique:users', // NEPTUN-774
                    'password' => 'required|min:6',
                    'password_repeat' => 'same:password',
                    'email' => 'email',
                    'email_private' => 'email',
                    'email_work' => 'email',
                    'picture' => 'image',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    // 'username' => 'required|unique:users,username,' . $userId, // NEPTUN-774
                    'password' => 'min:6',
                    'password_repeat' => 'same:password',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|email',
                    'email_private' => 'email',
                    'email_work' => 'email',
                    'picture' => 'image',
                ];
            }
            default:break;
        }
    }
}
