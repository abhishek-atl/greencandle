<?php


namespace App\Repositories;

use App\Models\Entry;
use App\Repositories\BaseRepository;


class EntryRepository extends BaseRepository
{
    protected $entry;

    public function __construct(
        Entry $entry
    ) {
        parent::__construct($entry);
        $this->entry = $entry;
    }

    public function getBatchById($id)
    {
        $entry = Entry::where('id', $id)->first();
        $entries = Entry::where('start_date', $entry->start_date)->with('customer')->get();
        return $entries;
    }
}
