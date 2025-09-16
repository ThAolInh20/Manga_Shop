<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;   


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
    $labels = [];
    $series = [
        'pending'    => [],
        'processing' => [],
        'shipping'   => [],
        'completed'  => [],
        'returned'   => [],
        'canceled'   => [],
    ];
    $totalRevenue = 0;
    $totalOrders = 0;

    $startDate = null;
    $endDate = null;

    if ($type === 'week') {
        $today = Carbon::today();
        $start = $today->copy()->startOfWeek();
        $end = $today->copy()->endOfWeek();
        $startDate = $start;
        $endDate = $end;

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $labels[] = $date->format('d/m');

            foreach ($series as $key => &$arr) {
                $statusMap = [
                    'pending' => 0,
                    'processing' => 1,
                    'shipping' => 2,
                    'completed' => 3,
                    'returned' => 4,
                    'canceled' => 5,
                ];
                $status = $statusMap[$key];

                $count = Order::where('order_status', $status)
                    ->whereDate('created_at', $date)
                    ->count();

                $arr[] = $count;

                if ($status == 3) { // tính revenue cho đơn hoàn tất
                    $totalOrders += $count;
                    $totalRevenue += Order::where('order_status', $status)
                        ->whereDate('created_at', $date)
                        ->sum('total_price');
                }
            }
        }
    } elseif ($type === 'month') {
        $today = Carbon::today();
        $start = $today->copy()->startOfMonth();
        $end = $today->copy()->endOfMonth();
        $startDate = $start;
        $endDate = $end;

        $daysInMonth = $today->daysInMonth;
        for ($i = 0; $i < $daysInMonth; $i += 7) {
            $from = $start->copy()->addDays($i);
            $to = $from->copy()->addDays(6);
            if ($to->month != $start->month) $to = $from->copy()->endOfMonth();

            $labels[] = $from->format('d/m') . ' - ' . $to->format('d/m');

            foreach ($series as $key => &$arr) {
                $statusMap = [
                    'pending' => 0,
                    'processing' => 1,
                    'shipping' => 2,
                    'completed' => 3,
                    'returned' => 4,
                    'canceled' => 5,
                ];
                $status = $statusMap[$key];

                $count = Order::where('order_status', $status)
                    ->whereBetween('created_at', [$from, $to])
                    ->count();

                $arr[] = $count;

                if ($status == 3) {
                    $totalOrders += $count;
                    $totalRevenue += Order::where('order_status', $status)
                        ->whereBetween('created_at', [$from, $to])
                        ->sum('total_price');
                }
            }
        }
    } elseif ($type === 'year') {
        $year = Carbon::today()->year;
        $startDate = Carbon::createFromDate($year, 1, 1);
        $endDate = Carbon::createFromDate($year, 12, 31);

        for ($month = 1; $month <= 12; $month++) {
            $labels[] = Carbon::createFromDate($year, $month, 1)->format('M');

            foreach ($series as $key => &$arr) {
                $statusMap = [
                    'pending' => 0,
                    'processing' => 1,
                    'shipping' => 2,
                    'completed' => 3,
                    'returned' => 4,
                    'canceled' => 5,
                ];
                $status = $statusMap[$key];

                $count = Order::where('order_status', $status)
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->count();

                $arr[] = $count;

                if ($status == 3) {
                    $totalOrders += $count;
                    $totalRevenue += Order::where('order_status', $status)
                        ->whereMonth('created_at', $month)
                        ->whereYear('created_at', $year)
                        ->sum('total_price');
                }
            }
        }
    }

    // Tính tổng trạng thái
    $statusQuery = Order::query()->select('order_status', DB::raw('count(*) as total'))
        ->whereIn('order_status', [0,1,2,3,4,5]);

    if ($startDate && $endDate) {
        $statusQuery->whereBetween('created_at', [$startDate, $endDate]);
    }

    $statusCounts = $statusQuery
        ->groupBy('order_status')
        ->pluck('total', 'order_status')
        ->toArray();

    $statusSummary = [
        'pending'    => $statusCounts[0] ?? 0,
        'processing' => $statusCounts[1] ?? 0,
        'shipping'   => $statusCounts[2] ?? 0,
        'completed'  => $statusCounts[3] ?? 0,
        'returned'   => $statusCounts[4] ?? 0,
        'canceled'   => $statusCounts[5] ?? 0,
    ];

    return response()->json([
        'labels' => $labels,
        'series' => $series, // 👈 dữ liệu cho từng trạng thái
        'total_orders' => $totalOrders,
        'total_revenue' => $totalRevenue,
        'status_counts' => $statusSummary,
        'no_data' => ($totalRevenue == 0)
    ]);
}



     
    public function productPieChart(Request $request)
{
    $totalStock = Product::query()->sum('quantity');
    $totalSold  = Product::query()->sum('quantity_buy');

    $unsold = $totalStock - $totalSold;
    if ($unsold < 0) $unsold = 0; // tránh âm

    return response()->json([
        'labels' => ['Đã bán', 'Chưa bán'],
        'data' => [$totalSold, $totalStock]
    ]);
}
}
