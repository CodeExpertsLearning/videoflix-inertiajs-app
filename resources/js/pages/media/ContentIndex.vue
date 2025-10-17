<script setup lang="ts">
import ContentController from '@/actions/App/Http/Controllers/Media/ContentController';
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
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    contents: Object,
});

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
                    <TableHead class="text-right"> Ações </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="content of contents.data" :key="content.id">
                    <TableCell class="font-medium">
                        {{ content.id }}
                    </TableCell>
                    <TableCell>{{ content.title }}</TableCell>
                    <TableCell>{{ content.created_at }}</TableCell>
                    <TableCell class="text-right"> </TableCell>
                </TableRow>
            </TableBody>
        </Table>

        <Pagination :links="contents.links" v-if="contents.total > 10" />
    </AppLayout>
</template>
