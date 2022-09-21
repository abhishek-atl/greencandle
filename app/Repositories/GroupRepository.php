<?php


namespace App\Repositories;

use App\Models\Group;
use App\Repositories\BaseRepository;

class GroupRepository extends BaseRepository
{
    protected $group;

    public function __construct(
        Group $group
    ) {
        parent::__construct($group);
        $this->group = $group;
    }
}
