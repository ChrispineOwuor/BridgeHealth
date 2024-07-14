<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Recommendation;
use App\Models\Symptom;
use App\Models\Appointment;
use App\Models\Doctors;
use App\Models\HealthRecord;
use App\Models\Notification;
use App\Models\RecordSymptomPivot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PatientController extends Controller
{
    public function dashboardAction(Request $request)
    {
        $patient = Patient::where('user_id', Auth::id())->first();
        $appointments = Appointment::where('patient_id', $patient->id)->count();
        $records = HealthRecord::where('patient_id', $patient->id)->count();
        return response()->json(compact('records', 'appointments'), 200);
    }
    public function getSymptoms(Request $request)
    {
        $symptoms = Symptom::all();
        return response()->json([
            'msg' => 'All symptoms retrieved successfully',
            'data' => $symptoms, Response::HTTP_OK
        ]);
    }
    public function getDoctors(Request $request)
    {
        $doctors = Doctors::all();

        $doctors->each(function ($doctor) {
            $doctor->name = User::find($doctor->user_id)->name;
        });

        return response()->json([
            'msg' => 'All doctors retrieved successfully',
            'data' => $doctors, Response::HTTP_OK
        ]);
    }
    // Add a new medical record
    public function addRecord(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|in:check up,emmergency',
            'symptoms' => 'required|array',
            'symptoms.*' => 'exists:symptoms,id',
        ]);

        $patient = Patient::where('user_id', Auth::id())->first();
        $healthRecord = HealthRecord::create([
            'type' => $validatedData['type'],
            'patient_id' => $patient->id,
        ]);
        $healthRecord->symptoms()->attach($validatedData['symptoms']);
        return response()->json([
            'msg' => 'Record created successfully',
        ], Response::HTTP_OK);
    }

    // Book an appointment
    public function makeBooking(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'doctor_id' => 'required|integer|exists:doctors,id',
        ]);
        $patient = Patient::where('user_id', Auth::id())->first();
        $appointment = new Appointment();
        $appointment->patient_id = $patient->id;
        $appointment->doctor_id = $validatedData['doctor_id'];
        $appointment->appointment_date = $validatedData['date'];
        $appointment->appointment_time = $validatedData['time'];

        $appointment->save();

        return response()->json(['msg' => 'Appointment booked successfully'], Response::HTTP_ACCEPTED);
    }

    // Get all records
    public function allRecords()
    {
        $patient = Patient::where('user_id', Auth::id())->first();
        $records = HealthRecord::where('patient_id', $patient->id)->get();
        $records->each(function ($record) {
            $symptoms = RecordSymptomPivot::where("health_record_id", $record->id)->get();
            $symptoms = $symptoms->pluck('symptom_id');
            $record->symptoms = Symptom::whereIn('id', $symptoms)->get();
            $recommendations = Recommendation::where("health_record_id", $record->id)->get();
            $recommendations->each(function ($recommendation) {
                $doctor = Doctors::find($recommendation->doctor_id);
                $recommendation->doctor_name = User::find($doctor->user_id)->name;
            });
            $record->recommendations = $recommendations;
        });
        return response()->json([compact('records'), Response::HTTP_OK]);
    }

    public function show($id)
    {
        $patient = Patient::where('user_id', Auth::id())->first();
        $record = HealthRecord::where('patient_id', $patient->id);
        $recommendations = Recommendation::where('health_record_id', $record->id)->get();
        $recommendations->each(function ($recommendation) {
            $doctor = Doctors::find($recommendation->doctor_id);
            $recommendation->doctor_name = User::find($doctor->user_id)->name;
        });
        return response()->json([compact('record'), Response::HTTP_OK]);
    }

    // Delete a specific record
    public function destroy($id)
    {
        $patient = Patient::where('user_id', Auth::id())->first();

        $record = HealthRecord::where('patient_id', $patient->id);
        $record->delete();
        return response()->json(['msg' => 'Record deleted successfully'], Response::HTTP_OK);
    }

    // Set reminders
    public function reminders(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'message' => 'required|string',
            'severity' => 'required|in:important,normal',
        ]);

        $patient = Patient::where('user_id', Auth::id())->firstOrFail();

        $doctorCount = Doctors::count();

        if ($doctorCount === 0) {
            return response()->json(['msg' => 'No doctors available'], Response::HTTP_BAD_REQUEST);
        }

        $doctor_id = random_int(1, $doctorCount);

        Notification::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor_id,
            'message' => $validatedData['message'],
            'severity' => $validatedData['severity'],
        ]);

        return response()->json(['msg' => 'Reminder set successfully'], Response::HTTP_OK);
    }
}
