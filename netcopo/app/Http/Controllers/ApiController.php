<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{

	public function clearleads(Request $request)
    {
       
      $data = json_decode($request->getContent(), true);
      $listids=$data['lead_ids'];
      foreach ($listids as $listid) {
      	$result[]=$listid;
      }
      $result = json_encode($result);
      $response = [
            'data'    => $result,
        ];
        return response()->json($response, 200);
    }
}