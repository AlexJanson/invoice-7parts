<script setup>
import { ref, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import ConfirmationSlideOver from '@/Components/ConfirmationSlideOver.vue'
import PaymentForm from './PaymentForm.vue'

const props = defineProps({
    invoice: Object,
})

const paymentForm = ref(null)
const slideOver = ref(null)

const show = ref(false)
function open() {
    router.visit(route('payment.create', { invoice: props.invoice }), {
        preserveState: true,
    })
    slideOver.value.open()
}

function close() {
    if (paymentForm.value.form.isDirty) {
        const result = confirm('Unsaved changes are you sure you want to exit?')
        result ? slideOver.value.close() : slideOver.value.open()
        if (result) {
            paymentForm.value.form.reset()
            router.visit(route('invoices.index'), {
                preserveState: true,
            })
        }
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
    paymentForm.value.form.clearErrors()

    paymentForm.value.form
        .transform((data) => ({
            ...data,
            customer_id: data.customer_id.id,
            invoice_id: data.invoice_id.id,
        }))
        .post(route('payment.store'), {
            onSuccess: () => {
                paymentForm.value.form.reset()
                slideOver.value.close()
            },
        })
}

watch(
    () => props.invoice,
    () => {
        if (!props.invoice) return

        const customer = usePage().props.customers.find(
            ({ id }) => id === props.invoice.customer_id,
        )

        const invoice = usePage().props.invoices.data.find(
            ({ id }) => id === props.invoice.id,
        )

        // Next tick to have customerForm be defined.
        paymentForm.value.form.defaults({
            customer_id: customer,
            invoice_id: invoice,
            amount: invoice.due_amount,
        })

        // Need to reset the form after updating the defaults!
        paymentForm.value.form.reset()
    },
)

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
        <template #title> Nieuwe betaling toevoegen </template>

        <template #description>
            Ga aan de slag door de onderstaande informatie in te vullen om een
            nieuwe betaling aan te maken.
        </template>

        <Suspense>
            <PaymentForm ref="paymentForm" />
        </Suspense>
    </ConfirmationSlideOver>
</template>
