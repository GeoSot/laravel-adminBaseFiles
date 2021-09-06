<?php

namespace GeoSot\BaseAdmin\App\Traits\Eloquent;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait IsExportable
{
    /**
     * @return array
     */
    public function getCsvColumns(): array
    {
        return $this->getArrayableItems(array_merge($this->getFillable(), $this->appends));
    }

    protected function parseValueForCsv($data, $key)
    {
        $isRelation = Str::substrCount($key, '.');
        if ($isRelation) {
            return $this->parseValueForCsv(data_get($data, Str::beforeLast($key, '.')), Str::afterLast($key, '.'));
        }

        $val= data_get($data, $key) ;
        if (!is_iterable($data) || !is_iterable($val) && isset($data[$key])) {
            return data_get($data, $key);
        }

        $result = '';
        foreach ($data as $dt) {
            $result .= "{$this->parseValueForCsv($dt, $key)}, ";
        }

        return $result;
    }

    /**
     * @return array
     */
    public function toCsvArray($item): array
    {
        return array_map(function ($key) use ($item) {
            $result = $this->parseValueForCsv($item, $key) ?: '';
            return strip_tags(is_array($result) ? implode(', ', $result) : $result);
        }, $this->getCsvColumns());
    }

    /**
     * Export a csv file based on a collection of items.
     *
     * @param Collection $items
     * @return StreamedResponse
     * @throws Exception
     */
    public function exportToCsv(Collection $items, callable $translateCallback = null): StreamedResponse
    {
        $filename = now()->format('Y-m-d-his') . '-' . $this->getTable() . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=" . $filename,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        return response()->stream(function () use ($items, $translateCallback) {
            $file = fopen('php://output', 'w');

            fputcsv($file, array_map(function (string $text) use ($translateCallback) {
                return $translateCallback ? $translateCallback($text) : $text;
            }, $this->getCsvColumns()));

            foreach ($items as $item) {
                fputcsv($file, $this->toCsvArray($item));
            }

            fclose($file);
        }, 200, $headers);
    }
}
