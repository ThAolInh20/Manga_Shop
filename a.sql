-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping data for table laravel.accounts: ~5 rows (approximately)
INSERT INTO `accounts` (`id`, `name`, `email`, `image`, `password`, `role`, `address`, `phone`, `birth`, `gender`, `last_login`, `is_active`, `created_at`, `updated_at`, `updated_by`) VALUES
	(3, 'ADMIN', 'admin@gmail.com', NULL, '$2y$12$v0FZquEfsYJrP1meZeH8a.gZJXqh.o1dIQbaWhFrgymR95a4VMR.a', 0, NULL, NULL, NULL, NULL, NULL, 1, '2025-12-08 05:30:43', '2025-12-08 05:30:43', 1),
	(4, 'nam', 'hoaido@gmail.com', NULL, '$2y$12$vmxAQ1sO8Mt8UiX9.XLDCOL7dgZsWPe.9TfSAM5MxpnzuG6I9pusC', 2, NULL, NULL, NULL, NULL, NULL, 1, '2025-12-08 06:13:20', '2025-12-08 06:13:20', NULL),
	(5, 'trinh', 'Trinhmele@gmail.com', NULL, '$2y$12$CJpdlHpslqjX4OXmM/igu.hd4a71pwwjBqQsAM2ZL9pzdMoX/SpKy', 2, NULL, NULL, NULL, NULL, NULL, 1, '2025-12-08 06:13:55', '2025-12-08 06:13:55', NULL);

-- Dumping data for table laravel.carts: ~0 rows (approximately)

