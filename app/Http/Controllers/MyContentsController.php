<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;

class MyContentsController extends Controller
{
    public function index()
    {
        $contents = Content::query()->getWithActiveVideos()
                        ->get();

        return inertia('Dashboard', compact('contents'));
    }

    public function watch(Content $content)
    {

        $videos = $content->videos()->whereIsProcessed(true)
                                    ->whereNotNull('code')
                                    ->get();

        $content = ['title' => $content->title, 'type' => $content->type];

        return inertia('Watch', compact('videos', 'content'));
    }
}
