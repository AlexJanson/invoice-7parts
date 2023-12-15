<script setup>
import { computed, nextTick, onMounted, ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import ListCustomers from './Partials/ListCustomers.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { PlusIcon } from '@heroicons/vue/20/solid'
import CreateCustomerSlideOver from './Partials/CreateCustomerSlideOver.vue'
import EditCustomerSlideOver from './Partials/EditCustomerSlideOver.vue'

const props = defineProps({
    customers: Object,
    customer: {
        type: Object,
        required: false,
    },
})

const pagination = computed(() => {
    return {
        from: props.customers.from,
        to: props.customers.to,
        total: props.customers.total,
        prev_page_url: props.customers.prev_page_url,
        next_page_url: props.customers.next_page_url,
    }
})

const customers = computed(() => props.customers.data)

const createCustomerSlideOver = ref(null)
const editCustomerSlideOver = ref(null)
onMounted(() => {
    const slideOver = {
        'customer.create': createCustomerSlideOver.value,
        'customer.edit': editCustomerSlideOver.value,
    }[route().current()]

    nextTick(() => slideOver?.open())
})
</script>

<template>
    <AppLayout title="Klanten">
        <template #header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-semibold leading-tight text-gray-800">
                    Klanten
                </h1>

                <PrimaryButton @click="createCustomerSlideOver.open()">
                    <PlusIcon class="-ml-1 mr-2 h-4 w-4" />
                    Nieuw
                </PrimaryButton>
            </div>
        </template>

        <ListCustomers :customers="customers" :pagination="pagination" />

        <!-- Create new Customer slide-over -->
        <CreateCustomerSlideOver ref="createCustomerSlideOver" />

        <!-- Edit existing Customer slide-over -->
        <EditCustomerSlideOver
            ref="editCustomerSlideOver"
            :customer="customer"
        />
    </AppLayout>
</template>
