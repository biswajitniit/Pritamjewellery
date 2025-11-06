<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Rolepermissionuser;
use App\Models\Rolepermission;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class RolepermissionuserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rolepermissionusers = Rolepermissionuser::simplePaginate(100);
        return view('rolepermissionusers.list', compact('rolepermissionusers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::select('id', 'name')
            ->whereNotIn('id', function ($query) {
                $query->select('user_id')
                    ->from('rolepermissionusers');
            })
            ->get();
        return view('rolepermissionusers.add', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'user_id'           => 'required|unique:rolepermissionusers',
            ],
            [
                'user_id.required' => 'User is Required', // custom message
            ]
        );

        $rolepermissionuser = Rolepermissionuser::create([
            'user_id'              => strip_tags($request->user_id),
            'created_by'           => Auth::user()->name
        ]);
        $lastInsertedId = $rolepermissionuser->id;

        $menus = [
            'pcodes',
            'uoms',
            'sizes',
            'patterns',
            'stones',
            'customers',
            'companies',
            'vendors',
            'karigars',
            'itemdescriptionheaders',
            'products',
            'metals',
            'metalpurities',
            'tollerences',
            'locations',
            // FOR TRANSACTION
            'customerordertemps',
            'customerorders',
            'issuetokarigars',
            'miscellaneouses',
            'purchases',
            'sales',
        ];

        foreach ($menus as $menu) {
            Rolepermission::create([
                'rolepermissionuser_id' => $lastInsertedId ?? null, // include only if required
                'user_id'              => $request->user_id,
                'menu_name'            => $menu,
                'menu_permissions'     => $request->$menu ?? '0',
                'permissions_add'      => $request->{$menu . '_menus_add'} ?? '0',
                'permissions_edit'     => $request->{$menu . '_menus_edit'} ?? '0',
                'permissions_delete'   => $request->{$menu . '_menus_delete'} ?? '0',
                'permissions_view'     => $request->{$menu . '_menus_view'} ?? '0',
                'permissions_print'    => $request->{$menu . '_menus_excels_and_printpdf'} ?? '0',
            ]);
        }

        //return redirect()->back()->withSuccess('Role permission record created successfully.');
        return redirect()->route('rolepermissionusers.index')->withSuccess('Role permission record created successfully.');
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
        $users = User::select('id', 'name')
            ->whereIn('id', function ($query) {
                $query->select('user_id')
                    ->from('rolepermissionusers');
            })
            ->get();
        $rolepermissionusers = Rolepermissionuser::findOrFail($id);
        return view('rolepermissionusers.edit', compact('users', 'rolepermissionusers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Step 1: Check and delete existing permissions
            if (Rolepermission::where('rolepermissionuser_id', $id)->exists()) {
                Rolepermission::where('rolepermissionuser_id', $id)->delete();
            }

            // Step 2: Insert new permissions
            $menus = [
                'pcodes',
                'uoms',
                'sizes',
                'patterns',
                'stones',
                'customers',
                'companies',
                'vendors',
                'karigars',
                'itemdescriptionheaders',
                'products',
                'metals',
                'metalpurities',
                'tollerences',
                'locations',
                // FOR TRANSACTION
                'customerordertemps',
                'customerorders',
                'issuetokarigars',
                'miscellaneouses',
                'purchases',
                'sales',
            ];

            foreach ($menus as $menu) {
                Rolepermission::create([
                    'rolepermissionuser_id' => $id, // include only if required
                    'user_id'               => $request->user_id,
                    'menu_name'             => $menu,
                    'menu_permissions'      => $request->input($menu, '0'),
                    'permissions_add'       => $request->input($menu . '_menus_add', '0'),
                    'permissions_edit'      => $request->input($menu . '_menus_edit', '0'),
                    'permissions_delete'    => $request->input($menu . '_menus_delete', '0'),
                    'permissions_view'      => $request->input($menu . '_menus_view', '0'),
                    'permissions_print'     => $request->input($menu . '_menus_print', '0'),
                ]);
            }
            return redirect()->route('rolepermissionusers.index')->withSuccess('Role permission record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Rolepermission::where('rolepermissionuser_id', $id)->delete();
        Rolepermissionuser::where('id', $id)->firstorfail()->delete();
        return redirect('/rolepermissionusers.index')->with('success', 'Role permission record deleted successfully.');
    }
}
