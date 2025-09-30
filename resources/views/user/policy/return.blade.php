@extends('user.layouts.app')

@section('title', 'Chính sách đổi – trả sản phẩm')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">QUY ĐỊNH ĐỔI – TRẢ SẢN PHẨM</h2>

    <h4>Phạm vi áp dụng</h4>
    <p>Tất cả quý khách hàng mua sắm trực tiếp tại <strong>MangaShop</strong> hoặc mua online qua <a href="{{ url('/') }}">{{ url('/') }}</a>.</p>

    <h4>1. Điều kiện áp dụng</h4>
    <ul>
        <li>Sản phẩm bị lỗi kỹ thuật được xác nhận bởi Nhà sản xuất/Nhà cung cấp.</li>
        <li>Đổi sản phẩm trong vòng <strong>15 ngày</strong> kể từ ngày ghi trên hóa đơn.</li>
        <li>Sản phẩm còn nguyên seal, đầy đủ bao bì và quà tặng kèm (nếu có).</li>
        <li>Có hình ảnh, video chứng minh sản phẩm lỗi hoặc hư hao do vận chuyển.</li>
    </ul>

    <h4>2. Trường hợp không được đổi sản phẩm</h4>
    <ul>
        <li>Sản phẩm không thỏa các điều kiện trên.</li>
        <li>Hư hại do thiên tai, hỏa hoạn, lụt lội, côn trùng, động vật xâm nhập...</li>
        <li>Bị biến dạng, nứt vỡ, hư hỏng do tác động bên ngoài.</li>
    </ul>

    <h4>3. Chính sách đổi sản phẩm</h4>
    <ul>
        <li>Áp dụng <strong>một đổi một</strong> (cùng mẫu, màu, dung lượng,…).</li>
        <li>Không áp dụng đổi trả để hoàn tiền.</li>
        <li>Trong trường hợp hoàn tiền, <strong>MangaShop</strong> sẽ hoàn bằng tiền mặt hoặc chuyển khoản, sau khi trừ chi phí vận chuyển và chi phí khác (nếu có).</li>
    </ul>
</div>
@endsection
