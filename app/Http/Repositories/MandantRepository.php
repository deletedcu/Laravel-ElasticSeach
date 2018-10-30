<?php
namespace App\Http\Repositories;
/**
 * User: Marijan
 * Date: 14.06.2016.
 * Time: 08:11
 */

use DB;
use App\Mandant;
use App\User;

class MandantRepository
{
    /**
     * Merge two collections
     *
     * @return object array $array
     */
     public function phonelistSearch($request ){
        $query = Mandant::join('mandant_users','',0); 
        
        return $query->get();
     }
     
}
