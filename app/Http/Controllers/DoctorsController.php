<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctors;
use App\Models\HealthRecord;
use App\Models\Patient;
use App\Models\Recommendation;
use App\Models\Symptom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DoctorsController extends Controller
{
    public function addSymptom(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Symptom::create($validatedData);

        return response()->json([
            'msg' => 'Symptom created successfully',
        ], Response::HTTP_OK);
    }

    public function recommend(Request $request)
    {
        $validatedData = $request->validate([
            'health_record_id' => 'required|exists:health_records,id',
            'remarks' => 'required|string',
        ]);

        $doctor = Doctors::where('user_id', Auth::id())->first();
        Recommendation::create([
            'doctor_id' => $doctor->id,
            'health_record_id' => $validatedData['health_record_id'],
            'remarks' => $validatedData['remarks'],
        ]);
        $health_rocord = HealthRecord::find($validatedData['health_record_id']);
        $health_rocord->status = "completed";
        $health_rocord->save();


        return response()->json([
            'message' => 'Recommendation created successfully',
        ], Response::HTTP_OK);
    }
    public function medicalRecords(Request $request)
    {
        $records = HealthRecord::all();
        $records->each(function ($record) {
            $patient = Patient::find($record->patient_id);
            $user = User::find($patient->user_id);
            $record->name = $user->name;
        });

        return response()->json([
            'message' => 'Retrieved successfully',
            'data' => $records,
        ], Response::HTTP_OK);
    }
    public function record(Request $request, $id)
    {
        $records = HealthRecord::find($id);
        if (!$records) {
            return response()->json([
                'message' => 'Record not found',
            ], Response::HTTP_NOT_FOUND);
        };
        $patient = Patient::find($records->patient_id);
        $user = User::find($patient->user_id);
        $records->name = $user->name;
        return response()->json([
            'message' => 'Retrieved successfully',
            'data' => $records,
        ], Response::HTTP_OK);
    }
    public function closeAppoint(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:appointments,id',
        ]);
        $appointment = Appointment::find($validatedData['id']);

        $appointment->status = "Completed";
        $appointment->save();
        return response()->json([
            'message' => 'Appointment closed successfully',
        ], Response::HTTP_OK);
    }
    public function dashData(Request $request)
    {
        $doctor = Doctors::where('user_id', Auth::id())->first();
        $appointments = $doctor->getAppointments()->where('status', 'Scheduled')->get()->count();
        $completedAppointments = $doctor->getAppointments()->where('status', 'completed')->count();
        $health_records = HealthRecord::where('status', 'pending')->get()->count();
        $completedHealth_recors = HealthRecord::where('status', 'completed')->count();

        return response()->json([
            'appointments' => $appointments,
            'completedAppointments' => $completedAppointments,
            'health_records' => $health_records,
            'completedHealth_recors' => $completedHealth_recors, Response::HTTP_OK
        ]);
    }
    public function alerts(Request $request)
    {
        $doctor = Doctors::where('user_id', Auth::id())->first();
        $notifications = $doctor->unreadNotifications()->get();

        $notifications->map(function ($notification) {

            $notification->doctor = User::find($notification->doctor_id)->name;
            $notification->patient = User::find($notification->patient_id)->name;
        });
        return response()->json(["data" => $notifications], Response::HTTP_OK);
    }
    public function appointments(Request $request)
    {
        $doctor = Doctors::where('user_id', Auth::id())->first();
        $appointments = $doctor->getAppointments()->get();

        $appointments->each(function ($appointment) {
            $patient = Patient::find($appointment->patient_id);
            $doctor = Doctors::find($appointment->doctor_id);

            $appointment->patient_name = User::find($patient->user_id)->name;
            $appointment->doctor_name = User::find($doctor->user_id)->name;
        });
        return response()->json(["data" => $appointments], Response::HTTP_OK);
    }
}
