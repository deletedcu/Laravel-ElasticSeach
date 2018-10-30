<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DocumentRequest extends Request
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
                   'document_type_id' => 'required|integer',
                   'user_id' => 'required|integer',
                   'name' => 'required',
                   'owner_user_id' => 'integer',
                   'document_status_id' => 'required|integer',
                   'search_tags' => 'required',
                   'date_expired' => 'required|date',
                   'iso_category_id' => 'integer',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
               //
            }
            default:break;
        }
    
    }
}
