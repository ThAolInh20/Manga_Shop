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
    $type = $request->query('type', 'week');

    $labels = [];
    $series = [
        'pending'    => [],
        'processing' => [],
        'shipping'   => [],
        'completed'  => [],
        'returned'   => [],
        'canceled'   => [],
    ];
    $revenueSeries = [];

    $totalRevenue = 0;
    $totalOrders = 0;

    $today = Carbon::today();

    // Xác định khoảng thời gian
    switch ($type) {
        case 'week':
            $startDate = $today->copy()->startOfWeek();
            $endDate   = $today->copy()->endOfWeek();
            $granularity = 'day';
            break;
        case 'lastWeek':
            $startDate = $today->copy()->subWeek()->startOfWeek();
            $endDate   = $today->copy()->subWeek()->endOfWeek();
            $granularity = 'day';
            break;
        case 'month':
            $startDate = $today->copy()->startOfMonth();
            $endDate   = $today->copy()->endOfMonth();
            $granularity = 'week';
            break;
        case 'lastMonth':
            $lastMonth = $today->copy()->subMonth();
            $startDate = $lastMonth->copy()->startOfMonth();
            $endDate   = $lastMonth->copy()->endOfMonth();
            $granularity = 'week';
            break;
        case 'year':
            $year = $today->year;
            $startDate = Carbon::createFromDate($year, 1, 1);
            $endDate   = Carbon::createFromDate($year, 12, 31);
            $granularity = 'month';
            break;
        case 'lastYear':
            $year = $today->subYear()->year;
            $startDate = Carbon::createFromDate($year, 1, 1);
            $endDate   = Carbon::createFromDate($year, 12, 31);
            $granularity = 'month';
            break;
        
        default:
            $startDate = $today->copy()->startOfWeek();
            $endDate   = $today->copy()->endOfWeek();
            $granularity = 'day';
            break;
    }

    // Map trạng thái
    $statusMap = [
        'pending' => 0,
        'processing' => 1,
        'shipping' => 2,
        'completed' => 3,
        'returned' => 4,
        'canceled' => 5,
    ];

    // Loop dữ liệu theo granularity
    if ($granularity === 'day') {
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $labels[] = $date->format('d/m');

            // doanh thu trong ngày
            $dailyRevenue = Order::where('order_status', 3)
                ->whereDate('created_at', $date)
                ->sum('total_price');
            $revenueSeries[] = $dailyRevenue;

            foreach ($series as $key => &$arr) {
                $count = Order::where('order_status', $statusMap[$key])
                    ->whereDate('created_at', $date)
                    ->count();

                $arr[] = $count;

                if ($statusMap[$key] == 3) {
                    $totalOrders += $count;
                    $totalRevenue += $dailyRevenue;
                }
            }
        }
    } elseif ($granularity === 'week') {
        $days = $startDate->diffInDays($endDate) + 1;
        for ($i = 0; $i < $days; $i += 7) {
            $from = $startDate->copy()->addDays($i);
            $to   = $from->copy()->addDays(6);
            if ($to->gt($endDate)) $to = $endDate;

            $labels[] = $from->format('d/m') . ' - ' . $to->format('d/m');

            $weeklyRevenue = Order::where('order_status', 3)
                ->whereBetween('created_at', [$from, $to])
                ->sum('total_price');
            $revenueSeries[] = $weeklyRevenue;

            foreach ($series as $key => &$arr) {
                $count = Order::where('order_status', $statusMap[$key])
                    ->whereBetween('created_at', [$from, $to])
                    ->count();

                $arr[] = $count;

                if ($statusMap[$key] == 3) {
                    $totalOrders += $count;
                    $totalRevenue += $weeklyRevenue;
                }
            }
        }
    } elseif ($granularity === 'month') {
        $year = $startDate->year;
        for ($month = 1; $month <= 12; $month++) {
            $labels[] = Carbon::createFromDate($year, $month, 1)->format('M');

            $monthlyRevenue = Order::where('order_status', 3)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('total_price');
            $revenueSeries[] = $monthlyRevenue;

            foreach ($series as $key => &$arr) {
                $count = Order::where('order_status', $statusMap[$key])
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->count();

                $arr[] = $count;

                if ($statusMap[$key] == 3) {
                    $totalOrders += $count;
                    $totalRevenue += $monthlyRevenue;
                }
            }
        }
    }

    // Tổng trạng thái
    $statusQuery = Order::query()->select('order_status', DB::raw('count(*) as total'))
        ->whereIn('order_status', array_values($statusMap))
        ->whereBetween('created_at', [$startDate, $endDate]);

    $statusCounts = $statusQuery->groupBy('order_status')->pluck('total', 'order_status')->toArray();

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
        'series' => $series,
        'revenue_series' => $revenueSeries,
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
public function chartForSuppliers(Request $request)
{
    $filter = $request->get('filter', 'all');

    $query = DB::table('product_suppliers as ps')
        ->join('suppliers as s', 's.id', '=', 'ps.supplier_id')
        ->where('s.is_active', 1);

    if ($filter === 'week') {
        $query->whereBetween('ps.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    } elseif ($filter === 'month') {
        $query->whereMonth('ps.created_at', now()->month)
              ->whereYear('ps.created_at', now()->year);
    } elseif ($filter === 'year') {
        $query->whereYear('ps.created_at', now()->year);
    }

    $data = $query->select(
        's.name',
        DB::raw('SUM(ps.quantity) as total_quantity'),
        DB::raw('SUM(ps.import_price * ps.quantity) as total_cost')
    )
    ->groupBy('s.id', 's.name')
    ->get();

    // Nếu gọi AJAX -> trả JSON
    if ($request->ajax()) {
        return response()->json([
            'labels' => $data->pluck('name'),
            'quantities' => $data->pluck('total_quantity'),
            'costs' => $data->pluck('total_cost'),
        ]);
    }

    // Lần đầu vào page -> render blade
    return view('admin.suppliers.chart');
}
}
