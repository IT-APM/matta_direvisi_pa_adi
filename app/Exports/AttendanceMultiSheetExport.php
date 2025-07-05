<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AttendanceMultiSheetExport implements WithMultipleSheets
{
    private $trx;
    private $duration;
    public function __construct($trx, $duration) 
    {
        $this->trx = $trx;
        $this->duration = $duration;
    }

    public function sheets(): array
    {
        $trx = $this->trx;
        $duration = $this->duration;
        return [
            new AttendanceRawDataExport($trx),
            new AttendanceDurationExport($duration),
        ];
    }
}
