# Hướng dẫn chạy dự án khi lần đầu tải về (First-time Setup Guide)

Tài liệu này hướng dẫn bạn các bước chi tiết để khởi chạy dự án **Manga_Shop** trên môi trường Docker sau khi clone code về máy lần đầu.

## I. Yêu cầu hệ thống (Prerequisites)
- Đã cài đặt **Docker Desktop** (cho Windows/macOS) hoặc Docker Engine (cho Linux).
- Đã cài đặt **Git** (nếu cần clone mã nguồn).

---

## II. Các bước thiết lập lần đầu (First-time Installation)

Thực hiện lần lượt các bước sau tại thư mục gốc của dự án:

### Step 1: Thiết lập file môi trường cấu hình `.env`
Nhân bản file `.env.example` thành file `.env` chính thức:
- **Trên Windows (cmd):**
  ```cmd
  copy .env.example .env
  ```
- **Trên Git Bash / PowerShell / Linux / macOS:**
  ```bash
  cp .env.example .env
  ```

> [!NOTE]  
> Mặc định dự án sử dụng các credentials được cấu hình sẵn trong docker-compose. Hãy chắc chắn cấu hình database trong file `.env` của bạn như sau:
> ```env
> DB_CONNECTION=mysql
> DB_HOST=db
> DB_PORT=3306
> DB_DATABASE=manga
> DB_USERNAME=manga
> DB_PASSWORD=secret
> DB_ROOT_PASSWORD=root_secret
> ```

---

### Step 2: Khởi động các container Docker
Chạy lệnh sau để tải các image cần thiết và khởi tạo các container trong chế độ nền (detached mode):
```bash
docker compose up -d
```
*Lưu ý: Nếu bạn muốn thiết lập lại hoàn toàn cơ sở dữ liệu sau này, bạn có thể xóa volume cũ đi và chạy lại bằng lệnh `docker compose down -v` trước khi `up -d`.*

---

### Step 3: Cài đặt các thư viện phụ thuộc của PHP (Composer)
Cài đặt toàn bộ các dependency được khai báo trong `composer.json` thông qua container `app`:
```bash
docker compose exec app composer install
```

---

### Step 4: Tạo mã khóa ứng dụng (App Key)
Tạo khóa bảo mật cho ứng dụng Laravel:
```bash
docker compose exec app php artisan key:generate
```

---

### Step 5: Cài đặt các thư viện Front-end (NPM)
Cài đặt các thư viện Node.js cần thiết để biên dịch file giao diện:
```bash
docker compose exec app npm install
```

---

### Step 6: Chạy Migration và Seed dữ liệu mẫu
Khởi tạo cấu trúc các bảng dữ liệu trong MySQL và nạp dữ liệu mẫu ban đầu:
```bash
# Tạo các bảng cơ sở dữ liệu
docker compose exec app php artisan migrate

# Nạp dữ liệu mẫu (Seeding)
docker compose exec app php artisan db:seed
```

---

## III. Hướng dẫn chạy dự án hàng ngày & Khi sửa code (Daily Development & Live Preview)

Sau khi đã cài đặt xong lần đầu, mỗi khi bắt đầu làm việc hoặc **sửa đổi source code và muốn xem các thay đổi trực tiếp trên trình duyệt**, bạn thực hiện các bước sau:

1. **Khởi động các container Docker:**
   ```bash
   docker compose up -d
   ```

2. **Khởi động server biên dịch Vite (Chạy mỗi khi sửa code để xem trực tiếp):**
   Chạy lệnh này để Vite tự động theo dõi các file giao diện (CSS, JS, Blade), biên dịch và cập nhật tức thì (hot reload) lên trình duyệt mỗi khi bạn lưu file:
   ```bash
   docker compose exec app npm run dev
   ```

3. **Truy cập ứng dụng:**
   - Trang web (Nginx): [http://localhost:8000](http://localhost:8000)


---

## IV. Một số lệnh hữu ích thường gặp (Commands Reference)

- **Truy cập trực tiếp vào Terminal của container `app`:**
  ```bash
  docker compose exec app sh
  ```
- **Tắt toàn bộ hệ thống container:**
  ```bash
  docker compose down
  ```
- **Xem log hệ thống (Ví dụ xem log của cơ sở dữ liệu `db`):**
  ```bash
  docker compose logs db
  ```
