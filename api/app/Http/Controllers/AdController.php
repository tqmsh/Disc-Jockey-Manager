<?php

namespace App\Http\Controllers;

use App\Models\Campaign;

class AdController extends Controller
{
    public function impression($id){
        Campaign::find($id)->update([
                "impressions" => Campaign::find($id)->impressions + 1]
        );
    }
    public function click($id){
        Campaign::find($id)->update([
                "clicks" => Campaign::find($id)->clicks + 1]
        );
    }
}
