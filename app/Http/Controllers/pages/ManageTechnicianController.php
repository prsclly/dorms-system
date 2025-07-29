<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Technician;
use App\Models\Specialization;

class ManageTechnicianController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $technicians = Technician::with('specialization')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
            })
            ->orderBy('name')
            ->paginate(10);

        $specializations = Specialization::orderBy('name')->get();

        return view('content.pages.manage-technicians', compact('technicians', 'specializations', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'               => 'required|string',
            'email'              => 'required|email|unique:technicians,email',
            'specialization_id'  => 'nullable|exists:specializations,id',
            'new_specialization' => 'nullable|string'
        ]);

        if (!$request->specialization_id && !$request->new_specialization) {
            return back()->withErrors(['specialization' => 'Please select or enter a specialization.']);
        }

        if ($request->new_specialization) {
            $specialization = Specialization::firstOrCreate([
                'name' => ucfirst($request->new_specialization)
            ]);
            $specializationId = $specialization->id;
        } else {
            $specializationId = $request->specialization_id;
        }

        $firstName = Str::of($request->name)->explode(' ')[0];
        $plainPassword = 'Teknisi' . ucfirst($firstName) . '123';

        Technician::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'specialization_id' => $specializationId,
            'password'          => Hash::make($plainPassword),
        ]);

        return back()->with('success', 'Technician added successfully. Password: <strong>' . $plainPassword . '</strong>');
    }

    public function update(Request $request, Technician $technician)
    {
        $request->validate([
            'name'              => 'required|string',
            'email'             => 'required|email|unique:technicians,email,' . $technician->id,
            'specialization_id' => 'required|exists:specializations,id'
        ]);

        $technician->update([
            'name'              => $request->name,
            'email'             => $request->email,
            'specialization_id' => $request->specialization_id
        ]);

        return back()->with('success', 'Technician updated.');
    }

    public function destroy(Technician $technician)
    {
        $technician->delete();
        return back()->with('success', 'Technician deleted.');
    }
}
