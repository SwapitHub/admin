<?php

namespace App\Http\Controllers;

use App\Models\TransactionModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve search and order status from the request
        $search = $request->input('search');
        $orderStatus = $request->input('txnstatus');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Initialize the query
        $query = DB::table('transaction')
            ->orderBy('transaction.id', 'desc');

        // Add search filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('transaction.id', 'like', "%$search%")
                    ->orWhere('transaction.order_id', 'like', "%$search%")
                    ->orWhere('transaction.ref_num', 'like', "%$search%")
                    ->orWhere('transaction.transaction_id', 'like', "%$search%");
            });
        }


        // Add order status filter
        if (!empty($orderStatus) && $orderStatus !== 'All') {
            $query->where('transaction.status', $orderStatus);
        }

        // Add date range filter
        if (!empty($fromDate) && !empty($toDate)) {
            $query->whereBetween('transaction.created_at', [$fromDate, $toDate]);
        }


        // Paginate the results
        $txn = $query->paginate(10)->appends($request->all());
            // Get the last executed query

        $data['transactions'] = $txn;
        return view('admin.transactions', $data);

    }
}
