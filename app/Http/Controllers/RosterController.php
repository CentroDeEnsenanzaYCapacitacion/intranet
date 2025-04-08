<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Http\Requests\StaffRequest;

class RosterController extends Controller
{
    public function index()
    {
        $staff = Staff::where('isActive', true)->get();

        return view('admin.rosters.staff.show', compact('staff'));
    }

    public function create()
    {
        return view('admin.rosters.staff.new');
    }

    public function store(StaffRequest $request)
    {
        $data = $request->validated();

        $staff = new Staff();
        $staff->name = $data['name'];
        $staff->surnames = $data['surnames'] ?? null;
        $staff->Address = $data['Address'] ?? null;
        $staff->colony = $data['colony'] ?? null;
        $staff->municipalty = $data['municipalty'] ?? null;
        $staff->phone = $data['phone'] ?? null;
        $staff->cel = $data['cel'] ?? null;
        $staff->rfc = $data['rfc'] ?? null;
        $staff->department = $data['department'] ?? null;
        $staff->position = $data['position'] ?? null;
        $staff->personal_mail = $data['personal_mail'] ?? null;
        $staff->requiresMail = $request->has('requiresMail');
        $staff->isRoster = $request->has('isRoster');
        $staff->isActive = true;

        $staff->save();

        return redirect()->route('admin.staff.show')->with('success', 'Empleado creado exitosamente.');
    }

    public function deactivate($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->isActive = false;
        $staff->save();

        return redirect()->route('admin.staff.show')->with('success', 'Empleado eliminado correctamente.');
    }

    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        return view('admin.rosters.staff.edit', compact('staff'));
    }

    public function update(StaffRequest $request, $id)
    {
        $staff = Staff::findOrFail($id);

        $data = $request->validated();

        $staff->update([
            ...collect($data)->except(['name', 'surnames', 'cec_mail', 'requiresMail'])->toArray(),
            'isRoster' => $request->has('isRoster'),
        ]);

        return redirect()->route('admin.staff.show')->with('success', 'Empleado actualizado correctamente.');
    }
}
