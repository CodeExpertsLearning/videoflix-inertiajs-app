<script setup lang="ts">
import MyContentsController from '@/actions/App/Http/Controllers/MyContentsController';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    contents: Array,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: MyContentsController.index(),
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex w-full items-center justify-between px-5 py-2">
            <h2
                class="w-full border-b border-gray-600 pb-2 text-2xl font-bold text-white capitalize"
            >
                Conteúdos
            </h2>
        </div>

        <div class="grid w-full grid-cols-4 gap-x-4 gap-y-5 p-5">
            <div
                v-for="content of contents"
                :key="content.id"
                class="group relative w-[320px] shadow shadow-white"
            >
                <Link
                    :href="
                        MyContentsController.watch({ content: content.slug })
                            .url
                    "
                >
                    <img
                        v-if="content.cover"
                        :src="`/storage/${content.cover}`"
                        alt="`Capa Conteúdo: ${content.title}`"
                    />
                    <img
                        v-if="!content.cover"
                        src="https://placeholdit.com/320x220?text=Sem+Foto"
                        alt="`Capa Conteúdo: ${content.title}`"
                    />

                    <div
                        class="absolute top-0 bottom-0 hidden h-full w-full bg-black/60 p-1 group-hover:block"
                    >
                        <h2
                            class="mb-4 block text-xl font-bold text-white capitalize"
                        >
                            {{ content.title }}
                        </h2>
                        <p class="text-white">
                            {{ content.description }}
                        </p>
                        <strong class="mt-4 block text-xl font-bold"
                            >ASSISTIR...</strong
                        >
                    </div>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
