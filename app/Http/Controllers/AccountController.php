<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Trả về view danh sách tài khoản
        $accounts = Account::all(); // Assuming you have an Account model
        return view('admin.accounts.index',compact('accounts')); // Assuming you have a view for listing accounts
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'name' => 'nullable|string|max:100',
        'email' => 'required|email|unique:accounts',
        'password' => 'required|min:6',
        'role' => 'required|integer',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        Account::create($validated);

        return redirect()->route('accounts.index')->with('success', 'Thêm tài khoản thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        return view('admin.accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        return view('admin.accounts.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
        'name' => 'nullable|string|max:100',
        'email' => 'required|email|unique:accounts,email,' . $account->id,
        'role' => 'required|integer',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        $account->update($validated);

        return redirect()->route('accounts.index')->with('success', 'Cập nhật tài khoản thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->route('accounts.index')->with('success', 'Xóa tài khoản thành công');
    }
}
