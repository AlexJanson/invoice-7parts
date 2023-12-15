<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import ConfirmationSlideOver from '@/Components/ConfirmationSlideOver.vue'
import ProductForm from './ProductForm.vue'

const productForm = ref(null)
const slideOver = ref(null)

const show = ref(false)
function open() {
    router.visit(route('product.create'), {
        preserveState: true,
    })
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
    console.log('TODO: submit the form')
}

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
        <template #title> Nieuw product toevoegen </template>

        <template #description>
            Ga aan de slag door de onderstaande informatie in te vullen om een
            nieuw product aan te maken.
        </template>

        <ProductForm ref="productForm" />
    </ConfirmationSlideOver>
</template>
