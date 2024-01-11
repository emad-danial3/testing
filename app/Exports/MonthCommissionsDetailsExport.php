<?php

namespace App\Exports;

use App\Models\UserCommission;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthCommissionsDetailsExport implements FromCollection, WithHeadings
{

    private $start_date;
    private $end_date;
    private $data;

    public function __construct($start_date, $end_date, $data)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $returned = array();
        foreach ($this->data as $row) {
            $exported['Parent ID'] = $row->commission->user->id;
            $exported['Parent Name'] = $row->commission->user->full_name;
            $exported['Child ID'] = $row->user_id;
            $exported['Child Name'] = $row->user->full_name;
            $exported['Child Phone'] = $row->user->phone;
            $exported['Commission ID'] = $row->commission_id;
            $exported['Level'] = $row->level;
            $exported['Is New'] = $row->new;
            $exported['total orders has commission'] = $row->total_order_has_commission;
            $exported['Commission Percentage'] = $row->commission_percentage;
            $exported['Commission Value'] = $row->commission_value;
            $exported['Commission Created_at'] = $row->created_at;
            $exported['Child Created_at'] = $row->user->created_at;
            array_push($returned, $exported);
        }
        return collect($returned);
    }

    public function headings(): array
    {
        return
            ['Parent ID',
                'Parent Name',
                'Child ID',
                'Child Name',
                'Child Phone',
                'Commission ID',
                'Level',
                'Is New',
                'total orders has commission',
                'Commission Percentage',
                'Commission Value',
                'Commission Created_at',
                'Child Created_at',
            ];
    }

}
