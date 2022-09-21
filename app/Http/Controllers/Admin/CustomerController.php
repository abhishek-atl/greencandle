<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Repositories\CustomerRepository;

use App\Http\Requests\StoreCustomerRequest;
use App\Repositories\GroupRepository;

class CustomerController extends Controller
{

    protected $customerRepository;
    protected $groupRepository;

    public function __construct(
        CustomerRepository $customerRepository,
        GroupRepository $groupRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->groupRepository = $groupRepository;
    }

    public function index()
    {
        return view('admin.modules.customers.index');
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
        $params['with'] = ['group'];
        return $this->customerRepository->getByParams($params);
    }

    public function add($id = null)
    {
        $isEdit = false;
        $customer = [];
        if (request('id')) {
            $isEdit = true;
            $customer = $this->customerRepository->getById(request('id'));
        }
        $groups = $this->groupRepository->getByParams(['active' => 1]);

        return view('admin.modules.customers.add', [
            'isEdit' => $isEdit,
            'customer' => $customer,
            'groups' => $groups,
        ]);
    }

    public function store(StoreCustomerRequest $request)
    {
        $params = $request->except(['_token', 'image', 'proengsoft_jsvalidation']);
        if ($request->id)
            $params['id'] = $request->id;

        $this->customerRepository->save($params);

        if ($request->id)
            $msg = 'Customer has been updated successfully!';
        else
            $msg = 'Customer has been added successfully!';

        return redirect()->route('admin.customers')->with('success_msg', $msg);
    }

    public function delete($id)
    {
        return $this->customerRepository->delete($id);
    }
}
