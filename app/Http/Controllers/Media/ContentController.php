<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\ContentRequest;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    public function __construct(private Content $content){}

    public function index()
    {
        $contents = $this->content->paginate(10);

        return inertia('media/ContentIndex', compact('contents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('media/ContentCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContentRequest $request)
    {
        $data = $request->validated();

        $data['code'] = str()->uuid();
        $data['cover'] = $data['cover']?->store('contents', 'public');

        $content = $this->content->create($data);

        return redirect()->route('media.contents.edit', $content->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $content = $this->content->findOrFail($id, ['id', 'title', 'cover', 'description', 'body', 'type']);

        return inertia('media/ContentEdit', compact('content'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContentRequest $request, string $id)
    {
        $data = $request->validated();
        $content = $this->content->findOrFail($id);

        if(isset($data['cover'])) {
            $disk = Storage::disk('public');
            if($content->cover && $disk->exists($content->cover)) {
                $disk->delete($content->cover);
            }

            $data['cover'] = $data['cover']->store('contents', 'public');
        }

        $content->update($data);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $content = $this->content->findOrFail($id);

        if($content->cover) {
            $disk = Storage::disk('public');
            if($disk->exists($content->cover)) {
                $disk->delete($content->cover);
            }
        }

        $content->delete();

        return redirect()->back();
    }
}
