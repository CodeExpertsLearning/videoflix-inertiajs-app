<script setup lang="ts">
import MyContentsController from '@/actions/App/Http/Controllers/MyContentsController';
import AppLayout from '@/layouts/AppLayout.vue';
import { stream_player } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Hls from 'hls.js';
import { onMounted, ref } from 'vue';

const props = defineProps({
    videos: Array,
    content: Object,
});

const video = ref(props.videos[0]);
const playerEl = ref();
const player = new Hls();

const menu = ref(false);
const showMenu = () => {
    menu.value = !!!menu.value;
};

onMounted(() => {
    player.loadSource(
        stream_player({ code: video.value.code, video: video.value.video }).url,
    );
    player.attachMedia(playerEl.value);
});

const changeVideo = (videoLine) => {
    video.value = videoLine;
    menu.value = false;

    player.loadSource(
        stream_player({ code: videoLine.code, video: videoLine.video }).url,
    );
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: MyContentsController.index(),
    },
    {
        title: 'Assistir',
        href: MyContentsController.watch({ content: props.content.title }),
    },
];
</script>
<template>
    <Head :title="`Assistindo: ${video.name} - ${content.title}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex w-full items-center justify-between px-5 py-2">
            <h2 class="block font-thin text-white capitalize">
                {{ content.title }} &raquo; {{ video.name }}
            </h2>
            <div class="relative">
                <button v-if="content.type === 'SERIE'" @click="showMenu">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
                        />
                    </svg>
                </button>
                <nav
                    v-if="menu"
                    class="absolute top-6 right-0 z-50 rounded border border-white bg-black shadow-xl"
                >
                    <ul>
                        <li
                            v-for="v of videos"
                            :key="v.id"
                            @click="changeVideo(v)"
                            class="flex cursor-pointer gap-2 border-b border-white px-2 py-4 hover:bg-white hover:text-black"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="size-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z"
                                />
                            </svg>

                            {{ v.name }}
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="w-full px-5">
            <video ref="playerEl" class="mt-2 w-full" controls></video>
        </div>
    </AppLayout>
</template>
