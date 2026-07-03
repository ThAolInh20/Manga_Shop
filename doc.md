***
Hướng dẫn bắt đầu khi vào dự án docker
***
# Yêu cầu
- Docker bản mới nhất
# Cài đặt
## Git clone dự án
```bash
    git clone https://github.com/ThAolInh20/Manga_Shop.git
```
## Tạo 1 .env theo .env.example và thêm các thông tin cần thiết
## Chạy 
```bash
    docker compose up -d
```
Đợi docker up xong ...

Khi chạy xong thì truy cập vào container của app
```bash
    docker compose exec -it app sh
```
Tải các package cần thiết
```bash
    composer install
    npm install
```
Link thư mục ảnh
```bash
php artisan storage:link
```
