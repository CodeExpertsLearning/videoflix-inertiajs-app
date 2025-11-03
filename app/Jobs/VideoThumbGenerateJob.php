<?php

namespace App\Jobs;

use App\Events\VideoFlix\VideoThumbGenerated;
use App\Models\Video;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoThumbGenerateJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private Video $video)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $thumb = 'thumbs/' . $this->video->code . '/thumb.png';

        FFMpeg::fromDisk('videos')
            ->open($this->video->video)
            ->getFrameFromSeconds(5)
            ->export()
            ->toDisk('public')
            ->save($thumb);

        $this->video->update([
            'thumb' => $thumb
        ]);

        event(new VideoThumbGenerated($this->video->id, $thumb));
    }
}
