<script setup>
import { nextTick, onMounted, ref, toRefs } from 'vue'
import ConfirmationSlideOver from '@/Components/ConfirmationSlideOver.vue'
import { router } from '@inertiajs/vue3'
import CustomerForm from './CustomerForm.vue'
import { toReactive } from '@vueuse/core'

const props = defineProps({
    customer: Object,
})
const { customer } = toRefs(props)

const customerForm = ref(null)
const slideOver = ref(null)

const show = ref(false)
function open() {
    router.visit(
        route('customer.edit', {
            customer: customer.value,
        }),
        {
            preserveState: true,
        },
    )
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
    customerForm.value.form.put(
        route('customer.update', { customer: customer.value }),
        {
            preserveState: true,
            onSuccess: () => slideOver.value.close(),
        },
    )
}

onMounted(() => {
    if (!customer.value) return

    const c = toReactive(customer)

    // Next tick to have customerForm be defined.
    nextTick(() => {
        customerForm.value.form.defaults({
            name: c.name,
            email: c.email,
            phone: c.phone,
            tax_number: c.tax,
            kvk_number: c.kvk,
            email: c.email,
            email: c.email,
            shippingAddress: {
                address: c.shipping_address.address,
                zipcode: c.shipping_address.zipcode,
                city: c.shipping_address.city,
                state: c.shipping_address.state,
                country: c.shipping_address.country,
            },
            billingAddress: {
                address: c.billing_address.address,
                zipcode: c.billing_address.zipcode,
                city: c.billing_address.city,
                state: c.billing_address.state,
                country: c.billing_address.country,
            },
            contacts: c.contacts,
        })

        // Need to reset the form after updating the defaults!
        customerForm.value.form.reset()

        customerForm.value.sameAsShippingAddress =
            Object.entries(c.shipping_address).toString() ===
            Object.entries(c.billing_address).toString()

        // Reset form again so the isDirty attribute gets set to false!
        customerForm.value.form.reset()
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
        <template #title> Klantgegevens bewerken </template>

        <template #description>
            Ga aan de slag door de onderstaande informatie in te vullen om uw
            bestaande klantgegevens aan te passen.
        </template>

        <CustomerForm ref="customerForm" />
    </ConfirmationSlideOver>
</template>
