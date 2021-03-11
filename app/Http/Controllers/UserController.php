<?php

namespace App\Http\Controllers;

use App\Models\Doctype;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctype = Doctype::all();
        $rows = DB::table('users')
                ->join('doctypes', 'users.doctypessss_id', '=', 'doctypes.id')
                ->select('users.*', 'doctypes.name AS doctypesName')
                ->get();

        return view('admin.index', compact('doctype', 'rows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'          => ['required', 'string', 'min:8', 'confirmed'],
            'lastname'          => 'required',
            'doctypessss_id'    => 'required',
            'docnumber'         => 'required',
            'birthday'          => 'required',
            'telephone'         => 'required',
        ]);

        $users                  = new User();
        $users->name            = $request->name;
        $users->email           = $request->email;
        $users->password        = Hash::make($request['password']);
        $users->lastname        = $request->lastname;
        $users->doctypessss_id  = $request->doctypessss_id;
        $users->docnumber       = $request->docnumber;
        $users->birthday        = $request->birthday;
        $users->telephone       = $request->telephone;
        $users->role = '2';

        $users->save();

        return redirect()->route('admin.index')->with('mensaje', '¡Usuario creado con exito!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'lastname'          => 'required',
            'doctypessss_id'    => 'required',
            'docnumber'         => 'required',
            'birthday'          => 'required',
            'telephone'         => 'required',
        ]);

        $rows = DB::table('users')
                ->where('id', $request->id)
                ->update([
                    'name'              => $request->name,
                    'email'             => $request->email,
                    'lastname'          => $request->lastname,
                    'doctypessss_id'    => $request->doctypessss_id,
                    'docnumber'         => $request->docnumber,
                    'birthday'          => $request->birthday,
                    'telephone'         => $request->telephone,
                ]);

        return redirect()->route('admin.index')->with('mensaje', '¡Usuario creado con exito!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rows = User::find($id);
        $rows->delete();

        return redirect()->route('admin.index')->with('mensaje', '¡Usuario eliminado con exito!');
    }
}
