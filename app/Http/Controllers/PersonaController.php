<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PersonaController extends Controller
{
    public function index(Request $request)
    {
        $personas = Persona::query()
            ->when($request->search, function ($query, $search) {
                $query->where('per_nom', 'ilike', "%{$search}%")
                    ->orWhere('per_appm', 'ilike', "%{$search}%")
                    ->orWhere('per_cod', 'ilike', "%{$search}%");
            })
            ->orderBy('per_nom')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Persona/Index', [
            'personas' => $personas,
            'filters'  => $request->only('search'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Persona/Form', [
            'persona' => null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'per_cod'   => 'required|string|max:14|unique:persona,per_cod',
            'per_nom'   => 'required|string|max:150',
            'per_appm'  => 'required|string|max:150',
            'per_prof'  => 'required|string|max:100',
            'per_telf'  => 'required|string|max:17',
            'per_cel'   => 'required|string|max:17',
            'per_email' => 'required|email|max:50|unique:persona,per_email',
            'per_dir'   => 'required|string|max:150',
            'per_fnac'  => 'required|date',
            'per_lnac'  => 'required|string|max:150',
            'per_est'   => 'required|boolean',
            'per_foto'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $validated['per_create'] = now();
        $validated['per_update'] = now();

        if ($request->hasFile('per_foto')) {
            $validated['per_foto'] = $request->file('per_foto')
                ->store('img/personas', 'public');
        }

        Persona::create($validated);

        return redirect()->route('personas.index')
            ->with('success', 'Persona creada correctamente.');
    }

    public function edit(string $id)
    {
        $persona = Persona::findOrFail($id);

        return Inertia::render('Persona/Form', [
            'persona' => $persona,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $persona = Persona::findOrFail($id);

        $validated = $request->validate([
            'per_nom'   => 'required|string|max:150',
            'per_appm'  => 'required|string|max:150',
            'per_prof'  => 'required|string|max:100',
            'per_telf'  => 'required|string|max:17',
            'per_cel'   => 'required|string|max:17',
            'per_email' => 'required|email|max:50|unique:persona,per_email,' . $id . ',per_cod',
            'per_dir'   => 'required|string|max:150',
            'per_fnac'  => 'required|date',
            'per_lnac'  => 'required|string|max:150',
            'per_est'   => 'required|boolean',
            'per_foto'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $validated['per_update'] = now();

        if ($request->hasFile('per_foto')) {
            if ($persona->per_foto && $persona->per_foto !== 'img/placeholder.png') {
                Storage::disk('public')->delete($persona->per_foto);
            }
            $validated['per_foto'] = $request->file('per_foto')
                ->store('img/personas', 'public');
        } else {
            unset($validated['per_foto']); // ← no tocar la foto existente
        }

        $persona->update($validated);

        return redirect()->route('personas.index')
            ->with('success', 'Persona actualizada correctamente.');
    }

    public function destroy(string $id)
    {
        $persona = Persona::findOrFail($id);

        // Eliminar foto si no es el placeholder
        if ($persona->per_foto !== 'img/placeholder.png') {
            Storage::disk('public')->delete($persona->per_foto);
        }

        $persona->delete();

        return redirect()->route('personas.index')
            ->with('success', 'Persona eliminada correctamente.');
    }

    public function show(string $id)
    {
        $persona = Persona::findOrFail($id);

        return Inertia::render('Persona/Show', [
            'persona' => $persona,
        ]);
    }
}
