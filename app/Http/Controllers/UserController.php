<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('id', '!=', '15')->simplePaginate(25);
        return view('users.list', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'name'              => 'required',
                'email'             => 'required|email|unique:users',
                'password'          => 'required',
                'mobile'            => 'nullable',
            ],
            [
                'name.required' => 'Your Name is Required', // custom message
                'email.required' => 'Your Email is Required', // custom message
                'password.required' => 'Your Password is Required', // custom message
            ]
        );

        User::create([
            'name'                 => strip_tags($request->name),
            'email'                => strip_tags($request->email),
            'password'             => Hash::make($request->password),
            'mobile'               => strip_tags($request->mobile),
            'status'               => strip_tags($request->status)
        ]);

        return redirect()->route('users.index')->withSuccess('Admin record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {


        $validatedData = $request->validate(
            [
                'name'              => 'required',
                'email'             => 'required',
                'mobile'            => 'nullable',
            ],
            [
                'name.required' => 'Your Name is Required', // custom message
                'email.required' => 'Your Email is Required', // custom message
            ]
        );
        try {
            $user                     = User::find($id);
            $user->name               = strip_tags($request->input('name'));
            $user->email              = strip_tags($request->input('email'));
            if ($request->has('password')) {
                $user->password             = Hash::make($request->password);
            }
            $user->mobile             = strip_tags($request->input('mobile'));
            $user->status             = strip_tags($request->input('status'));
            $user->update();

            return redirect()->route('users.index')->withSuccess('Admin record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::where('id', $id)->firstorfail()->delete();
        return redirect('/users')->with('success', 'Book deleted');
    }
}
