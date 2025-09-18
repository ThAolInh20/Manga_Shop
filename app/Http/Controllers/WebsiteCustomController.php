<?php

namespace App\Http\Controllers;

use App\Models\WebsiteCustom;
use Illuminate\Http\Request;

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
        ]);

        $config = WebsiteCustom::first();
        if (!$config) {
            WebsiteCustom::create($validated);
        } else {
            $config->update($validated);
        }

        return redirect()->back()->with('success', 'Cấu hình website cập nhật thành công!');
    }
}
