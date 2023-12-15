<script setup>
import { nextTick, onMounted, ref, toRefs } from 'vue'
import ConfirmationSlideOver from '@/Components/ConfirmationSlideOver.vue'
import { router, usePage } from '@inertiajs/vue3'
import { toReactive } from '@vueuse/core'
import { formatMoney } from '@/helpers'
import { parse } from 'date-fns'
import nl from 'date-fns/esm/locale/nl'
import InvoiceForm from './InvoiceForm.vue'
import InvoiceItems from './InvoiceItems.vue'

const props = defineProps({
    invoice: Object,
})
const { invoice } = toRefs(props)

const invoiceForm = ref(null)
const slideOver = ref(null)
const itemsRef = ref(null)

const show = ref(false)
function open() {
    router.visit(
        route('invoice.edit', {
            invoice: invoice.value,
        }),
        {
            preserveState: true,
        },
    )
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
    invoiceForm.value.form.items = invoice.value.items
    invoiceForm.value.form.discount = invoice.value.discount

    console.log('TODO: submit the form', invoiceForm.value.form.data())
}

onMounted(() => {
    if (!invoice.value) return

    const inv = toReactive(invoice)

    // Next tick to have customerForm be defined.
    nextTick(() => {
        invoiceForm.value.form.defaults({
            customer: inv.customer,
            invoice_date: parse(inv.invoice_date, 'd MMMM, yyyy', new Date(), {
                locale: nl,
            }),
            contact: inv.contact,
            due_date: parse(inv.due_date, 'd MMMM, yyyy', new Date(), {
                locale: nl,
            }),
            invoice_number: inv.invoice_number,
            reference: inv.reference,
            term: inv.term,
            comments: inv.comments,
        })

        // Need to reset the form after updating the defaults!
        invoiceForm.value.form.reset()
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
        v-if="invoice"
        @slide:confirmed="submit()"
        @slide:canceled="close()"
        @slide:closed="close()"
        ref="slideOver"
    >
        <!-- Main panel -->
        <template #title> Factuur regels </template>

        <template #description> Vul hieronder de factuurregels in. </template>

        <InvoiceItems
            v-model="invoice.items"
            :products="usePage().props.products"
            editable
            :discount="invoice.discount"
            @update:discount="(update) => (invoice.discount = update)"
            ref="itemsRef"
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
