<script setup lang="ts">
import ContentController from '@/actions/App/Http/Controllers/Media/ContentController';
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import InputLabel from '@/components/ui/label/Label.vue';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import TextArea from '@/components/ui/textarea/TextArea.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    title: null,
    description: null,
    body: null,
    type: null,
    cover: null,
});

const optsType = [
    { label: 'Filme', value: 'MOVIE' },
    { label: 'Série', value: 'SERIE' },
];

const createContent = () => {
    form.post(ContentController.store());
};

//Drag and Drop & Image Upload...
const isDragged = ref(false);
const coverImg = ref(null);

const coverHandle = (e) => {
    mainHandleImage(e.target.files[0]);
};
const coverDrop = (e) => {
    mainHandleImage(e.dataTransfer.files[0]);
};

const mainHandleImage = (image) => {
    form.cover = image ? image : null;

    mountPreviewImage(image);
};

const mountPreviewImage = (image) => {
    const reader = new FileReader();
    reader.readAsDataURL(image);

    reader.onload = (e) => {
        coverImg.value = e.target.result;
    };
};

//Lê a origem dos arquivos
//Fazer o bind no form
//Agente pode fazer um preview...

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Conteúdos',
        href: ContentController.index(),
    },
    {
        title: 'Criar Conteúdo',
        href: ContentController.create(),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Criar Conteúdo" />

        <div class="w-full p-2">
            <form action="" @submit.prevent="createContent">
                <div class="mb-6 w-full">
                    <InputLabel for="title">Título</InputLabel>
                    <Input
                        id="title"
                        class="mt-2"
                        type="text"
                        v-model="form.title"
                        required
                        autofocus
                    />
                    <InputError :message="form.errors.title" />
                </div>

                <div class="mb-6 w-full">
                    <InputLabel for="description">Descrição</InputLabel>
                    <Input
                        id="description"
                        class="mt-2"
                        type="text"
                        v-model="form.description"
                        required
                    />
                    <InputError :message="form.errors.description" />
                </div>

                <div class="mb-6 w-full">
                    <InputLabel for="body">Conteúdo</InputLabel>
                    <TextArea
                        id="body"
                        class="mt-2"
                        type="text"
                        v-model="form.body"
                        required
                    />
                    <InputError :message="form.errors.body" />
                </div>

                <div class="mb-6 w-full">
                    <InputLabel for="type" class="mb-4"
                        >Tipo Conteúdo</InputLabel
                    >
                    <Select id="type" v-model="form.type" required>
                        <SelectTrigger>
                            <SelectValue
                                placeholder="Selecione um tipo do conteúdo!"
                            />
                        </SelectTrigger>

                        <SelectContent>
                            <SelectItem
                                v-for="type of optsType"
                                :key="type.label"
                                :value="type.value"
                            >
                                {{ type.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.type" />
                </div>

                <div class="mb-6 w-full" :class="{ 'flex gap-2': coverImg }">
                    <div
                        :class="{
                            'flex w-[50%] items-center justify-center':
                                coverImg,
                        }"
                    >
                        <div>
                            <InputLabel
                                @dragover.prevent="isDragged = true"
                                @dragleave.prevent="isDragged = false"
                                @drop.prevent="coverDrop"
                                class="flex w-full items-center justify-center rounded border-2 border-dashed border-gray-500 p-10"
                                :class="{ 'border-gray-200': isDragged }"
                                for="cover"
                                >Clique ou arraste a imagem da capa do seu
                                conteúdo para upload...</InputLabel
                            >
                            <Input
                                id="cover"
                                type="file"
                                class="sr-only"
                                @change="coverHandle"
                            />
                            <InputError :message="form.errors.cover" />
                        </div>
                    </div>
                    <div class="w-[50%]" v-if="coverImg">
                        <img
                            :src="coverImg"
                            alt="Imagem Capa"
                            class="rounded border-gray-400 bg-white p-1 shadow"
                        />
                    </div>
                </div>

                <Button
                    class="mt-8"
                    variant="secondary"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    >Criar Conteúdo</Button
                >
            </form>
        </div>
    </AppLayout>
</template>
