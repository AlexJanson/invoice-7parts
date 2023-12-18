<script setup>
import { nextTick, onMounted, ref, toRefs } from 'vue'
import ConfirmationSlideOver from '@/Components/ConfirmationSlideOver.vue'
import { router } from '@inertiajs/vue3'
import { toReactive } from '@vueuse/core'
import { formatMoney } from '@/helpers'
import ProductForm from './ProductForm.vue'

const props = defineProps({
    product: Object,
})
const { product } = toRefs(props)

const productForm = ref(null)
const slideOver = ref(null)

const show = ref(false)
function open() {
    router.visit(
        route('product.edit', {
            product: product.value,
        }),
        {
            preserveState: true,
        },
    )
    slideOver.value.open()
}

function close() {
    if (productForm.value.form.isDirty) {
        const result = confirm('Unsaved changes are you sure you want to exit?')
        result ? slideOver.value.close() : slideOver.value.open()
        if (result) {
            productForm.value.form.reset()
            router.visit(route('products.index'), {
                preserveState: true,
            })
        }
        return
    }

    router.visit(route('products.index'), {
        preserveState: true,
    })
    slideOver.value.close()
}

function toggle() {
    show.value ? close() : open()
}

function setOpen(value) {
    value ? open() : close()
}

function submit() {
    productForm.value.form
        .transform((data) => ({
            ...data,
            unit: data.unit.unit,
        }))
        .put(
            route('product.update', {
                product: product.value,
            }),
            {
                preserveState: true,
                onSuccess: () => {
                    productForm.value.form.reset()
                    slideOver.value.close()
                },
            },
        )
}

onMounted(() => {
    if (!product.value) return

    const p = toReactive(product)

    // Next tick to have customerForm be defined.
    nextTick(() => {
        productForm.value.form.defaults({
            name: p.name,
            price: p.price,
            tax: p.tax,
            description: p.description,
        })

        // Need to reset the form after updating the defaults!
        productForm.value.form.reset()
    })
})

defineExpose({
    open,
    close,
    toggle,
    setOpen,
})
</script>

<template>
    <ConfirmationSlideOver
        @slide:confirmed="submit()"
        @slide:canceled="close()"
        @slide:closed="close()"
        ref="slideOver"
    >
        <template #title> Product bewerken </template>

        <template #description>
            Ga aan de slag door de onderstaande informatie in te vullen om uw
            bestaande product aan te passen.
        </template>

        <ProductForm ref="productForm" />
    </ConfirmationSlideOver>
</template>
