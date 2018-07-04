<?php

namespace App\Admin\Grid;

use App\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExpoter extends AbstractExporter
{
    public function export()
    {
        Excel::create('Filename', function($excel) {

            $excel->sheet('Sheetname', function($sheet) {

                // 这段逻辑是从表格数据中取出需要导出的字段
                $rows = collect($this->getData())->map(function ($item) {
                    return array_only($item, ['name', 'id', 'password', 'id', 'password']);
                });

                $sheet->rows($rows);

            });

        })->export('csv');
    }
}