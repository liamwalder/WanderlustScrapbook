<?php

namespace App\Repositories;

use App\Entry;
use App\Location;

/**
 * Class EntryRepository
 * @package App\Repositories
 */
class EntryRepository {

    /**
     * @param $id
     * @return mixed
     */
    public function getEntry($id)
    {
        return Entry::findOrFail($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getEntries()
    {
        return Entry::with(['files' => function($query) {
                $query->orderBy('created_at', 'DESC');
            }])
            ->with('entryLocations')
            ->with('location')
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    /**
     * @param Entry $entry
     * @param $data
     * @return Entry
     */
    public function updateEntry(Entry $entry, $data)
    {
        $entry->fill($data);
        $entry->save();
        return $entry;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function createEntry($data)
    {
        return Entry::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'location_id' => $data['location']
        ]);
    }

}