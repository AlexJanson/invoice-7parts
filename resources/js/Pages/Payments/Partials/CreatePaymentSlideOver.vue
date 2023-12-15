<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import ConfirmationSlideOver from '@/Components/ConfirmationSlideOver.vue'
import PaymentForm from './PaymentForm.vue'

const paymentForm = ref(null)
const slideOver = ref(null)

const show = ref(false)
function open() {
    router.visit(route('payment.create'), {
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
            router.visit(route('payments.index'), {
                preserveState: true,
            })
        }
    }

    router.visit(route('payments.index'), {
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
                router.replace(route('payments.index'))
                slideOver.value.close()
            },
        })
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
