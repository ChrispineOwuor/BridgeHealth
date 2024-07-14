<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use App\Models\Doctors;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\HealthRecord;
use App\Models\Appointment;
use Symfony\Component\HttpFoundation\Response;

class AdminsController extends Controller
{
    public function index()
    {
        $users = User::count();
        $appointments = Appointment::count();
        $recordCounts = HealthRecord::count();
        $patients = Patient::count();
        $doctors = Doctors::count();
        $admins = Admins::count();

        return response()->json(["data"=>compact("users", 'appointments', 'recordCounts', 'patients', 'doctors', 'admins')], Response::HTTP_OK);
    }
    public function show()
    {
        $users = User::all();
        return response()->json($users, Response::HTTP_OK);
    }
    // public function destroy($id)
    // {
    //     $user = User::findOrFail($id);
    //     $user->delete();

    //     return response()->json(['msg' => 'User deleted successfully'], Response::HTTP_OK);
    // }
}
