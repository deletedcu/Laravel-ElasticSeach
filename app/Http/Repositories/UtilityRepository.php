<?php
namespace App\Http\Repositories;
/**
 * Created by PhpStorm.
 * User: Marijan
 * Date: 25.04.2016.
 * Time: 02:42
 */

use Auth;
use DB;
use App\MandantUser;
use App\UserSettings;
use App\MandantUserRole;
use App\GlobalSettings;


class UtilityRepository
{
    /**
     * Universal user has permissions check
     * @param array $userArray
     * @return bool 
     */
    public function universalHasPermission( $userArray=array(), $withAdmin=true, $debug=false){
        $uid = Auth::user()->id;
      
        $mandantUsers =  MandantUser::where('user_id',$uid)->get();
        $hasPermission = false;   
        foreach($mandantUsers as $mu){
            $userMandatRoles = MandantUserRole::where('mandant_user_id',$mu->id)->get();
            foreach($userMandatRoles as $umr){
               
               
                if($withAdmin == true){
                    if( $umr->role_id == 1 || in_array($umr->role_id, $userArray) )
                        $hasPermission = true;
                    
                }
                else{
                    if( in_array($umr->role_id, $userArray) == true ){
                        
                        $hasPermission = true;
                    }
                    
                }
                   
            }
        }
        
        return $hasPermission;
    }
    
    /**
     * Check if Mandant has Wiki permission
     * @return bool
     */
    static function getMandantWikiPermission(){
        $user = Auth::user();
        $mandant = MandantUser::where('user_id', $user->id)->first()->mandant;
        return (bool)$mandant->rights_wiki;
    }
    
    /**
     * Get telephone list view settings
     * @return array
     */
    static function getPhonelistSettings(){
        
        $visible = array();
        $settingsUID = Auth::user()->id;
        $settingsCategory = 'telefonliste';
        $settingsName = 'visibleColumns';
        
        // Define default display options for user if they dont exist
        $settingsOld = UserSettings::where('user_id', $settingsUID)
            ->where('category', $settingsCategory)->where('name', $settingsName)->get();
        
        if(count($settingsOld) == 0){
            for($i = 1; $i <= 10; $i++)
                UserSettings::create(['user_id' => $settingsUID, 'category' => $settingsCategory, 'name' => $settingsName, 'value' => 'col'.$i ]);
        }
        
        // Retrieve users display options
        $userSettings = UserSettings::where('user_id', $settingsUID)
            ->where('category', $settingsCategory)->where('name', $settingsName)->get();
        
        foreach($userSettings as $setting){
            $visible[$setting->value] = true;
        }
        
        return $visible;
    }
    
    /**
     * Get global settings for default user roles
     * @return array
     */
    static function getDefaultUserRoleSettings(){
        
        $defaultRoles = array(16); // Wiki Leser
        $existingRoles = GlobalSettings::where('category', 'users')->where('name', 'defaultRoles')->pluck('value')->toArray();
        if($existingRoles) $defaultRoles = $existingRoles;
        
        return $defaultRoles;
    }
    
     
}
