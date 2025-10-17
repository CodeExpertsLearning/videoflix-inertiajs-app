<script setup>
import NavLink from '@/components/TextLink.vue';
defineProps({
    links: Array,
});
</script>

<template>
    <div id="pagination" class="flex justify-end py-2 pr-10">
        <NavLink
            v-for="link in links"
            :href="link.url ? link.url : '#'"
            v-show="link.url"
            :key="link.label"
            class="ml-4 rounded border-gray-300 !px-4 !py-2 transition duration-300 ease-in-out hover:border-gray-600 hover:bg-gray-900 hover:text-white"
            :class="{
                'border-xl border border-gray-600 bg-black text-white':
                    link.active,
            }"
            v-html="link.label"
        ></NavLink>
    </div>
</template>
