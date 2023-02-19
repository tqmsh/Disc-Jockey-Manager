<?php
namespace App\Http\Library;

use Illuminate\Http\JsonResponse;

trait ApiHelpers
{
    protected function isStudent($user):bool
    {
        if(!empty($user)){
            if ($user->role == 3){
                return true;
            }
        }
    }
}
