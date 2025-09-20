<?php

namespace App\Http\Controllers;

use App\Models\WebsiteCustom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class WebsiteCustomController extends Controller
{
   public function edit()
    {
        // Giả sử chỉ có 1 record
        $config = WebsiteCustom::first();
        return view('admin.website_custom.edit', compact('config'));
    }

   public function update(Request $request)
{
    $validated = $request->validate([
        'address'          => 'nullable|string|max:50',
        'hotline'          => 'nullable|string|max:50',
        'email'            => 'nullable|email|max:50',
        'primary_color'    => 'nullable|string|max:50',
        'background_color' => 'nullable|string|max:50',
        'background'       => 'nullable|string|max:50',
        'font_family'      => 'nullable|string|max:50',
        'logo'             => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4048',
    ]);

    try {
        $config = WebsiteCustom::first();

        // Xử lý upload logo
       if ($request->hasFile('logo')) {
            // Lưu trong thư mục storage/app/public/logos với tên cố định logo.png
            $path = $request->file('logo')->storeAs('logo', 'logo.png', 'public');
            $validated['logo'] = $path; // lưu path vào DB nếu cần
        }

        if (!$config) {
            WebsiteCustom::create($validated);
        } else {
            $config->update($validated);
        }

        return redirect()->back()->with('success', 'Cấu hình website cập nhật thành công!');
    } catch (\Throwable $e) {
        Log::error('Lỗi cập nhật WebsiteCustom: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại!');
    }
}

}
