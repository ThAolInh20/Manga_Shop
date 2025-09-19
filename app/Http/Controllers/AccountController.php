<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Requests\AccountRequest;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

 

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
       

        $accounts = $query->with('updatedBy')->orderBy($sort, $order)->paginate($perPage)->appends($request->all());

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
    public function store(AccountRequest $request)
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
    public function showBlade(){
        return view('user.auth.profi');
    }
    public function show2()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'Chưa đăng nhập'], 401);
    }

    // Lấy thông tin account kèm shipping, order và sản phẩm trong order
    $account = Account::with([
        'shipping', 
        'orders', 
        'orders.productOrders.product' // lấy luôn thông tin sản phẩm trong order
    ])->find($user->id);

    if (!$account) {
        return response()->json(['message' => 'Không tìm thấy account'], 404);
    }

    return response()->json([
        'account' => $account
    ]);
}

    /**
     * Hiển thị form sửa tài khoản.
     */
    public function edit(Account $account)

    {
        if($account->role==2){
                return redirect()->route('accounts.index')->with('error', 'Không thể sửa tài khoản khách hàng');
        }
        return view('admin.accounts.edit', compact('account'));
    }

    /**
     * Cập nhật tài khoản.
     */
    public function update(Request $request, Account $account)
    {
        if($account->role==2){
             return redirect()->route('accounts.index')->with('error', 'Không thể sửa tài khoản khách hàng');
        }
        $validated = $request->validate([
            'name'      => 'nullable|string|max:100',
            // 'email'     => 'required|email|unique:accounts,email,' . $account->id,
            'role'      => 'required|integer',
            // 'image'     => 'nullable|image|max:4076',
            'address'   => 'nullable|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'birth'     => 'nullable|date',
            'gender'    => 'nullable|in:Male,Female,Other',
            // 'is_active' => 'required|boolean',
        ],[
            'role.required'=>'Chọn role' 
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
        $mm = 'Cập nhật tài khoản'. $account->name.' thành công';

        return redirect()->route('accounts.index')->with('success', $mm);
    }
     public function update2(AccountRequest $request, $id)
    {
        $user = Auth::user();

        if (!$user || $user->id != $id) {
            return response()->json(['message' => 'Không có quyền'], 403);
        }
        try{
        $data = $request->validated();

                $account = Account::find($id);
                if (!$account) {
                    return response()->json(['message' => 'Không tìm thấy tài khoản'], 404);
                }

                $account->update($data);

                return response()->json([
                    'message' => 'Cập nhật thông tin thành công',
                    'account' => $account
                ]);
                }catch(\Exception $err){
                    return response()->json([
                    'message' => 'Lỗi'.$err
                    
                ]);
                }

        
    }

    /**
     * Xóa tài khoản.
     */
    public function destroy(Account $account)
    {
        // Xóa ảnh khi xóa account
        // if ($account->image && Storage::disk('public')->exists($account->image)) {
        //     Storage::disk('public')->delete($account->image);
        // }
        $user = auth()->user();

        // Nếu là Admin → xóa tất cả
        if ($user->role === 0) {
            $account->delete();
            return redirect()->route('accounts.index')->with('success', 'Xóa tài khoản thành công');
        }

        // Nếu là CTV → chỉ xóa tài khoản role = 2
        if ($user->role === 1 && $account->role === 2) {
            $account->delete();
            return redirect()->route('accounts.index')->with('success', 'Xóa tài khoản thành công');
        }

        // Các trường hợp còn lại → không được phép xóa
        return redirect()->route('accounts.index')->with('error', 'Bạn không có quyền xóa tài khoản này');
    }
    public function deactivate(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Chưa đăng nhập'], 401);
        }
        if($user->is_active==0){
            $user->is_active = 1;
            $user->save();

            return response()->json([
            'message' => 'Tài khoản đã bị hủy thành công',
            'account' => $user
            ]);
        }
        $user->is_active = 0;
        $user->save();

        return response()->json([
        'message' => 'Tài khoản đã khôi phục thành công',
        'account' => $user
        ]);
        
       

        
    }
   
}
