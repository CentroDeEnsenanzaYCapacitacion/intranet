<?php

namespace App\Http\Controllers;

use App\Models\HourAssignment;
use App\Models\Staff;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\Crew;

class CalendarController extends Controller
{
    public function calendar()
    {
        $assignments = HourAssignment::with(['staff', 'subject'])->get();

        $events = $assignments->map(function ($assignment) {
            return [
                'id' => $assignment->id,
                'title' => $assignment->staff->name . ' - ' . $assignment->hours . ' hrs',
                'start' => "{$assignment->date}T{$assignment->start_time}",
                'end' => "{$assignment->date}T{$assignment->end_time}",
                'extendedProps' => [
                    'staff_id' => $assignment->staff_id,
                    'subject_id' => $assignment->subject_id,
                    'staff_name' => $assignment->staff->name . ' ' . $assignment->staff->surnames,
                    'subject_name' => $assignment->subject->name,
                    'hours' => $assignment->hours,
                    'start_time' => $assignment->start_time,
                    'end_time' => $assignment->end_time
                ]
            ];
        });

        $staff = Staff::where('isActive', true)
            ->where('isRoster', false)
            ->orderBy('name')
            ->get();

        $subjects = Subject::where('is_active', true)->orderBy('name')->get();

        $crews    = Crew::orderBy('name')->get();

        return view(
            'system.calendar.teachers',
            compact('staff', 'subjects', 'crews')
        );
    }

    public function getHourAssignments(Request $request)
    {
        $crewId = $request->input('crew_id');
        $start = $request->input('start');
        $end = $request->input('end');

        $query = HourAssignment::with(['staff', 'subject']);

        if ($crewId) {
            $query->where('crew_id', $crewId);
        }

        if ($start && $end) {
            $query->whereBetween('date', [$start, $end]);
        }

        $assignments = $query->get();

        $events = $assignments->map(function ($assignment) {
            return [
                'id' => $assignment->id,
                'title' => $assignment->staff->name . ' - ' . $assignment->hours . ' hrs',
                'start' => "{$assignment->date}T{$assignment->start_time}",
                'end' => "{$assignment->date}T{$assignment->end_time}",
                'extendedProps' => [
                    'staff_id' => $assignment->staff_id,
                    'subject_id' => $assignment->subject_id,
                    'staff_name' => $assignment->staff->name . ' ' . $assignment->staff->surnames,
                    'subject_name' => $assignment->subject->name,
                    'hours' => $assignment->hours,
                    'start_time' => $assignment->start_time,
                    'end_time' => $assignment->end_time
                ]
            ];
        });

        return response()->json($events);
    }




    public function storeHourAssignment(Request $request)
    {
        $data = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'crew_id' => 'nullable|exists:crews,id',
        ]);

        $start = strtotime($data['start_time']);
        $end = strtotime($data['end_time']);
        $data['hours'] = ($end - $start) / 3600;

        $data['crew_id'] = auth()->user()->crew_id === 1
            ? $request->input('crew_id')
            : auth()->user()->crew_id;

        HourAssignment::create($data);

        return response()->json(['message' => 'Horas asignadas correctamente']);
    }


    public function updateHourAssignment(Request $request, $id)
    {
        $assignment = HourAssignment::findOrFail($id);

        $data = $request->validate([
            'subject_id' => 'sometimes|exists:subjects,id',
            'staff_id' => 'sometimes|exists:staff,id',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
            'date' => 'sometimes|date'
        ]);

        if (isset($data['start_time']) && isset($data['end_time'])) {
            $start = strtotime($data['start_time']);
            $end = strtotime($data['end_time']);
            $data['hours'] = ($end - $start) / 3600;
        }

        $assignment->update($data);

        return response()->json(['message' => 'Asignación actualizada correctamente']);
    }



    public function deleteHourAssignment($id)
    {
        $assignment = HourAssignment::findOrFail($id);
        $assignment->delete();

        return response()->json(['message' => 'Asignación eliminada correctamente']);
    }
}
