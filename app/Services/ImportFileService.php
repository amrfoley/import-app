<?php

namespace App\Services;

use App\Models\Article;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;

class ImportFileService implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $data = [
                'title'             => htmlspecialchars($row['product_name']),
                'part_number'       => $row['part_number'],
                'article_group_id'  => $row['articel_group_id'],
                'price'             => $row['prize'],
            ];

            $imported = Article::create($data);

            if($imported !== null) {
                RedisService::add('articles', json_encode(array_merge($data, ['id' => $imported->id])));
                $imported = null;
            }
        }
    }

    public function rules(): array
    {
        return [
            'product_name'      => 'required',
            'part_number'       => ['required', 'max:16', 'unique:articles,part_number'],
            'articel_group_id'  => ['required', 'numeric'],
            'prize'             => ['required', 'numeric'],
        ];
    }
}