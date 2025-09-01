<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    /**
     * Hiển thị danh sách tài khoản.
     */
    public function index(Request $request)
    {
        $query = Account::query();
         // tìm kiếm theo tên, email, sđt
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
                
            });
        }
        if ($request->filled('role')) {
        $query->where('role', $request->role);
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'asc');
        $perPage = $request->get('per_page', 10);
       

        $accounts = $query->orderBy($sort, $order)->paginate($perPage)->appends($request->all());

        if ($request->ajax()) {
            return view('admin.accounts.index', compact('accounts'))->render();
        }

         return view('admin.accounts.index', compact('accounts'));
    }

    /**
     * Hiển thị form tạo tài khoản mới.
     */
    public function create()
    {
        return view('admin.accounts.create');
    }

    /**
     * Lưu tài khoản mới.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:accounts',
            'password' => 'required|min:6',
            'role'     => 'required|integer',
            'image'    => 'nullable|image|max:2048',
        ]);

        // Upload ảnh
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('avatars', 'public');
        }

        $validated['password'] = bcrypt($validated['password']);
        Account::create($validated);

        return redirect()->route('accounts.index')->with('success', 'Thêm tài khoản thành công');
    }

    /**
     * Hiển thị chi tiết tài khoản.
     */
    public function show(Account $account)
    {
        return view('admin.accounts.show', compact('account'));
    }

    /**
     * Hiển thị form sửa tài khoản.
     */
    public function edit(Account $account)
    {
        return view('admin.accounts.edit', compact('account'));
    }

    /**
     * Cập nhật tài khoản.
     */
    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'name'      => 'nullable|string|max:100',
            // 'email'     => 'required|email|unique:accounts,email,' . $account->id,
            'role'      => 'required|integer',
            'image'     => 'nullable|image|max:4076',
            'address'   => 'nullable|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'birth'     => 'nullable|date',
            'gender'    => 'nullable|in:Male,Female,Other',
            'is_active' => 'required|boolean',
        ]);

        // Nếu nhập password thì hash
        if ($request->filled('password')) {
             $request->validate([
            'password' => 'string|min:6', // ✅ chỉ khi nhập thì check
            ]);
            $validated['password'] = bcrypt($request->password);
        }

        // Upload ảnh mới
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($account->image && Storage::disk('public')->exists($account->image)) {
                Storage::disk('public')->delete($account->image);
            }
            $validated['image'] = $request->file('image')->store('avatars', 'public');
        }

        $account->update($validated);

        return redirect()->route('accounts.index')->with('success', 'Cập nhật tài khoản thành công');
    }

    /**
     * Xóa tài khoản.
     */
    public function destroy(Account $account)
    {
        // Xóa ảnh khi xóa account
        if ($account->image && Storage::disk('public')->exists($account->image)) {
            Storage::disk('public')->delete($account->image);
        }

        $account->delete();
        return redirect()->route('accounts.index')->with('success', 'Xóa tài khoản thành công');
    }
}
