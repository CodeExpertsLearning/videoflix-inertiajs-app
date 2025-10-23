<script setup>
import VideosController from '@/actions/App/Http/Controllers/Media/VideosController';
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import InputLabel from '@/components/ui/label/Label.vue';

import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    video: Object,
    content: Number,
});

const emit = defineEmits();

const form = useForm({
    name: props.video.name,
    description: '',
});

const updateVideo = () => {
    form.patch(
        VideosController.update({
            content: props.content,
            video: props.video.id,
        }),
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
};
</script>
<template>
    <div class="p-6 text-gray-900">
        <div class="flex space-x-6">
            <div class="w-full max-w-[350px] space-y-3">
                <div class="h-[180px] w-full">
                    <img
                        src="https://placeholdit.com/320x180/dddddd/999999"
                        class="h-[180px] w-full rounded bg-white p-1 shadow"
                    />
                </div>

                <!-- Encoding Status -->
                <!-- <div class="space-y-1">
                                <div
                                    class="h-3 overflow-hidden rounded bg-gray-100 shadow-inner"
                                >
                                    <div
                                        class="h-full bg-green-500"
                                        style="width: 65%"
                                    ></div>
                                </div>
                                <div class="text-sm font-bold text-white">
                                    Convertendo vídeo
                                </div>
                            </div> -->
                <!-- Encoding Status -->

                <!-- Uploading Status -->
                <div class="space-y-1" v-if="video.uploading">
                    <div
                        class="h-3 overflow-hidden rounded bg-gray-100 shadow-inner"
                    >
                        <div
                            class="h-full bg-blue-500"
                            :style="{
                                width: `${video.uploadProgress}%`,
                            }"
                        ></div>
                    </div>
                    <div class="text-sm font-bold text-white">
                        Enviando Vídeo
                    </div>
                </div>
                <!-- Uploading Status -->

                <div class="flex items-center space-x-3" v-if="video.uploading">
                    <!-- Disparar os eventos -->
                    <button
                        class="text-sm font-medium text-blue-500"
                        v-if="!video.file.paused"
                        @click.prevent="emit('pause', video.id)"
                    >
                        Pausar
                    </button>
                    <button
                        class="text-sm font-medium text-blue-500"
                        v-if="video.file.paused"
                        @click.prevent="emit('resume', video.id)"
                    >
                        Continuar
                    </button>
                    <button
                        class="text-sm font-medium text-blue-500"
                        @click.prevent="emit('cancel', video.id)"
                    >
                        Cancelar
                    </button>
                </div>
            </div>

            <form @submit.prevent="updateVideo" class="flex-grow space-y-6">
                <div>
                    <InputLabel for="title" class="text-white"
                        >Nome Vídeo</InputLabel
                    >

                    <Input
                        id="title"
                        type="text"
                        v-model="form.name"
                        required
                        autofocus
                        class="text-white"
                    />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="description" class="text-white"
                        >Descrição</InputLabel
                    >

                    <Input
                        id="description"
                        type="text"
                        class="mt-1 block w-full p-2 text-white"
                        v-model="form.description"
                        required
                        autofocus
                        autocomplete=""
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.description"
                    />
                </div>

                <div class="mt-8">
                    <Button
                        variant="secondary"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Atualizar
                    </Button>

                    <span
                        class="ml-4 font-thin text-white"
                        v-if="form.recentlySuccessful"
                        >Atualizado...</span
                    >
                </div>
            </form>
        </div>
    </div>
</template>
