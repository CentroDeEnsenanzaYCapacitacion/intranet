<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdjustmentDefinition;

class PerceptionsController extends Controller
{
    public function __construct()
    {

        $this->middleware('role:1,2,4');
    }

    public function getData()
    {
        $perceptions = AdjustmentDefinition::where('type', 'perception')->orderBy('name')->get();
        $deductions = AdjustmentDefinition::where('type', 'deduction')->orderBy('name')->get();

        return view('admin.catalogues.perceptions.show', compact('perceptions', 'deductions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:perception,deduction',
        ]);

        AdjustmentDefinition::create($validated);

        return redirect()->route('admin.catalogues.perceptions.show')->with('success', 'Registro creado correctamente.');
    }

    public function destroy($id)
    {
        $definition = AdjustmentDefinition::findOrFail($id);
        $definition->delete();

        return redirect()->route('admin.catalogues.perceptions.show')->with('success', 'Registro eliminado correctamente.');
    }
}
