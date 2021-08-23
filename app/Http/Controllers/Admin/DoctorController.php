<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\Specialty;

use App\Http\Controllers\Controller;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctors = User::doctors()->get();
        return view('doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $specialties = Specialty::all();
        return view('doctors.create', compact('specialties'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
       
            'name' => 'required|min:3',
            'email' => 'required|email',
            'dni' => 'nullable|min:10',
            'address' => 'nullable|min:5'            

        ];

        $this->validate($request, $rules);

        //Mass asigment
        $user = User::create(
            $request->only('name', 'email', 'dni', 'address', 'phone')
            + [
                'role' => 'doctor',
                'password' => bcrypt($request->input('password'))
            ]
        );

        $user->specialties()->attach($request->input('specialties'));

        $notification = 'El médico se ha registrado correctamente.';
        return redirect('/doctors')->with(compact('notification'));
    }

    
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $doctor = User::doctors()->findOrFail($id);
        $specialties = Specialty::all();
        $specialty_ids = $doctor->specialties()->pluck('specialties.id');
        return view('doctors.edit', compact('doctor', 'specialties', 'specialty_ids'));
    }

    
    public function update(Request $request, $id)
    {
        $rules = [
       
            'name' => 'required|min:3',
            'email' => 'required|email',
            'dni' => 'nullable|min:10',
            'address' => 'nullable|min:5'            

        ];
        $this->validate($request, $rules);

        $user = User::doctors()->findOrFail($id);

        $data = $request->only('name', 'email', 'dni', 'address', 'phone');
        $password = $request->input('password');
        if ($password)
            $data['password'] = bcrypt($password);

        $user->fill($data); //fill de los datos
        $user->save(); //hacemos el update

        $user->specialties()->sync($request->input('specialties'));

        $notification = 'La información del médico se ha actualizado correctamente.';
        return redirect('/doctors')->with(compact('notification'));
    }

    public function destroy(User $doctor)
    {
        $doctorName = $doctor->name;
        $doctor->delete();

        $notification = "El médico $doctorName se ha eliminado correctamente.";
        return redirect('doctors')->with(compact('notification'));
    }
}
