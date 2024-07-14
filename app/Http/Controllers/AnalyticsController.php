<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\HealthRecord;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Most Common Symptoms
        $mostCommonSymptoms = DB::table('record_symptom_pivots')
        ->join('symptoms', 'record_symptom_pivots.symptom_id', '=', 'symptoms.id')
        ->select('symptoms.name', DB::raw('count(*) as total'))
        ->groupBy('symptoms.name')
        ->orderByDesc('total')
        ->get();

        // Mothers' Age Groups
        $ageGroups = [
            '18-25' => 0,
            '26-35' => 0,
            '36-45' => 0,
            '46-55' => 0,
            '56+' => 0,
        ];

        $mothers = Patient::all();
        foreach ($mothers as $mother) {
            $age = Carbon::parse($mother->date_of_birth)->age;

            if ($age >= 18 && $age <= 25) {
                $ageGroups['18-25']++;
            } elseif ($age >= 26 && $age <= 35) {
                $ageGroups['26-35']++;
            } elseif ($age >= 36 && $age <= 45) {
                $ageGroups['36-45']++;
            } elseif ($age >= 46 && $age <= 55) {
                $ageGroups['46-55']++;
            } else {
                $ageGroups['56+']++;
            }
        }

        // Number of Health Records Created per Day
        $healthRecordsPerDay = HealthRecord::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

        // Mothers Registered per Month of the Year
        $months = [
            'January' => 0,
            'February' => 0,
            'March' => 0,
            'April' => 0,
            'May' => 0,
            'June' => 0,
            'July' => 0,
            'August' => 0,
            'September' => 0,
            'October' => 0,
            'November' => 0,
            'December' => 0,
        ];

        $mothersPerMonth = Patient::select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->get();

        foreach ($mothersPerMonth as $record) {
            $monthName = Carbon::createFromFormat('m', $record->month)->format('F');
            $months[$monthName] = $record->total;
        }

        // Appointments per Month
        $appointmentsPerMonth = HealthRecord::select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->get();

        $appointments = [];
        foreach ($appointmentsPerMonth as $record) {
            $monthName = Carbon::createFromFormat('m', $record->month)->format('F');
            $appointments[$monthName] = $record->total;
        }

        // Pie Chart for Appointments Status
        $appointmentStatuses = [
            'Scheduled' => 0,
            'Completed' => 0,
            'Cancelled' => 0,
        ];

        $appointmentsStatusCount = HealthRecord::select('status', DB::raw('count(*) as total'))
        ->groupBy('status')
        ->get();

        foreach ($appointmentsStatusCount as $record) {
            $appointmentStatuses[$record->status] = $record->total;
        }

        return response()->json([
            'most_common_symptoms' => $mostCommonSymptoms,
            'mothers_age_groups' => $ageGroups,
            'health_records_per_day' => $healthRecordsPerDay,
            'mothers_registered_per_month' => $months,
            'appointments_per_month' => $appointments,
            'appointments_statuses' => $appointmentStatuses,
        ]);
    }
}
