{{-- resources/views/privacy.blade.php --}}
@extends('user.layouts.app')

@section('title', 'Chính sách bảo mật')

@section('content')
<div class="container my-5">
  <div class="card shadow-lg rounded-2xl">
    <div class="card-body p-5">
      <h1 class="mb-4 text-center fw-bold">🔒 CHÍNH SÁCH BẢO MẬT</h1>

      <p>
        MangaShop cam kết bảo mật những thông tin mang tính riêng tư của khách hàng. 
        Quý khách vui lòng đọc kỹ bản “Chính sách bảo mật” dưới đây để hiểu rõ hơn 
        những cam kết mà chúng tôi thực hiện, nhằm tôn trọng và bảo vệ quyền lợi của người truy cập.
      </p>

      <h3 class="mt-4">1. Thu thập thông tin cá nhân</h3>
      <p>
        Các thông tin thu thập thông qua website  sẽ giúp chúng tôi:
      </p>
      <ul>
        <li>Hỗ trợ khách hàng khi mua sản phẩm.</li>
        <li>Giải đáp thắc mắc khách hàng.</li>
        <li>Cung cấp cho bạn thông tin mới nhất trên website.</li>
        <li>Xem xét và nâng cấp nội dung, giao diện website.</li>
        <li>Thực hiện các hoạt động quảng bá sản phẩm/dịch vụ của MangaShop.</li>
      </ul>
      <p>
        Để truy cập và sử dụng một số dịch vụ tại MangaShop, quý khách có thể cần đăng ký 
        với chúng tôi thông tin cá nhân (Email, Họ tên, SĐT...). 
        Mọi thông tin khai báo phải đảm bảo tính chính xác và hợp pháp. 
        MangaShop không chịu trách nhiệm pháp lý liên quan đến thông tin khai báo sai lệch.
      </p>
      <p>
        Chúng tôi cũng có thể thu thập thông tin về số lần viếng thăm, số trang quý khách xem, 
        số liên kết click và các dữ liệu liên quan khác phục vụ mục đích tối ưu trải nghiệm.
      </p>

      <h3 class="mt-4">2. Sử dụng thông tin cá nhân</h3>
      <p>
        MangaShop thu thập và sử dụng thông tin cá nhân với mục đích phù hợp, tuân thủ nội dung “Chính sách bảo mật”.
      </p>
      <p>
        Khi cần thiết, chúng tôi có thể liên hệ với bạn qua các hình thức như: thư ngỏ, đơn đặt hàng, 
        thư cảm ơn, thông tin kỹ thuật và bảo mật, hoặc email định kỳ về sản phẩm/dịch vụ mới, sự kiện sắp tới.
      </p>

      <h3 class="mt-4">3. Chia sẻ thông tin cá nhân</h3>
      <p>
        Ngoại trừ các trường hợp nêu trong chính sách này, MangaShop cam kết không tiết lộ thông tin cá nhân ra ngoài.
      </p>
      <p>
        Chúng tôi chỉ chia sẻ thông tin khi: (a) có yêu cầu từ cơ quan pháp luật; 
        (b) cần thiết để bảo vệ quyền lợi hợp pháp của MangaShop.
      </p>

      <h3 class="mt-4">4. Truy xuất thông tin cá nhân</h3>
      <p>
        Bất cứ thời điểm nào, quý khách cũng có thể truy cập và chỉnh sửa thông tin cá nhân 
        thông qua tài khoản hoặc liên hệ bộ phận hỗ trợ.
      </p>

      <h3 class="mt-4">5. Bảo mật thông tin cá nhân</h3>
      <p>
        Khi gửi thông tin cho chúng tôi, bạn đồng ý với các điều khoản nêu trên. 
        MangaShop cam kết bảo mật thông tin cá nhân bằng nhiều công nghệ bảo mật như PCI, SSL... 
        để ngăn chặn truy cập, sử dụng hoặc tiết lộ trái phép.
      </p>
      <p>
        Tuy nhiên, không có dữ liệu nào truyền trên Internet an toàn tuyệt đối. 
        Do đó, MangaShop không thể đảm bảo 100% thông tin luôn được bảo mật, 
        và không chịu trách nhiệm khi xảy ra truy cập trái phép nếu lỗi xuất phát từ phía người dùng 
        (ví dụ chia sẻ mật khẩu, không đăng xuất khi dùng máy công cộng).
      </p>

      <h3 class="mt-4">6. Quy định về “Spam”</h3>
      <p>
        MangaShop phản đối spam và chỉ gửi email cho khách hàng khi có đăng ký hoặc sử dụng dịch vụ. 
        Chúng tôi cam kết không bán, cho thuê hay chia sẻ email khách hàng cho bên thứ ba. 
        Nếu nhận được email ngoài ý muốn, vui lòng liên hệ với chúng tôi qua mục <strong>Liên hệ</strong>.
      </p>

      <h3 class="mt-4">7. Thay đổi về chính sách</h3>
      <p>
        MangaShop có quyền thay đổi nội dung chính sách bảo mật mà không cần thông báo trước, 
        để phù hợp hơn với nhu cầu kinh doanh và phản hồi từ khách hàng. 
        Khi có thay đổi, chúng tôi sẽ cập nhật thời gian “Cập nhật lần cuối” bên dưới.
      </p>

      <h3 class="mt-4">8. Thông tin liên hệ</h3>
      <p>
        MangaShop rất cảm ơn và khuyến khích quý khách đóng góp ý kiến để cải thiện dịch vụ.  
        Mọi ý kiến đóng góp xin liên hệ qua SĐT: <strong>090 998 28 73</strong> 
        hoặc gửi email qua mục <strong>Liên hệ</strong> trên website.
      </p>

      <p class="mt-5 text-muted fst-italic">
        Cập nhật lần cuối: {{ date('d/m/Y H:i') }}
      </p>
    </div>
  </div>
</div>
@endsection
