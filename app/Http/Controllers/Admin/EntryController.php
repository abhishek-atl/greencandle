<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Repositories\EntryRepository;

use App\Http\Requests\StoreEntryRequest;
use App\Repositories\CustomerRepository;
use Illuminate\Support\Carbon;

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
            $params['like'] = ['text' => $search];
        }
        $params['order_by'] = request('order_by') ? request('order_by') : 'id';
        $params['order'] = request('order') ? request('order') : 'desc';
        $params['per_page'] = config('custom.db.per_page');
        $params['with'] = ['customer'];
        return $this->entryRepository->getByParams($params);
    }

    public function add($id = null)
    {
        $isEdit = false;
        $entry = [];
        if (request('id')) {
            $isEdit = true;
            $entry = $this->entryRepository->getById(request('id'));
        }
        $customers = $this->customerRepository->getByParams(['active' => 1]);
        return view('admin.modules.entries.add', [
            'isEdit' => $isEdit,
            'entry' => $entry,
            'customers' => $customers
        ]);
    }

    public function store(StoreEntryRequest $request)
    {
        $params = $request->except(['_token', 'image', 'proengsoft_jsvalidation']);
        if ($request->id)
            $params['id'] = $request->id;

        $params['start_date'] = Carbon::createFromFormat('d/m/Y', $request->start_date);
        $params['end_date'] = Carbon::createFromFormat('d/m/Y', $request->end_date);

        $this->entryRepository->save($params);

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
