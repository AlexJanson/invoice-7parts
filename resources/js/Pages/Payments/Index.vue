<script setup>
import { computed, nextTick, onMounted, ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import ListPayments from './Partials/ListPayments.vue'
import CreatePaymentSlideOver from './Partials/CreatePaymentSlideOver.vue'
import EditPaymentSlideOver from './Partials/EditPaymentSlideOver.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { PlusIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    payments: Object,
    payment: {
        type: Object,
        required: false,
    },
})

const pagination = computed(() => {
    return {
        from: props.payments.from,
        to: props.payments.to,
        total: props.payments.total,
        prev_page_url: props.payments.prev_page_url,
        next_page_url: props.payments.next_page_url,
    }
})

const payments = computed(() => props.payments.data)

const createPaymentSlideOver = ref(null)
const editPaymentSlideOver = ref(null)
onMounted(() => {
    const slideOver = {
        'payment.create': createPaymentSlideOver.value,
        'payment.edit': editPaymentSlideOver.value,
    }[route().current()]

    nextTick(() => slideOver?.open())
})
</script>

<template>
    <AppLayout title="Betalingen">
        <template #header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-semibold leading-tight text-gray-800">
                    Betalingen
                </h1>

                <PrimaryButton @click="createPaymentSlideOver.open()">
                    <PlusIcon class="-ml-1 mr-2 h-4 w-4" />
                    Nieuw
                </PrimaryButton>
            </div>
        </template>

        <ListPayments :payments="payments" :pagination="pagination" />

        <!-- Create new Payment slide-over -->
        <CreatePaymentSlideOver ref="createPaymentSlideOver" />

        <!-- Edit existing Payment slide-over -->
        <EditPaymentSlideOver ref="editPaymentSlideOver" :payment="payment" />
    </AppLayout>
</template>
