@extends('user.layouts.app')
@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Cập nhật đơn hàng #{{ $order->id }}</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($order)
    <form action="{{ route('user.order.update.post', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Thông tin người nhận -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-bold">Thông tin người nhận</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Tên người nhận</label>
                    <input type="text" name="name_recipient" class="form-control" 
                        value="{{ old('name_recipient', $order->name_recipient) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone_recipient" class="form-control" 
                        value="{{ old('phone_recipient', $order->phone_recipient) }}" required>
                </div>
               <!-- --------------  -->
              <div class="mb-3">
    <label class="form-label">Tỉnh / Thành phố</label>
    <select id="province" class="form-select select2" required>
        <option value="">-- Chọn tỉnh --</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Quận / Huyện</label>
    <select id="district" class="form-select select2" required disabled>
        <option value="">-- Chọn huyện --</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Xã / Phường</label>
    <select id="ward" class="form-select select2" required disabled>
        <option value="">-- Chọn xã --</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Số nhà, đường</label>
    <input type="text" id="street" class="form-control" placeholder="Ví dụ: 123 Lê Lợi" />
</div>
<div class="mb-3">
    <label class="form-label">Địa chỉ nhận</label>
    <textarea 
        name="shipping_address" 
        id="shipping_address" 
        class="form-control" 
        rows="3" 
        required
    >{{ old('shipping_address', $order->shipping_address) }}</textarea>
</div>

        <!-- Phương thức thanh toán -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-bold">Phương thức thanh toán</div>
            <div class="card-body">
                <select name="payment_status" class="form-select">
                    <option value="0" {{ $order->payment_status == 0 ? 'selected' : '' }}>Thanh toán khi nhận hàng (COD)</option>
                    <option value="1" {{ $order->payment_status == 1 ? 'selected' : '' }}>Thanh toán online</option>
                </select>
            </div>
        </div>

        <!-- Voucher (nếu có) -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-bold">Mã giảm giá</div>
            <div class="card-body">
                <input type="text" name="voucher_code" class="form-control"
                    value="{{ old('voucher_code', $order->voucher_code) }}" placeholder="Nhập mã voucher nếu có">
            </div>
        </div>

        <!-- Nút submit -->
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('user.cart.list') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
    @else
        <div class="alert alert-warning">Không tìm thấy đơn hàng</div>
    @endif
</div>
<script>
document.addEventListener("DOMContentLoaded", async () => {
    const provinceEl = $("#province")
    const districtEl = $("#district")
    const wardEl = $("#ward")
    const streetEl = $("#street")
    const addressEl = $("#shipping_address")

    // init Select2 cho 3 select
    $(".select2").select2({
        width: "100%",
        placeholder: "Chọn...",
        allowClear: true
    })

    // Load danh sách tỉnh
    const provinces = await fetch("https://provinces.open-api.vn/api/p/").then(r => r.json())
    provinces.forEach(p => {
        provinceEl.append(new Option(p.name, p.code))
    })

    // Khi chọn tỉnh
    provinceEl.on("change", async function () {
        districtEl.empty().append(new Option("-- Chọn huyện --", ""))
        wardEl.empty().append(new Option("-- Chọn xã --", ""))
        districtEl.prop("disabled", true)
        wardEl.prop("disabled", true)
        updateAddress()

        if (!this.value) return
        const data = await fetch(`https://provinces.open-api.vn/api/p/${this.value}?depth=2`).then(r => r.json())
        data.districts.forEach(d => {
            districtEl.append(new Option(d.name, d.code))
        })
        districtEl.prop("disabled", false)
    })

    // Khi chọn huyện
    districtEl.on("change", async function () {
        wardEl.empty().append(new Option("-- Chọn xã --", ""))
        wardEl.prop("disabled", true)
        updateAddress()

        if (!this.value) return
        const data = await fetch(`https://provinces.open-api.vn/api/d/${this.value}?depth=2`).then(r => r.json())
        data.wards.forEach(w => {
            wardEl.append(new Option(w.name, w.code))
        })
        wardEl.prop("disabled", false)
    })

    // Khi chọn xã hoặc nhập số nhà
    wardEl.on("change", updateAddress)
    streetEl.on("input", updateAddress)

    function updateAddress() {
        const provinceName = provinceEl.find("option:selected").text()
        const districtName = districtEl.find("option:selected").text()
        const wardName = wardEl.find("option:selected").text()
        const street = streetEl.val()

        addressEl.val([street, wardName, districtName, provinceName].filter(Boolean).join(", "))
    }
})
</script>


@endsection
