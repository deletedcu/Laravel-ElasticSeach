<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

use App\Mandant;



class MandantRequest extends Request
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
                    'name' => 'required',
                    'mandant_number'  => 'required|unique:mandants',
                    'mandant_id_hauptstelle' => 'required_if:hauptstelle,0',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => 'required',
                    'mandant_number'  => 'required',
                    'mandant_id_hauptstelle' => 'required_if:hauptstelle,0',
                    'mandant_id' => 'integer',
                    'email' => 'email',
                ];
            }
            default:break;
        }
    }
}
