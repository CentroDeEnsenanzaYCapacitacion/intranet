<?php

namespace App\Http\Controllers;

use App\Models\SysRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function getRequests()
    {
        $requests = SysRequest::whereNull('approved')->get();
        $old_requests = SysRequest::whereNotNull('approved')->get();

        return view('admin.requests.show', compact('requests','old_requests'));
    }

    public function updateRequest($request_id,$action){
        $request= SysRequest::find($request_id);
        if($action==="approve"){
            $request->approved = true;
        }else{
            $request->approved = false;
        }
        $request->save();

        return redirect()->route('admin.requests.show');
    }

    public function editRequest($request_id){
        $request = SysRequest::find($request_id);
        return view('admin.requests.edit',compact('request'));
    }

    public function changePercentage(Request $request,$request_id){
        $sysrequest = SysRequest::find($request_id);
        $array = explode("-",$sysrequest->description);
        $reason = $array[1];
        $sysrequest->description = $request->discount .' - '.$reason;

        $sysrequest->save();

        return redirect()->route('admin.requests.show');
    }
}
