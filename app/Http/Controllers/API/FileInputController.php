<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class FileInputController extends Controller
{
    public function postDeletePicture(Request $request)
    {
        $output = array('uploaded' => 'OK' );
        return response()->json($output);
    }

    public function postUploadPicture(Request $request)
    {
        $output = array('uploaded' => 'OK' );
        return response()->json($output);
    }
}
