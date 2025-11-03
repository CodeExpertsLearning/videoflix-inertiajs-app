<script setup lang="ts">
import VideosController from '@/actions/App/Http/Controllers/Media/VideosController';
import Input from '@/components/ui/input/Input.vue';
import InputLabel from '@/components/ui/label/Label.vue';
import VideoItem from '@/components/videoflix/VideoItem.vue';

import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { useEchoPublic } from '@laravel/echo-vue';
import { createUpload } from '@mux/upchunk';
import axios from 'axios';
import { ref } from 'vue';

useEchoPublic('videos', '.App\\Events\\VideoFlix\\EncodeVideoStarted', (e) => {
    const video = findVideoByID(e.videoId);
    if (!video) return;

    video.encoding = true;
});

useEchoPublic('videos', '.App\\Events\\VideoFlix\\EncodeVideoProgress', (e) => {
    const video = findVideoByID(e.videoId);
    if (!video) return;

    video.encodingProgress = e.progress;
});

useEchoPublic('videos', '.App\\Events\\VideoFlix\\EncodeVideoFinished', (e) => {
    const video = findVideoByID(e.videoId);
    if (!video) return;

    video.encoding = false;
});

useEchoPublic('videos', '.App\\Events\\VideoFlix\\VideoThumbGenerated', (e) => {
    const video = findVideoByID(e.videoId);
    if (!video) return;

    video.thumb = e.thumb;
});

const props = defineProps({
    content: Object,
});

const isDragged = ref(false);

const findVideoByID = (videoId) => {
    return videosList.value.find((video) => video.id === videoId);
};

// Upload Vídeos
const videosList = ref([]);

const mainHandleVideos = (videos) => {
    Array.from(videos).forEach((video) => {
        axios
            .post(`/media/contents/${props.content.id}/videos/upload`, {
                name: video.name,
            })
            .then((res) => {
                const videoPayload = {
                    id: res.data.id,
                    name: res.data.name,
                    uploading: true,
                    uploadProgress: 0,
                    encoding: false,
                    encodingProgress: 0,
                    thumb: null,
                    file: chunckUpload(video, {
                        video: res.data.id,
                        content: props.content.id,
                    }),
                };

                videosList.value.unshift(videoPayload);
            });
    });
};

const chunckUpload = (video, params) => {
    const chunck = createUpload({
        endpoint: VideosController.processChunck({
            content: params.content,
            video: params.video,
        }).url,
        headers: {
            'X-CSRF-TOKEN': usePage().props.csrf_token,
        },
        file: video,
        chunkSize: 20 * 1024,
    });

    chunck.on(
        'progress',
        (e) => (findVideoByID(params.video).uploadProgress = e.detail),
    );

    chunck.on(
        'success',
        (e) => (findVideoByID(params.video).uploading = false),
    );

    return chunck;
};

const resumeUpload = (videoId) => findVideoByID(videoId).file.resume();
const pauseUpload = (videoId) => findVideoByID(videoId).file.pause();
const cancelUpload = (videoId) => {
    findVideoByID(videoId).file.abort();

    router.delete(
        VideosController.destroy({ content: props.content.id, video: videoId }),
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                videosList.value = videosList.value.filter(
                    (video) => video.id !== videoId,
                );
            },
        },
    );
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '',
    },
    {
        title: 'Conteúdos',
        href: '',
    },
    {
        title: 'Criar Conteúdo',
        href: '',
    },
];
</script>

<template>
    <Head title="Upload de Vídeos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl">
            <!-- Area Upload -->
            <div class="mx-auto mt-10 mb-2 w-[80%]">
                <InputLabel
                    @dragover.prevent="isDragged = true"
                    @dragleave.prevent="isDragged = false"
                    @drop="isDragged = false"
                    @drop.prevent="mainHandleVideos($event.dataTransfer.files)"
                    for="photos"
                    class="bg-black-700 flex h-28 w-full items-center justify-center rounded border-2 border-dashed border-white"
                    :class="{
                        'bg-gray-900': isDragged,
                    }"
                    >Clique ou arraste seus vídeos para realizar o
                    upload</InputLabel
                >

                <Input
                    id="photos"
                    type="file"
                    class="sr-only"
                    @change="mainHandleVideos($event.target.files)"
                />
            </div>
            <!-- Area Upload -->

            <div>
                <VideoItem
                    v-for="video of videosList"
                    :key="video.id"
                    :video="video"
                    :content="content.id"
                    @resume="resumeUpload"
                    @pause="pauseUpload"
                    @cancel="cancelUpload"
                />
            </div>
        </div>
    </AppLayout>
</template>
