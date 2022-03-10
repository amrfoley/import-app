<?php

namespace App\Services;

use App\Models\Article;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class ImportFileService implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows, SkipsOnFailure
{
    protected $headings = ['product_name', 'part_number', 'articel_group_id', 'prize'];

    public function model(array $row)
    {
        return new Article([
            'title'             => $row['product_name'],
            'part_number'       => $row['part_number'],
            'article_group_id'  => $row['articel_group_id'],
            'price'             => $row['prize'],
        ]);
    }

    public function rules(): array
    {
        return [
            'product_name'      => 'required',
            'part_number'       => 'required',
            'articel_group_id'  => ['required', 'numeric'],
            'prize'             => ['required', 'numeric'],
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        if(!empty($failures))
        {
            throw new \Exception($failures[0]->toArray()[0]);
        }
    }
}