<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $defaultDate = $request->date ?  Carbon::createFromFormat('d/m/Y', $request->date) : Carbon::parse('last monday');

        $records = DB::table('entries')->whereDate('start_date', $defaultDate)
            ->select([
                'groups.name',
                DB::raw('sum(amount) as total_amount'),
                DB::raw('sum(brokerage) as total_brokerage'),
                DB::raw('sum(brokerage + amount) as grand_total'),
            ])
            ->leftjoin('customers', 'entries.customer_id', 'customers.id')
            ->leftjoin('groups', 'customers.group_id', 'groups.id')
            ->groupBy(['group_id', 'groups.name'])
            ->get();

        return view('admin.modules.reports.index', [
            'defaultDate' => $defaultDate->format('d/m/Y'),
            'records' => $records
        ]);
    }
}
