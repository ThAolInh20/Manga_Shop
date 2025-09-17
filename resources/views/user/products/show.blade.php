@extends('user.layouts.app')

@section('title', $product->name . ' - Chi tiết sản phẩm')

@section('content')
<div class="row g-4">
  <!-- Cột trái -->
  <div class="col-md-5">
    <div class="card p-3 shadow-sm">
      <!-- Ảnh chính -->
      <div class="text-center mb-3">
        <a href="{{ asset('storage/' . $product->images) }}" data-lightbox="product-gallery">
          <img src="{{ asset('storage/' . $product->images) }}" 
               class="img-fluid rounded shadow-sm"
               style="width:100%; max-height: 350px; object-fit: contain;" 
               alt="{{ $product->name }}">
        </a>
      </div>

      <!-- Ảnh phụ -->
      @if($product->images_sup)
      <div class="d-flex justify-content-center flex-wrap gap-2 mb-3">
        @foreach(json_decode($product->images_sup, true) as $img)
          <a href="{{ asset('storage/' . $img) }}" data-lightbox="product-gallery">
            <img src="{{ asset('storage/' . $img) }}" 
                 class="img-thumbnail shadow-sm"
                 style="width: 80px; height: 80px; object-fit: cover;" 
                 alt="Ảnh phụ">
          </a>
        @endforeach
      </div>
      @endif

      <!-- Nút giỏ hàng -->
      <div class="d-flex gap-2 mb-3">
        <button type="button" class="btn btn-primary w-50" onclick="addToCart({{ $product->id }}, {{ $product->price }}, {{ $product->sale ?? 0 }})">
    <i class="bi bi-cart"></i> Thêm vào giỏ
  </button>
        <a href="{{ route('user.cart.list') }}" class="btn btn-outline-secondary w-50">
          <i class="bi bi-bag-check"></i> Xem giỏ hàng
        </a>
      </div>

      <!-- Chính sách -->
      <div class="card border-0 bg-light p-3">
        <h6 class="fw-bold mb-2">🎁 Chính sách ưu đãi</h6>
        <ul class="mb-0 ps-3 small">
          <li>🚚 Giao hàng nhanh</li>
          <li>🔄 Đổi trả miễn phí toàn quốc</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Cột phải -->
  <div class="col-md-7">
    <!-- Thông tin cơ bản -->
    <div class="card p-3 shadow-sm mb-3">
      <h3 class="fw-bold mb-3">{{ $product->name }}</h3>
      <div class="row">
        <div class="col-md-6">
          <p><strong>📚 Thể loại:</strong> {{ $product->categ ?? 'Đang cập nhật' }}</p>
          <p><strong>✍️ Tác giả:</strong> {{ $product->author ?? 'Đang cập nhật' }}</p>
          <p><strong>💰 Giá gốc:</strong> {{ number_format($product->price, 0, ',', '.') }} đ</p>
        </div>
        <div class="col-md-6">
          <p><strong>🏢 NXB:</strong> {{ $product->publisher ?? 'Đang cập nhật' }}</p>
          <p><strong>🚚 Nhà cung cấp:</strong> {{ $product->supplier ?? 'Đang cập nhật' }}</p>
        </div>
      </div>

      <!-- Giá khuyến mãi -->
      <div class="mt-3">
        @if ($product->sale>0)
          <p class="mb-1">
            <span class="text-muted text-decoration-line-through me-2">
              {{ number_format($product->price, 0, ',', '.') }} đ
            </span>
            <small class="text-success">-{{ $product->sale }}%</small>
          </p>
          <p class="fw-bold text-danger fs-3">
            {{ number_format($product->price - ($product->price * $product->sale / 100), 0, ',', '.') }} đ
          </p>
        @else
          <p class="fw-bold text-primary fs-3">
            {{ number_format($product->price, 0, ',', '.') }} đ
          </p>
        @endif
      </div>
    </div>

    <!-- Thông tin chi tiết -->
    <div class="card p-3 shadow-sm mb-3">
      <h5 class="fw-bold mb-3">📋 Thông tin chi tiết</h5>
      <div class="row">
        <div class="col-md-6">
          <p><strong>Độ tuổi:</strong> {{ $product->age ?? 'Đang cập nhật' }}</p>
          <p><strong>Ngôn ngữ:</strong> {{ $product->language ?? 'Đang cập nhật' }}</p>
          <p><strong>Trọng lượng:</strong> {{ $product->weight ?? 'Đang cập nhật' }} g</p>
        </div>
        <div class="col-md-6">
          <p><strong>Kích thước:</strong> {{ $product->size ?? 'Đang cập nhật' }}</p>
          <p><strong>Tình trạng:</strong> {{ $product->status ?? 'Đang cập nhật' }}</p>
        </div>
      </div>
    </div>

    <!-- Mô tả -->
    <div class="card p-3 shadow-sm">
      <h5 class="fw-bold mb-3">📝 Mô tả sản phẩm</h5>
      <p class="mb-0">{{ $product->detail ?? 'Chưa có mô tả cho sản phẩm này.' }}</p>
    </div>
  </div>
</div>
 <suggest-products></suggest-products>  
<script>
async function addToCart(productId, price, sale) {
  try {
    const res = await axios.post('/api/cart', {
      product_id: productId,
      price: price,
      sale: sale||0
    }, {
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    });

    if (res.status === 201) {
      alert(`Đã thêm sản phẩm vào giỏ hàng!`);
    } else {
      alert(res.data.message);
    }
  } catch (err) {
    if (err.response && err.response.status === 401) {
      alert('Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng!');
    } else {
      console.error(err);
      alert('Có lỗi xảy ra khi thêm sản phẩm!');
    }
  }
}
</script>
<style>
.lb-close, .lb-prev, .lb-next {
  display: block !important;
  opacity: 1 !important;
  z-index: 9999 !important;
}

</style>
@endsection
