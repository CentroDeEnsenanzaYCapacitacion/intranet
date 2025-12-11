<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SysRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function __construct()
    {

        $this->middleware('role:1,2,3,5');
    }

    public function getRequests()
    {
        $user = auth()->user();

        if ($user->role_id === 1) {
            // Admin ve todas las solicitudes
            $requests = SysRequest::whereNull('approved')->get();
            $old_requests = SysRequest::whereNotNull('approved')
                ->where('updated_at', '>=', now()->subMonth())
                ->orderBy('updated_at', 'desc')
                ->get();
        } else {
            // Otros roles solo ven solicitudes de su plantel
            $requests = SysRequest::whereNull('approved')
                ->whereHas('user', function($query) use ($user) {
                    $query->where('crew_id', $user->crew_id);
                })
                ->get();
            $old_requests = SysRequest::whereNotNull('approved')
                ->where('updated_at', '>=', now()->subMonth())
                ->whereHas('user', function($query) use ($user) {
                    $query->where('crew_id', $user->crew_id);
                })
                ->orderBy('updated_at', 'desc')
                ->get();
        }

        return view('admin.requests.show', compact('requests','old_requests'));
    }

    public function updateRequest($request_id,$action){
        if (auth()->user()->role_id !== 1) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        $sysrequest = SysRequest::find($request_id);
        if($action==="approve"){
            $sysrequest->approved = true;

            if ($sysrequest->request_type_id == 3 && $sysrequest->student_id) {
                preg_match('/Nueva colegiatura: \$([\d,\.]+)/', $sysrequest->description, $matches);
                if (isset($matches[1])) {
                    $newTuition = str_replace(',', '', $matches[1]);
                    $student = Student::find($sysrequest->student_id);
                    $student->tuition = $newTuition;
                    $student->save();
                }
            }
        }else{
            $sysrequest->approved = false;
        }
        $sysrequest->save();

        return redirect()->route('admin.requests.show');
    }

    public function editRequest($request_id){
        if (auth()->user()->role_id !== 1) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        $request = SysRequest::find($request_id);
        return view('admin.requests.edit',compact('request'));
    }

    public function changePercentage(Request $request,$request_id){
        if (auth()->user()->role_id !== 1) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        $sysrequest = SysRequest::find($request_id);
        $array = explode("-",$sysrequest->description);
        $reason = $array[1];
        $sysrequest->description = $request->discount .' - '.$reason;

        $sysrequest->save();

        return redirect()->route('admin.requests.show');
    }

    public function changeTuition(Request $request, $request_id)
    {
        if (auth()->user()->role_id !== 1) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        $request->validate([
            'new_tuition' => 'required|numeric|min:0.01'
        ]);

        $sysrequest = SysRequest::find($request_id);

        $student = Student::find($sysrequest->student_id);
        $student->tuition = $request->new_tuition;
        $student->save();

        $sysrequest->approved = true;
        $sysrequest->save();

        return redirect()->route('admin.requests.show');
    }
}
