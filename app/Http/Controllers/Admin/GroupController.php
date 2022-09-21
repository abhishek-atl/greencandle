<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\GroupRepository;
use App\Http\Requests\StoreGroupRequest;

class GroupController extends Controller
{

    protected $groupRepository;
    protected $customerRepository;

    public function __construct(
        GroupRepository $groupRepository
    ) {
        $this->groupRepository = $groupRepository;
    }

    public function index()
    {
        return view('admin.modules.groups.index');
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
        return $this->groupRepository->getByParams($params);
    }

    public function add()
    {
        $isEdit = false;
        $group = [];
        if (request('id')) {
            $isEdit = true;
            $group = $this->groupRepository->getById(request('id'));
        }

        return view('admin.modules.groups.add', [
            'isEdit' => $isEdit,
            'group' => $group
        ]);
    }

    public function store(StoreGroupRequest $request)
    {
        $params = $request->except(['_token', 'image', 'proengsoft_jsvalidation']);
        if ($request->id)
            $params['id'] = $request->id;

        $this->groupRepository->save($params);

        if ($request->id)
            $msg = 'Group has been updated successfully!';
        else
            $msg = 'Group has been added successfully!';

        return redirect()->route('admin.groups')->with('success_msg', $msg);
    }

    public function delete($id)
    {
        return $this->groupRepository->delete($id);
    }
}
