<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Jobs\VideoEncodingJob;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Pion\Laravel\ChunkUpload\Handler\ContentRangeUploadHandler;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class VideosController extends Controller
{
    public function index(Content $content)
    {
        return inertia('media/videos/Upload', compact('content'));
    }

    /**
     * Quando realizar a opção de upar algo
     * 3 - Expor a rota para receber os chuncks do video upado
     */
    public function store(Request $request, Content $content)
    {
        $video = $content->videos()->create([
            'code' => str()->uuid(),
            'name' => $request->name,
        ]);

        return $video;
    }

    public function update(Content $content, $video, Request $request)
    {
        $video = $content->videos()->findOrFail($video);

        $video->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->back();
    }

    public function destroy(Content $content, $video)
    {
        $video = $content->videos()->findOrFail($video);
        $video->delete();

        return redirect()->back();
    }

    public function processChunck(Content $content, $video, Request $request)
    {
        $receiver = new FileReceiver(
            UploadedFile::fake()->createWithContent('file', $request->getContent()),
            $request,
            ContentRangeUploadHandler::class
        );

        $save = $receiver->receive();

        if($save->isFinished()) {
            $video = $content->videos()->findOrFail($video);

            $video->update([
                'video' => $save->getFile()->storeAs('', str()->uuid(), 'videos')
            ]);

            dispatch(new VideoEncodingJob($video));
        }

        $save->handler();
    }
}
