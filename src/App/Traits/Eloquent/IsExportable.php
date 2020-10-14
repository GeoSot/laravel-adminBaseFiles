<?php

namespace GeoSot\BaseAdmin\App\Traits\Eloquent;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait IsExportable
{
    /**
     * @return array
     */
    public function getCsvColumns()
    {
        return $this->getArrayableItems(array_merge($this->getFillable(), $this->appends));
    }

    /**
     * @return array
     */
    public function toCsvArray()
    {
        return array_map(function ($it) {
            return is_array($it) ? implode(', ', $it) : $it;
        }, Arr::only($this->attributesToArray(), $this->getCsvColumns()));
    }

    /**
     * Export a csv file based on a collection of items.
     *
     * @param  Collection  $items
     * @return StreamedResponse
     * @throws Exception
     */
    public function exportToCsv(Collection $items)
    {
        $filename = now()->format('Y-m-d-his').'-'.$this->getTable().'.csv';


        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=".$filename,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];


        return response()->stream(function () use ($items) {
            $file = fopen('php://output', 'w');

            fputcsv($file, $this->getCsvColumns());

            foreach ($items as $item) {
                fputcsv($file, $item->toCsvArray());
            }

            fclose($file);
        }, 200, $headers);
    }
}
