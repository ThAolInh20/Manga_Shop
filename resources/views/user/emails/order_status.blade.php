<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Cập nhật đơn hàng</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f7f7; margin: 0; padding: 0;">
  <div style="max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
    
    <!-- Header -->
    <div style="background: #0d6efd; padding: 16px; color: #fff; text-align: center; font-size: 18px; font-weight: bold;">
      🛒 Cập nhật đơn hàng
    </div>

    <!-- Content -->
    <div style="padding: 20px; color: #333;">
      <h2 style="margin-top: 0; color: #0d6efd;">Xin chào {{ $order->account->name }}</h2>
      <p style="font-size: 15px; line-height: 1.6;">{{ $messageText }}</p>
      <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
      
      <p style="margin: 6px 0;"><strong>Mã đơn:</strong> #{{ $order->id }}</p>
      <p style="margin: 6px 0;"><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
      <p style="margin: 6px 0;"><strong>Thời gian cập nhật:</strong> {{ $order->updated_at->format('d/m/Y H:i') }}</p>


      <div style="margin-top: 20px; text-align: center;">
        <a href="{{ route('user.order.show',$order->id) }}" 
           style="display: inline-block; background: #0d6efd; color: #fff; text-decoration: none; padding: 12px 20px; border-radius: 6px; font-weight: bold;">
          🔎 Xem chi tiết đơn hàng
        </a>
      </div>
    </div>

    <!-- Footer -->
    <div style="background: #f0f0f0; padding: 12px; text-align: center; font-size: 12px; color: #666;">
      Đây là email tự động, vui lòng không trả lời lại. <br>
      &copy; {{ date('Y') }} Mangashop
    </div>
  </div>
</body>
</html>
