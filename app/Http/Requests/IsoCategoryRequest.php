<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class IsoCategoryRequest extends Request
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
        switch( $this->method() ){
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
               {
               
               return [
                    // 'name' => 'required|unique:iso_categories',
                    'name' => 'required',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => 'required',
                ];
            }
            default:break;
        }
    }
}
