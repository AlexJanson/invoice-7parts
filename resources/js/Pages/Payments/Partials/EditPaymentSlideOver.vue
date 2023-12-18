<script setup>
import { nextTick, onMounted, ref, toRefs } from 'vue'
import ConfirmationSlideOver from '@/Components/ConfirmationSlideOver.vue'
import { router } from '@inertiajs/vue3'
import { toReactive } from '@vueuse/core'
import PaymentForm from './PaymentForm.vue'
import { formatMoney } from '@/helpers'
import { parse } from 'date-fns'
import nl from 'date-fns/esm/locale/nl'

const props = defineProps({
    payment: Object,
})
const { payment } = toRefs(props)

const paymentForm = ref(null)
const slideOver = ref(null)

const show = ref(false)
function open() {
    router.visit(
        route('payment.edit', {
            payment: payment.value,
        }),
        {
            preserveState: true,
        },
    )
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
        return
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
    paymentForm.value.form
        .transform((data) => ({
            ...data,
            customer_id: data.customer_id.id,
            invoice_id: data.invoice_id.id,
        }))
        .put(route('payment.update', { payment: props.payment }), {
            preserveState: true,
            onSuccess: () => {
                paymentForm.value.form.reset()
                slideOver.value.close()
            },
        })
}

onMounted(() => {
    if (!payment.value) return

    const p = toReactive(payment)

    // Next tick to have customerForm be defined.
    nextTick(() => {
        paymentForm.value.form.defaults({
            payment_date: parse(p.payment_date, 'd MMMM, yyyy', new Date(), {
                locale: nl,
            }),
            customer_id: p.customer,
            invoice_id: p.invoice,
            amount: p.amount,
            comments: p.comments,
        })

        // Need to reset the form after updating the defaults!
        paymentForm.value.form.reset()
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
        <template #title> Betaling bewerken </template>

        <template #description>
            Ga aan de slag door de onderstaande informatie in te vullen om uw
            bestaande betaling aan te passen.
        </template>

        <Suspense>
            <PaymentForm ref="paymentForm" />
        </Suspense>
    </ConfirmationSlideOver>
</template>
