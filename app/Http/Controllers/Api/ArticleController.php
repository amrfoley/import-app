<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use App\Services\ImportFileService;
use App\Services\RedisService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ArticleController
{
    public function index(Request $request)
    {
        $articles = RedisService::has('articles')? RedisService::get('articles') :
            Article::select(['id', 'title', 'part_number', 'article_group_id', 'price', 'price'])
                ->paginate($request->per_page ?? 50);

        return response()->json(['articles' => $articles]);
    }

    public function show(Request $request)
    {
        if($request->has('part_number') || $request->has('id'))
        {
            $article = Article::select(['id', 'title', 'part_number', 'article_group_id', 'price', 'price'])
                ->where('id', $request->id)
                ->orWhere('part_number', $request->part_number)
                ->first();
        }

        return response()->json($article ?? null);
    }

    public function import(Request $request)
    {
        $import = false;
        $errors = [];

        if ($request->hasFile('articles')) 
        {
            try {
                Excel::import(new ImportFileService, $request->file('articles'));
                $import = true;
            }
            catch(ValidationException $e) 
            {
                foreach($e->failures() as $failure) 
                {
                    if(!empty($failure->errors()))
                    {
                        foreach($failure->errors() as $error) {
                            $errors[] = "$error on row {$failure->row()}";
                        }
                    }
                }
            }
        }

        return response()->json(['success' => $import, 'errors' => $errors]);
    }
}