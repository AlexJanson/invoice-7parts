<script setup>
import { reactive } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import InvoiceItems from './Partials/InvoiceItems.vue'

const props = defineProps({
    invoice: Object,
})

const footer = reactive({
    subtotal: props.invoice.subtotal,
    tax: props.invoice.tax,
    discount: props.invoice.discount,
    total: props.invoice.total,
})
</script>

<template>
    <AppLayout :title="`Factuur #${invoice.invoice_number}`">
        <template #header>
            <h1 class="text-3xl font-semibold leading-tight text-gray-800">
                Factuur #{{ invoice.invoice_number }}
            </h1>
            <span
                v-if="invoice.deleted_at"
                class="text-2xl font-medium italic text-red-600"
                >Gearchiveerd</span
            >
        </template>

        <div class="grid grid-cols-6 gap-x-12 gap-y-28">
            <div class="col-span-6">
                <h3
                    class="mb-8 flex items-center space-x-2 text-2xl font-bold leading-tight tracking-tight text-gray-800"
                >
                    <div
                        class="flex h-6 w-6 items-center justify-center rounded-full bg-sky-600"
                    >
                        <span class="text-sm text-gray-50">1</span>
                    </div>
                    <span>Factuurkop</span>
                </h3>

                <div class="ml-8 grid grid-cols-2 gap-12">
                    <div class="grid grid-cols-2 gap-4">
                        <span class="font-semibold text-gray-500">Klant</span>
                        <span class="font-semibold"
                            >{{ invoice.customer.name }} <br />
                            {{ invoice.customer.shipping_address.address }}
                            <br />
                            {{ invoice.customer.shipping_address.zipcode }},
                            {{ invoice.customer.shipping_address.city }} <br />
                            {{ invoice.customer.shipping_address.state }},
                            {{
                                invoice.customer.shipping_address.country
                            }}</span
                        >

                        <span class="font-semibold text-gray-500"
                            >Contactpersoon</span
                        >
                        <span class="font-semibold"
                            >{{ invoice.customer.main_contact.name }} <br />
                            {{ invoice.customer.main_contact.email }} <br />
                            {{ invoice.customer.main_contact.phone }}</span
                        >
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <span class="font-semibold text-gray-500"
                            >Factuur #</span
                        >
                        <span class="font-semibold"
                            >#{{ invoice.invoice_number }}</span
                        >

                        <span class="font-semibold text-gray-500">Datum</span>
                        <span class="font-semibold">{{
                            invoice.invoice_date
                        }}</span>

                        <span class="font-semibold text-gray-500"
                            >Vervaldatum</span
                        >
                        <span class="font-semibold">{{
                            invoice.due_date
                        }}</span>

                        <span class="font-semibold text-gray-500"
                            >Referentie</span
                        >
                        <span class="font-semibold">{{
                            invoice.reference
                        }}</span>

                        <span class="font-semibold text-gray-500">Periode</span>
                        <span class="font-semibold">{{ invoice.term }}</span>
                    </div>
                </div>
            </div>

            <div class="col-span-6">
                <h3
                    class="mb-8 flex items-center space-x-2 text-2xl font-bold leading-tight tracking-tight text-gray-800"
                >
                    <div
                        class="flex h-6 w-6 items-center justify-center rounded-full bg-sky-600"
                    >
                        <span class="text-sm text-gray-50">2</span>
                    </div>
                    <span>Factuurregels</span>
                </h3>

                <InvoiceItems
                    v-model="invoice.items"
                    :discount="invoice.discount"
                />
            </div>

            <div class="col-span-6">
                <h3
                    class="mb-8 flex items-center space-x-2 text-2xl font-bold leading-tight tracking-tight text-gray-800"
                >
                    <div
                        class="flex h-6 w-6 items-center justify-center rounded-full bg-sky-600"
                    >
                        <span class="text-sm text-gray-50">3</span>
                    </div>
                    <span>Factuurvoet</span>
                </h3>

                <div class="ml-8 grid grid-cols-3 gap-12">
                    <div class="col-span-2 grid w-2/3 grid-cols-2 gap-4">
                        <span class="font-semibold text-gray-500"
                            >Opmerkingen</span
                        >
                        <span class="col-span-2 font-medium">{{
                            invoice.comments
                        }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
