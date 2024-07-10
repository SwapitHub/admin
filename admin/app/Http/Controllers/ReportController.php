<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderModel;
use Illuminate\Support\Facades\DB;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // user by month
        $chart_options1 = [
            'chart_title' => 'Users by months',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\User',
            'group_by_field' => 'created_at',
            'group_by_period' => 'month',
            'chart_type' => 'bar',
        ];

        $users = new LaravelChart($chart_options1);


        $chart_options_transaction = [
            'chart_title' => 'Transactions by dates',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\TransactionModel',
            'group_by_field' => 'created_at',
            'group_by_period' => 'day',
            'aggregate_function' => 'sum',
            'aggregate_field' => 'amount',
            'chart_type' => 'line',
        ];

        $transaction = new LaravelChart($chart_options_transaction);

        $chart_options_created_user = [
            'chart_title' => 'Users by names',
            'report_type' => 'group_by_string',
            'model' => 'App\Models\User',
            'group_by_field' => 'first_name',
            'chart_type' => 'pie',
            'filter_field' => 'created_at',
            'filter_period' => 'month', // show users only registered this month
        ];

        $user_this_month = new LaravelChart($chart_options_created_user);


        $settings1 = [
            'chart_title'           => 'Users',
            'chart_type'            => 'line',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\User',
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'filter_days'           => '30',
            'group_by_field_format' => 'Y-m-d H:i:s',
            'column_class'          => 'col-md-12',
            'entries_number'        => '5',
            'translation_key'       => 'user',
            'continuous_time'       => true,
        ];
        $settings2 = [
            'chart_title'           => 'Orders',
            'chart_type'            => 'line',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\OrderModel',
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'filter_days'           => '30',
            'group_by_field_format' => 'Y-m-d H:i:s',
            'column_class'          => 'col-md-12',
            'entries_number'        => '5',
            'translation_key'       => 'order',
            'continuous_time'       => true,

        ];

        $users_order = new LaravelChart($settings1, $settings2);


        return view('admin.report', compact('users', 'transaction', 'user_this_month', 'users_order'));
    }

    public function revenue()
    {
        $chart_options1 = [
            'chart_title'           => 'Total Revenue (Daily)',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\OrderModel', // Assuming your model is named Order
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'sum',
            'aggregate_field'       => 'amount', // Field to sum up for revenue
            'chart_type'            => 'line',
        ];
        $dailyRevenue = new LaravelChart($chart_options1);

        $chart_options2 = [
            'chart_title'           => 'Total Revenue (Weekly)',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\OrderModel', // Assuming your model is named Order
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'week',
            'aggregate_function'    => 'sum',
            'aggregate_field'       => 'amount', // Field to sum up for revenue
            'chart_type'            => 'line',
        ];
        $weekleyRevenue = new LaravelChart($chart_options2);

        $chart_options3 = [
            'chart_title'           => 'Total Revenue (Monthly)',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\OrderModel', // Assuming your model is named Order
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'month',
            'aggregate_function'    => 'sum',
            'aggregate_field'       => 'amount', // Field to sum up for revenue
            'chart_type'            => 'bar',
        ];
        $monthlyRevenue = new LaravelChart($chart_options3);

        $chart_options4 = [
            'chart_title'           => 'Total Revenue (Yearly/Gross)',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\OrderModel', // Assuming your model is named Order
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'year',
            'aggregate_function'    => 'sum',
            'aggregate_field'       => 'amount', // Field to sum up for revenue
            'chart_type'            => 'bar',
        ];
        $yearlyRevenue = new LaravelChart($chart_options4);

        ## average order value
        // Fetch data grouped by year
        $orders = OrderModel::selectRaw('YEAR(created_at) as year, SUM(amount) as total_revenue, COUNT(*) as order_count')
            ->groupBy('year')
            ->get();

        // Calculate average order value for each year
        $averageOrderValues = $orders->map(function ($order) {
            return [
                'year' => $order->year,
                'average_order_value' => $order->total_revenue / $order->order_count,
            ];
        });

        ## average basket value
        // Fetch data grouped by year
        $aveorders = OrderModel::selectRaw('YEAR(created_at) as year, SUM(amount) as total_revenue, COUNT(*) as order_count')
            ->groupBy('year')
            ->get();

        // Calculate average basket value for each year
        $averageBasketValues = $orders->map(function ($aveorders) {
            return [
                'year' => $aveorders->year,
                'average_basket_value' => $aveorders->total_revenue / $aveorders->order_count,
            ];
        });

        ## net revenue
        $netRevenueData = OrderModel::leftJoin('refund', 'orders.order_id', '=', 'refund.order_id')
            ->select(
                DB::raw('YEAR(orders.created_at) as year'),
                DB::raw('SUM(orders.amount) - IFNULL(SUM(refund.amount), 0) as net_revenue')
            )
            ->groupBy('year')
            ->get();

            $netRevenue = $netRevenueData->map(function($data) {
                return [
                    'year' => $data->year,
                    'net_revenue' => $data->net_revenue,
                ];
            });


        return view('admin.revenue', compact('dailyRevenue', 'weekleyRevenue', 'monthlyRevenue', 'yearlyRevenue', 'averageOrderValues', 'averageBasketValues','netRevenue'));
    }

    public function showAOVChart()
    {
        // Fetch data grouped by year
        $orders = OrderModel::selectRaw('YEAR(created_at) as year, SUM(amount) as total_revenue, COUNT(*) as order_count')
            ->groupBy('year')
            ->get();

        // Calculate average order value for each year
        $averageOrderValues = $orders->map(function ($order) {
            return [
                'year' => $order->year,
                'average_order_value' => $order->total_revenue / $order->order_count,
            ];
        });

        return view('admin.aov_chart', compact('averageOrderValues'));
    }
}