-- Dumping data for table laravel.categories: ~2 rows (approximately)
INSERT INTO `categories` (`id`, `name`, `detail`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
	(1, 'Manga', 'Danh mục truyện', '2025-12-08 05:27:35', '2025-12-17 06:46:41', '1', '3'),
	(2, 'Postcard', 'Danh mục thẻ', '2025-12-08 05:27:45', '2025-12-17 06:46:53', '1', '3');

-- Dumping data for table laravel.orders: ~4 rows (approximately)
INSERT INTO `orders` (`id`, `account_id`, `voucher_id`, `shipping_id`, `order_date`, `deliver_date`, `order_status`, `total_price`, `subtotal_price`, `payment_status`, `update_by`, `created_at`, `updated_at`) VALUES
	(1, 5, 2, 1, NULL, NULL, 2, 54720.00, 60800.00, 0, '1', '2025-12-08 06:18:31', '2025-12-08 06:39:25'),
	(2, 5, NULL, 1, NULL, NULL, 0, 50000.00, 50000.00, 0, '5', '2025-12-08 06:19:47', '2025-12-08 06:34:16'),
	(3, 5, NULL, 1, NULL, NULL, 2, 189000.00, 189000.00, 0, '3', '2025-12-17 06:50:52', '2025-12-17 07:41:52'),
	(4, 5, 2, 1, NULL, NULL, 2, 243000.00, 270000.00, 0, '3', '2025-12-17 07:33:50', '2025-12-17 07:34:52');

-- Dumping data for table laravel.products: ~11 rows (approximately)
INSERT INTO `products` (`id`, `category_id`, `name`, `age`, `code`, `quantity`, `images`, `images_sup`, `publisher`, `author`, `price`, `sale`, `price_sale`, `detail`, `categ`, `status`, `is_active`, `language`, `weight`, `size`, `quantity_buy`, `created_at`, `updated_at`) VALUES
	(2, 1, 'naruto', 12, NULL, 999, 'products/9Ukz7427QUPJDW2XcdWAd8r1rqOldfabdATb4Mxz.jpg', '["products\\/gjuoj06BZYfdnl6xmIEl7p6rZYPKckackaOLumd1.jpg"]', NULL, 'hello', 12000.00, 10.00, 10800.00, NULL, 'hành động', 'new', 1, NULL, NULL, NULL, 1, '2025-12-08 05:56:52', '2025-12-08 06:39:25'),
	(3, 1, 'postcard siêu nhân', 12, NULL, 999, 'products/FByEWboM7qFEneYd8pNwH6b3GVGhkgZ6sCZN2qKQ.webp', NULL, NULL, 'Masashi Kishimoto', 50000.00, 0.00, 50000.00, NULL, NULL, 'active', 1, NULL, NULL, NULL, 1, '2025-12-08 06:15:45', '2025-12-08 06:39:25'),
	(4, 1, 'postcard siêu nhân 2', 12, NULL, 1000, 'products/bs4zjeCayTrCdWPRIlCAfihd9Vh6BdLktjxnba76.webp', NULL, NULL, 'Masashi Kishimoto', 50000.00, 0.00, 50000.00, NULL, NULL, 'active', 1, NULL, NULL, NULL, 0, '2025-12-08 06:16:14', '2025-12-08 06:17:32'),
	(5, 1, 'Doraemon Đố Vui - Tập 1 - Doraemon Xuất Hiện (Tái Bản 2023)', 16, NULL, 990, 'products/58IA1a2vqUU75vM9DB9b4CbGhBBq8kITCTanAu5Z.webp', '["products\\/24736sxiA8kGZ8n3f9EiTOhRvN0s46jNCqQsenwS.webp"]', 'Nhà Xuất Bản Kim Đồng', 'Fujiko Pro', 30000.00, 10.00, 27000.00, 'Doraemon Đố Vui - Tập 1: Doraemon Xuất Hiện\r\n\r\nTuyển tập 126 câu đố thú vị về 11 tập đầu của bộ Doraemon truyện ngắn. Cùng xem các bạn giải được bao nhiêu câu nào!', 'Thiếu nhi', 'active', 1, 'Tiếng Việt', '310', '14.5 x 10.5 x 1.4 cm', 20, '2025-12-17 06:26:44', '2025-12-17 07:34:52'),
	(6, 1, 'Doraemon Đố Vui - Tập 3 - Doraemon Quá Khứ Và Tương Lai (Tái Bản 2023)', 16, NULL, 1000, 'products/58IA1a2vqUU75vM9DB9b4CbGhBBq8kITCTanAu5Z.webp', '["products\\/sCIc13557nDnosukpYVbrpzgQQJeaCA6kRMzkwLg.webp"]', 'Nhà Xuất Bản Kim Đồng', 'Fujiko Pro', 30000.00, 10.00, 27000.00, 'Doraemon Đố Vui - Tập 3: Doraemon Quá Khứ Và Tương Lai\r\n\r\nMời các bạn cùng đọc và thử sức với 128 câu đố về các tập truyện từ 23 đến 33 trong bộ Doraemon truyện ngắn này cùng chúng tớ!', 'Thiếu nhi', 'active', 1, 'Tiếng Việt', '310', '14.5 x 10.5 x 1.4 cm', 15, '2025-12-17 06:28:40', '2025-12-17 06:28:40'),
	(7, 1, 'Doraemon Đố Vui - Tập 3: Doraemon Quá Khứ Và Tương Lai', 12, NULL, 1000, 'products/58IA1a2vqUU75vM9DB9b4CbGhBBq8kITCTanAu5Z.webp', '["products\\/sCIc13557nDnosukpYVbrpzgQQJeaCA6kRMzkwLg.webp"]', 'Kim Đồng', 'Fujiko Pro', 30000.00, 10.00, 27000.00, 'Doraemon Đố Vui - Tập 4: Doraemon Chú Mèo Máy Thông Minh\n\nMời các bạn cùng đọc và thử sức với 128 câu đố về các tập truyện từ 23 đến 33 trong bộ Doraemon truyện ngắn này cùng chúng tớ!', 'Thiếu nhi', 'active', 1, 'Tiếng việt', '310', '14.5 x 10.5 x 1.4 cm', 18, '2025-12-17 06:39:40', '2025-12-17 06:39:40'),
	(8, 1, 'Doraemon Đố Vui - Tập 5: Doraemon Quá Khứ Và Tương Lai', 12, NULL, 997, 'products/58IA1a2vqUU75vM9DB9b4CbGhBBq8kITCTanAu5Z.webp', '["products\\/sCIc13557nDnosukpYVbrpzgQQJeaCA6kRMzkwLg.webp"]', 'Kim Đồng', 'Fujiko Pro', 30000.00, 10.00, 27000.00, 'Doraemon Đố Vui - Tập 4: Doraemon Chú Mèo Máy Thông Minh\n\nMời các bạn cùng đọc và thử sức với 128 câu đố về các tập truyện từ 23 đến 33 trong bộ Doraemon truyện ngắn này cùng chúng tớ!', 'Thiếu nhi', 'active', 1, 'Tiếng việt', '310', '14.5 x 10.5 x 1.4 cm', 23, '2025-12-17 06:39:40', '2025-12-17 07:41:52'),
	(9, 1, 'Doraemon Đố Vui - Tập 6: Doraemon Quá Khứ Và Tương Lai', 12, NULL, 996, 'products/58IA1a2vqUU75vM9DB9b4CbGhBBq8kITCTanAu5Z.webp', '["products\\/sCIc13557nDnosukpYVbrpzgQQJeaCA6kRMzkwLg.webp"]', 'Kim Đồng', 'Fujiko Pro', 30000.00, 10.00, 27000.00, 'Doraemon Đố Vui - Tập 4: Doraemon Chú Mèo Máy Thông Minh\n\nMời các bạn cùng đọc và thử sức với 128 câu đố về các tập truyện từ 23 đến 33 trong bộ Doraemon truyện ngắn này cùng chúng tớ!', 'Thiếu nhi', 'active', 1, 'Tiếng việt', '310', '14.5 x 10.5 x 1.4 cm', 24, '2025-12-17 06:39:40', '2025-12-17 07:41:52'),
	(10, 1, 'Doraemon Đố Vui - Tập 7: Doraemon Quá Khứ Và Tương Lai', 12, NULL, 1000, 'products/58IA1a2vqUU75vM9DB9b4CbGhBBq8kITCTanAu5Z.webp', '["products\\/sCIc13557nDnosukpYVbrpzgQQJeaCA6kRMzkwLg.webp"]', 'Kim Đồng', 'Fujiko Pro', 30000.00, 10.00, 27000.00, 'Doraemon Đố Vui - Tập 4: Doraemon Chú Mèo Máy Thông Minh\n\nMời các bạn cùng đọc và thử sức với 128 câu đố về các tập truyện từ 23 đến 33 trong bộ Doraemon truyện ngắn này cùng chúng tớ!', 'Thiếu nhi', 'active', 1, 'Tiếng việt', '310', '14.5 x 10.5 x 1.4 cm', 100, '2025-12-17 06:39:40', '2025-12-17 06:39:40'),
	(11, 1, 'Doraemon Đố Vui - Tập 8: Doraemon Quá Khứ Và Tương Lai', 12, NULL, 1000, 'products/58IA1a2vqUU75vM9DB9b4CbGhBBq8kITCTanAu5Z.webp', '["products\\/sCIc13557nDnosukpYVbrpzgQQJeaCA6kRMzkwLg.webp"]', 'Kim Đồng', 'Fujiko Pro', 30000.00, 10.00, 27000.00, 'Doraemon Đố Vui - Tập 4: Doraemon Chú Mèo Máy Thông Minh\n\nMời các bạn cùng đọc và thử sức với 128 câu đố về các tập truyện từ 23 đến 33 trong bộ Doraemon truyện ngắn này cùng chúng tớ!', 'Thiếu nhi', 'active', 1, 'Tiếng việt', '310', '14.5 x 10.5 x 1.4 cm', 10, '2025-12-17 06:39:40', '2025-12-17 06:39:40'),
	(12, 1, 'Doraemon Đố Vui - Tập 9: Doraemon Quá Khứ Và Tương Lai', 12, NULL, 1000, 'products/58IA1a2vqUU75vM9DB9b4CbGhBBq8kITCTanAu5Z.webp', '["products\\/sCIc13557nDnosukpYVbrpzgQQJeaCA6kRMzkwLg.webp"]', 'Kim Đồng', 'Fujiko Pro', 30000.00, 10.00, 27000.00, 'Doraemon Đố Vui - Tập 4: Doraemon Chú Mèo Máy Thông Minh\n\nMời các bạn cùng đọc và thử sức với 128 câu đố về các tập truyện từ 23 đến 33 trong bộ Doraemon truyện ngắn này cùng chúng tớ!', 'Thiếu nhi', 'active', 1, 'Tiếng việt', '310', '14.5 x 10.5 x 1.4 cm', 10, '2025-12-17 06:39:40', '2025-12-17 06:39:40');

-- Dumping data for table laravel.product_orders: ~6 rows (approximately)
INSERT INTO `product_orders` (`product_id`, `order_id`, `quantity`, `price`) VALUES
	(2, 1, 1, 10800.00),
	(3, 1, 1, 50000.00),
	(4, 2, 1, 50000.00),
	(5, 4, 10, 27000.00),
	(8, 3, 3, 27000.00),
	(9, 3, 4, 27000.00);

-- Dumping data for table laravel.product_suppliers: ~4 rows (approximately)
INSERT INTO `product_suppliers` (`id`, `product_id`, `supplier_id`, `date_import`, `import_by`, `import_price`, `quantity`, `detail`, `created_at`, `updated_at`) VALUES
	(1, 2, 1, NULL, 1, 10000.00, 1000, NULL, '2025-12-08 06:05:26', '2025-12-08 06:05:26'),
	(2, 3, 2, NULL, 3, 20000.00, 1000, NULL, '2025-12-08 06:17:15', '2025-12-08 06:17:15'),
	(3, 4, 2, NULL, 3, 20000.00, 1000, NULL,  '2025-12-08 06:17:32', '2025-12-08 06:17:32'),
	(4, 5, 2, NULL, 3, 20000.00, 1000, NULL,'2025-12-17 06:27:30', '2025-12-17 06:27:30');

-- Dumping data for table laravel.shippings: ~2 rows (approximately)
INSERT INTO `shippings` (`id`, `account_id`, `name_recipient`, `shipping_fee`, `shipping_address`, `phone_recipient`, `created_at`, `updated_at`) VALUES
	(1, 5, 'trình', 0.00, 'xóm 12, Xã Bình Sơn, Huyện Long Thành, Đồng Nai', '0332832323', '2025-12-08 06:18:57', '2025-12-17 06:51:14'),
	(2, 5, 'trinh', 0.00, 'x, Phường Tây Mỗ, Quận Nam Từ Liêm, Hà Nội', '0945454354', '2025-12-08 06:22:19', '2025-12-08 06:22:19');

-- Dumping data for table laravel.suppliers: ~2 rows (approximately)
INSERT INTO `suppliers` (`id`, `name`, `address`, `phone`, `email`, `tax_code`, `contract`, `link_contract`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Nettruyen', 'Hà Nội, Việt Nam', '099993232', 'aotrame@gmail.com', '093232323', NULL, NULL, 1, '2025-12-08 05:28:13', '2025-12-08 05:28:13'),
	(2, 'Fahasa', 'Hà Nội', '0849838298', 'fahasa@gmail.com', '232323', NULL, NULL, 1, '2025-12-08 06:16:50', '2025-12-08 06:16:50');

-- Dumping data for table laravel.vouchers: ~2 rows (approximately)
INSERT INTO `vouchers` (`id`, `code`, `sale`, `is_active`, `date_end`, `max_discount`, `created_at`, `updated_at`) VALUES
	(1, 'tesy', 100, 1, '2025-12-27', 10000000, '2025-12-08 06:04:35', '2025-12-08 06:04:35'),
	(2, 'halo', 10, 1, '2026-01-17', 1000000, '2025-12-08 06:04:55', '2025-12-08 06:04:55');

-- Dumping data for table laravel.website: ~0 rows (approximately)

-- Dumping data for table laravel.website_customs: ~1 rows (approximately)
INSERT INTO `website_customs` (`id`, `address`, `hotline`, `email`, `primary_color`, `background_color`, `background`, `font_family`, `created_at`, `updated_at`, `logo`, `banner_main`, `sub_banners`) VALUES
	(1, 'Hà Nội, Việt Nam', '0849838298', 'mangashop@example.com', '#ffcc00', '#ffffff', NULL, 'Arial, sans-serif', '2025-12-08 05:26:22', '2025-12-08 05:59:00', 'logo/logo.png', 'banner/main_banner.png', '["banner/sub/sub_banner_6936c9b908687.png", "banner/sub/sub_banner_6936c9b908f60.png", "banner/sub/sub_banner_6936c9b909933.webp", "banner/sub/sub_banner_6936c9b909e7b.png"]');

-- Dumping data for table laravel.wishlists: ~2 rows (approximately)
INSERT INTO `wishlists` (`id`, `account_id`, `product_id`, `created_at`, `updated_at`) VALUES
	(1, 5, 8, '2025-12-17 06:50:30', '2025-12-17 06:50:30'),
	(2, 5, 9, '2025-12-17 06:50:30', '2025-12-17 06:50:30');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
