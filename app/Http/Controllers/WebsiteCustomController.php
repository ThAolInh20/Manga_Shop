<?php

namespace App\Http\Controllers;

use App\Models\WebsiteCustom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


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
        'banner_main'      => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:8048',
        'sub_banners.*'    => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4048',
        'sub_banners_old'  => 'nullable|string',
        
    ]);

    try {
        $config = WebsiteCustom::first();

        // Xử lý upload logo
       if ($request->hasFile('logo')) {
            // Lưu trong thư mục storage/app/public/logos với tên cố định logo.png
            $path = $request->file('logo')->storeAs('logo', 'logo.png', 'public');
            $validated['logo'] = $path; // lưu path vào DB nếu cần
        }

         if ($request->hasFile('banner_main')) {
            $path = $request->file('banner_main')->storeAs('banner', 'main_banner.png', 'public');
            $validated['banner_main'] = $path;
        }
        $finalSubBanners = [];

        // 1. Lấy lại mảng cũ (đã xoá bằng nút ❌ bên client)
        if ($request->filled('sub_banners_old')) {
            $old = json_decode($request->input('sub_banners_old'), true);
            if (is_array($old)) {
                $finalSubBanners = $old;
            }
        }
        if ($config && $config->sub_banners) {
            $currentBanners = json_decode($config->sub_banners, true);

            foreach ($currentBanners as $path) {
                if (!in_array($path, $finalSubBanners)) {
                    Storage::disk('public')->delete($path); // xoá trong storage
                }
            }
        }
        // 2. Nếu có upload mới → thêm vào cuối
        if ($request->hasFile('sub_banners')) {
            foreach ($request->file('sub_banners') as $file) {
                $filename = uniqid("sub_banner_").".".$file->getClientOriginalExtension();
                $path = $file->storeAs("banner/sub", $filename, "public");
                $finalSubBanners[] = $path;
            }
        }

        $validated['sub_banners'] = json_encode($finalSubBanners);


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
