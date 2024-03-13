<script setup>
import { computed, nextTick, onMounted, ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import ListInvoices from './Partials/ListInvoices.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { PlusIcon } from '@heroicons/vue/24/solid'
import CreateInvoiceSlideOver from './Partials/CreateInvoiceSlideOver.vue'
import EditInvoiceSlideOver from './Partials/EditInvoiceSlideOver.vue'
import CreatePaymentSlideOver from './Partials/CreatePaymentSlideOver.vue'

const props = defineProps({
    payments: Array,
    invoices: Object,
    invoice: {
        type: Object,
        required: false,
    },
})

const pagination = computed(() => {
    return {
        from: props.invoices.from,
        to: props.invoices.to,
        total: props.invoices.total,
        prev_page_url: props.invoices.prev_page_url,
        next_page_url: props.invoices.next_page_url,
    }
})

const invoices = computed(() => props.invoices.data)

const createInvoiceSlideOver = ref(null)
const editInvoiceSlideOver = ref(null)
const createPaymentSlideOver = ref(null)
onMounted(() => {
    const slideOver = {
        'invoice.create': createInvoiceSlideOver.value,
        'invoice.edit': editInvoiceSlideOver.value,
        'payment.create': createPaymentSlideOver.value,
    }[route().current()]

    nextTick(() => slideOver?.open())
})
</script>

<template>
    <AppLayout title="Facturen">
        <template #header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-semibold leading-tight text-gray-800">
                    Facturen
                </h1>

                <PrimaryButton @click="createInvoiceSlideOver.open()">
                    <PlusIcon class="-ml-1 mr-2 h-4 w-4" />
                    Nieuw
                </PrimaryButton>
            </div>
        </template>

        <ListInvoices :invoices="invoices" :pagination="pagination" />

        <!-- Create new Invoice slide-over -->
        <CreateInvoiceSlideOver ref="createInvoiceSlideOver" />

        <!-- Edit existing Invoice slide-over -->
        <EditInvoiceSlideOver
            v-if="invoice"
            ref="editInvoiceSlideOver"
            :invoice="invoice"
            :payments="payments"
        />

        <CreatePaymentSlideOver
            ref="createPaymentSlideOver"
            :invoice="invoice"
        />
    </AppLayout>
</template>
