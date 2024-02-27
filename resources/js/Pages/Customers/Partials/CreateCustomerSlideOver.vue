<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import ConfirmationSlideOver from '@/Components/ConfirmationSlideOver.vue'
import CustomerForm from './CustomerForm.vue'

const customerForm = ref(null)
const slideOver = ref(null)

const show = ref(false)
function open() {
    router.visit(route('customer.create'), {
        preserveState: true,
    })
    slideOver.value.open()
}

function close() {
    if (customerForm.value.form.isDirty) {
        const result = confirm('Unsaved changes are you sure you want to exit?')
        result ? slideOver.value.close() : slideOver.value.open()
        if (result) {
            customerForm.value.form.reset()
            router.visit(route('customers.index'), {
                preserveState: true,
            })
        }
        return
    }

    router.visit(route('customers.index'), {
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
    if (customerForm.value.sameAsShippingAddress) {
        customerForm.value.form.transform((data) => ({
            ...data,
            billingAddress: {
                ...data.shippingAddress,
            },
        }))
    } else {
        customerForm.value.form.transform((data) => data)
    }

    customerForm.value.form.post(route('customer.store'), {
        preserveState: true,
        onSuccess: () => {
            customerForm.value.form.reset()
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
        <template #title> Nieuwe klant toevoegen </template>

        <template #description>
            Ga aan de slag door de onderstaande informatie in te vullen om uw
            nieuwe klant aan te maken.
        </template>

        <CustomerForm ref="customerForm" />
    </ConfirmationSlideOver>
</template>
