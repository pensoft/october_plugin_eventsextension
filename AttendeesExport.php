<?php

namespace Pensoft\Eventsextension;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendeesExport implements FromCollection, WithHeadingRow, WithHeadings
{

	private $collection;

	public function __construct(Collection $collection)
	{
		$this->collection = $collection;
	}

	public function headings(): array
	{	
		return collect($this->collection->first())->keys()->all();
	}

	public function collection()
    {
        return $this->collection;
    }
}
