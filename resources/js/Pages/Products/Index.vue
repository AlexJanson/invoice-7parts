<script setup>
import { computed, nextTick, onMounted, ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import ListProducts from './Partials/ListProducts.vue'
import CreateProductSlideOver from './Partials/CreateProductSlideOver.vue'
import { PlusIcon } from '@heroicons/vue/24/solid'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import EditProductSlideOver from './Partials/EditProductSlideOver.vue'

const props = defineProps({
    products: Object,
    product: {
        type: Object,
        required: false,
    },
})

const pagination = computed(() => {
    return {
        from: props.products.from,
        to: props.products.to,
        total: props.products.total,
        prev_page_url: props.products.prev_page_url,
        next_page_url: props.products.next_page_url,
    }
})

const products = computed(() => props.products.data)

const createProductSlideOver = ref(null)
const editProductSlideOver = ref(null)
onMounted(() => {
    const slideOver = {
        'product.create': createProductSlideOver.value,
        'product.edit': editProductSlideOver.value,
    }[route().current()]

    nextTick(() => slideOver?.open())
})
</script>

<template>
    <AppLayout title="Producten">
        <template #header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-semibold leading-tight text-gray-800">
                    Producten
                </h1>

                <PrimaryButton @click="createProductSlideOver.open()">
                    <PlusIcon class="-ml-1 mr-2 h-4 w-4" />
                    Nieuw
                </PrimaryButton>
            </div>
        </template>

        <ListProducts :products="products" :pagination="pagination" />

        <!-- Create new Product slide-over -->
        <CreateProductSlideOver ref="createProductSlideOver" />

        <!-- Edit existing Product slide-over -->
        <EditProductSlideOver ref="editProductSlideOver" :product="product" />
    </AppLayout>
</template>
