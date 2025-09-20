<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    protected $filter;

    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }

    public function collection()
    {
        $query = Order::with('account', 'voucher', 'shipping');
        

        // Lọc theo khoảng thời gian
        switch($this->filter) {
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'lastWeek':
                $query->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                break;
            case 'lastMonth':
                $query->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
            case 'lastYear':
                $query->whereYear('created_at', now()->subYear()->year);
                break;
        }

        // Chọn các cột cần export (bỏ deliver_date và order_date)
        return $query->get()->map(function($order){
            $order_statuses = [
                0 => "Chờ khách xác nhận đơn",
                1 => "Đang xử lý",
                2 => "Đang giao",
                3 => "Hoàn tất",
                4 => "Đổi trả",
                5 => "Đã hủy",
            ];
            return [
                'ID' => $order->id,
                'Khách hàng' => $order->account->name ?? '-',
                'Email' => $order->account->email ?? '-',
                'Trạng thái' => $order_statuses[$order->order_status] ?? 'Không xác định',
                'Tổng tiền' => $order->total_price,
                'Tổng tiền sản phẩm' =>$order->subtotal_price,
                'Voucher' => $order->voucher->code ?? '-',
                'Tên người nhận' => $order->shipping->name_recipient ?? '-',
                'SĐT người nhận' => $order->shipping->phone_recipient ?? '-',
                'Địa chỉ người nhận' => $order->shipping->shipping_address ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Khách hàng',
            'Email',
            'Trạng thái',
            'Tổng tiền',
            'Tổng tiền sản phẩm',
            'Voucher',
            'Tên người nhận',
            'SĐT người nhận',
            'Địa chỉ người nhận',
        ];
    }
}
