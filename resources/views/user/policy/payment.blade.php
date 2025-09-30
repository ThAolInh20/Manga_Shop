@extends('user.layouts.app')

@section('title', 'Chính sách thanh toán - MangaShop')

@section('content')
<div class="container my-5">
  <div class="card shadow-lg rounded-2xl">
    <div class="card-body p-5">
      <h1 class="mb-4 text-center fw-bold">💳 PHƯƠNG THỨC THANH TOÁN</h1>

      <h3 class="mt-4">1/ Thanh toán bằng tiền mặt khi nhận hàng (COD)</h3>
      <p>
        Sau khi khách hàng đặt hàng thành công trên Website, hệ thống MangaShop sẽ gửi email thông báo đơn hàng
        đến địa chỉ email quý khách đã đăng ký.
      </p>
      <p>
        Các yêu cầu giao hàng cần có thông tin chính xác về người nhận, địa chỉ và số điện thoại.  
        Quý khách vui lòng kiểm tra đúng tên và thông tin nhận hàng kèm theo gói hàng trước khi thanh toán.  
        MangaShop không chịu trách nhiệm nếu quý khách thanh toán nhầm hoặc dư cho nhân viên giao hàng.
      </p>

      <h3 class="mt-4">2/ Thanh toán trực tuyến qua PayOS</h3>
      <p>
        Đối với các đơn hàng chọn phương thức thanh toán bằng <strong>PayOS</strong>:  
        Sau khi đặt hàng thành công, quý khách sẽ được chuyển hướng sang cổng thanh toán PayOS để hoàn tất giao dịch.
      </p>

      <ul>
        <li>Quý khách cần thanh toán <strong>100% giá trị đơn hàng</strong> (bao gồm cả phí vận chuyển nếu có).</li>
        <li>Hệ thống PayOS sẽ hiển thị đầy đủ thông tin đơn hàng, khách hàng chỉ cần chọn phương thức thanh toán phù hợp (thẻ ngân hàng, ví điện tử...)</li>
        <li>Sau khi giao dịch thành công, hệ thống sẽ tự động gửi email xác nhận đơn hàng đã được thanh toán.</li>
      </ul>

      <p>
        MangaShop chỉ tiến hành giao hàng khi đơn hàng đã được xác nhận thanh toán thành công qua PayOS.
      </p>

      <p class="mt-4">
        Nếu cần hỗ trợ thêm, vui lòng liên hệ hotline <strong>0849838298</strong> để được hỗ trợ kịp thời.
      </p>

      <p class="mt-5 text-muted fst-italic">
        Cập nhật lần cuối: {{ date('d/m/Y H:i') }}
      </p>
    </div>
  </div>
</div>

@endsection