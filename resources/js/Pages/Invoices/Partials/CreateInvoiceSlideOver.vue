<script setup>
import ConfirmationSlideOver from '@/Components/ConfirmationSlideOver.vue'
import { router, usePage } from '@inertiajs/vue3'
import InvoiceItems from './InvoiceItems.vue'
import InvoiceForm from './InvoiceForm.vue'
import { ref } from 'vue'

const invoiceForm = ref(null)
const items = ref(null)
const invoiceItems = ref([])
const slideOver = ref(null)

const show = ref(false)
function open() {
    router.visit(route('invoice.create'), {
        preserveState: true,
    })
    slideOver.value.open()
}

function close() {
    if (invoiceForm.value.form.isDirty) {
        const result = confirm('Unsaved changes are you sure you want to exit?')
        result ? slideOver.value.close() : slideOver.value.open()
        if (result) {
            invoiceForm.value.form.reset()
            router.visit(route('invoices.index'), {
                preserveState: true,
            })
        }
        return
    }

    router.visit(route('invoices.index'), {
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
    invoiceForm.value.form.items = invoiceItems.value

    invoiceForm.value.form.clearErrors()

    console.log('TODO: submit the form', invoiceForm.value.form.data())
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
        <!-- Main panel -->
        <template #title> Factuur regels </template>

        <template #description> Vul hieronder de factuurregels in. </template>

        <InvoiceItems
            ref="items"
            v-model="invoiceItems"
            :products="usePage().props.products"
            @update:discount="
                (discount) => (invoiceForm.form.discount = discount)
            "
            editable
        />

        <!-- Side panel -->
        <template #side-title> Nieuwe factuur toevoegen </template>

        <template #side-description>
            Ga aan de slag door de onderstaande informatie in te vullen om een
            nieuwe factuur aan te maken.
        </template>

        <template #side>
            <Suspense>
                <InvoiceForm ref="invoiceForm" />
            </Suspense>
        </template>
    </ConfirmationSlideOver>
</template>
