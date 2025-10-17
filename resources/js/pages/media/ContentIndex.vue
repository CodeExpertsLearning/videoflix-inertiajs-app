<script setup lang="ts">
import ContentController from '@/actions/App/Http/Controllers/Media/ContentController';
import TextLink from '@/components/TextLink.vue';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import Pagination from '@/components/videoflix/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Pencil, Trash } from 'lucide-vue-next';

const props = defineProps({
    contents: Object,
});

const form = useForm({});

const removeContent = (content) => {
    if (!confirm('Deseja mesmo remover este conteúdo?')) return;

    form.delete(ContentController.destroy(content));
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Conteúdos',
        href: ContentController.index(),
    },
];

console.log(props.contents);
</script>
<template>
    <Head title="Meus Conteúdos" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead class="w-[100px]"> # </TableHead>
                    <TableHead>Conteúdo</TableHead>
                    <TableHead>Criado em</TableHead>
                    <TableHead> Ações </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="content of contents.data" :key="content.id">
                    <TableCell class="font-medium">
                        {{ content.id }}
                    </TableCell>
                    <TableCell>{{ content.title }}</TableCell>
                    <TableCell>{{ content.created_at }}</TableCell>
                    <TableCell class="flex gap-x-2">
                        <TextLink
                            :href="
                                ContentController.edit({ content: content.id })
                            "
                            class="rounded border border-blue-900 bg-blue-700 px-4 py-2 font-bold text-white no-underline transition duration-300 ease-in-out hover:bg-blue-900"
                        >
                            <Pencil />
                        </TextLink>
                        <TextLink
                            @click.prevent="removeContent(content.id)"
                            class="rounded border border-red-900 bg-red-700 px-4 py-2 font-bold text-white no-underline transition duration-300 ease-in-out hover:bg-red-900"
                        >
                            <Trash />
                        </TextLink>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>

        <Pagination :links="contents.links" v-if="contents.total > 10" />
    </AppLayout>
</template>
