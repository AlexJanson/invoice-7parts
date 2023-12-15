<script setup>
import { computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import Overview from './Partials/Overview.vue'
import DueInvoices from './Partials/DueInvoices.vue'

const props = defineProps({
    dueInvoices: Object,
})

const pagination = computed(() => {
    return {
        from: props.dueInvoices.from,
        to: props.dueInvoices.to,
        total: props.dueInvoices.total,
        prev_page_url: props.dueInvoices.prev_page_url,
        next_page_url: props.dueInvoices.next_page_url,
    }
})

const dueInvoices = computed(() => props.dueInvoices.data)
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h1 class="text-3xl font-semibold leading-tight text-gray-800">
                Welkom, {{ $page.props.auth.user.name }}
            </h1>
        </template>

        <div class="mb-24">
            <Overview />
        </div>

        <DueInvoices :due-invoices="dueInvoices" :pagination="pagination" />
    </AppLayout>
</template>
