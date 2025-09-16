<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;



class ChartController extends Controller
{
    // public function chartForOrder(){
    //     sẽ lấy dữ liệu từ order tại order_status=3,4,5
    //     cái này có chọn lọc theo tuần, tháng năm với số lượng tùy vào lúc chọn (tuần thì 7 ngày) (tháng thì số cột= số ngày/ chia 7)(năm thì 12 tháng)
    //     biểu đồ có thông tin số lượng đơn theo cột,
    //     ở dưới bảng tổng số lượng total_price, tổng số đơn hàng
    //   }
      public function chartForOrder(Request $request)
    {
        $type = $request->query('type', 'week'); // 'week', 'month', 'year'

        // Chỉ lấy đơn đã hoàn thành / đang giao / đã hủy? ở đây là 3,4,5
        $query = Order::query()->whereIn('order_status', [3,4,5]);
       
        $labels = [];
        $data = [];

        $totalRevenue = 0;
        $totalOrders = 0;

        if ($type === 'week') {
            $today = Carbon::today();
            $start = $today->copy()->subDays(6); // 7 ngày
            for ($i = 0; $i < 7; $i++) {
                $date = $start->copy()->addDays($i);
                $labels[] = $date->format('d/m');

                $count = (clone $query)->whereDate('created_at', $date)->count();
                $data[] = $count;

                $totalOrders += $count;
                $totalRevenue += (clone $query)->whereDate('created_at', $date)->sum('total_price');
            }
        } elseif ($type === 'month') {
            $today = Carbon::today();
            $start = $today->copy()->startOfMonth();
            $daysInMonth = $today->daysInMonth;

            for ($i = 0; $i < $daysInMonth; $i += 7) {
                $from = $start->copy()->addDays($i);
                $to = $from->copy()->addDays(6);
                if ($to->month != $start->month) $to = $from->copy()->endOfMonth();

                $labels[] = $from->format('d/m') . ' - ' . $to->format('d/m');

                $count = (clone $query)
                    ->whereBetween('created_at', [$from->toDateString(), $to->toDateString()])
                    ->count();

                $data[] = $count;

                $totalOrders += $count;
                $totalRevenue += (clone $query)
                    ->whereBetween('created_at', [$from->toDateString(), $to->toDateString()])
                    ->sum('total_price');
            }
        } elseif ($type === 'year') {
            $year = Carbon::today()->year;
            for ($month = 1; $month <= 12; $month++) {
                $labels[] = Carbon::createFromDate($year, $month, 1)->format('M');

                $count = (clone $query)
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->count();

                $data[] = $count;

                $totalOrders += $count;
                $totalRevenue += (clone $query)
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->sum('total_price');
            }
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue
        ]);
    }
}
