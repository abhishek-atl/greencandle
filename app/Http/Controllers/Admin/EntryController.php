<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CustomerRepository;
use App\Repositories\EntryRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EntryController extends Controller
{

    protected $entryRepository;
    protected $customerRepository;

    public function __construct(
        EntryRepository $entryRepository,
        CustomerRepository $customerRepository
    ) {
        $this->entryRepository = $entryRepository;
        $this->customerRepository = $customerRepository;
    }

    public function index()
    {
        return view('admin.modules.entries.index');
    }

    public function indexJson()
    {
        $params = array();
        if (request('search')) {
            $search = '%' . request('search') . '%';
            $params['like'] = ['start_date' => $search];
        }
        $params['order_by'] = request('order_by') ? request('order_by') : 'id';
        $params['order'] = request('order') ? request('order') : 'desc';
        $params['per_page'] = config('custom.db.per_page');
        $params['with'] = ['customer'];
        return $this->entryRepository->getByParams($params);
    }

    public function add()
    {
        $customers = $this->customerRepository->getByParams(['active' => 1]);
        return view('admin.modules.entries.add', [
            'customers' => $customers
        ]);
    }

    public function edit($id = null)
    {
        $entries = $this->entryRepository->getBatchById(request('id'));
        $selectedEntry = $this->entryRepository->getById(request('id'));
        return view('admin.modules.entries.edit', [
            'entries' => $entries,
            'selectedEntry' => $selectedEntry
        ]);
    }

    public function store(Request $request)
    {
        $params = [];
        $params['start_date'] = Carbon::createFromFormat('d/m/Y', $request->start_date);
        $params['end_date'] = Carbon::createFromFormat('d/m/Y', $request->end_date);

        foreach ($request->customer as $customerKey => $customerVal) {

            if ($request->amount[$customerKey]) {
                if ($request->id && $request->id[$customerKey]) {
                    $params['id'] = $request->id[$customerKey];
                }
                $params['customer_id'] = $customerKey;
                $params['amount'] = $request->amount[$customerKey];
                $params['brokerage'] = $request->brokerage[$customerKey] ?? 0;
                $this->entryRepository->save($params);
            }
        }

        if ($request->id)
            $msg = 'Entry has been updated successfully!';
        else
            $msg = 'Entry has been added successfully!';

        return redirect()->route('admin.entries')->with('success_msg', $msg);
    }

    public function delete($id)
    {
        return $this->entryRepository->delete($id);
    }
}
