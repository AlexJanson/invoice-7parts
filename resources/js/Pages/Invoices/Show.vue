<script setup>
import { computed, reactive, ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import InvoiceItems from './Partials/InvoiceItems.vue'
import BaseTable from '@/Components/BaseTable.vue'
import {
    EllipsisVerticalIcon,
    TrashIcon,
    EyeIcon,
} from '@heroicons/vue/24/solid'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import { formatMoney } from '@/helpers'
import Toggle from '@/Components/Toggle.vue'

const props = defineProps({
    invoice: Object,
})

const footer = reactive({
    subtotal: props.invoice.subtotal,
    tax: props.invoice.tax,
    discount: props.invoice.discount,
    total: props.invoice.total,
})

const showEnglishInvoice = ref(false)

const invoiceRoute = computed(() =>
    route('invoice.stream', {
        invoice: props.invoice,
        locale: showEnglishInvoice.value ? 'en' : null,
    }),
)
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
                            {{ invoice.customer.shipping_address.zipcode }}
                            {{ invoice.customer.shipping_address.city }} <br />
                            {{ invoice.customer.shipping_address.state }} <br />
                            {{
                                invoice.customer.shipping_address.country
                            }}</span
                        >

                        <span class="font-semibold text-gray-500"
                            >Contactpersoon</span
                        >
                        <span class="font-semibold"
                            >{{ invoice.contact?.name }} <br />
                            {{ invoice.contact?.email }} <br />
                            {{ invoice.contact?.phone }}</span
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

            <div class="col-span-6 mb-20">
                <h3
                    class="mb-8 flex items-center space-x-2 text-2xl font-bold leading-tight tracking-tight text-gray-800"
                >
                    <div
                        class="flex h-6 w-6 items-center justify-center rounded-full bg-sky-600"
                    >
                        <span class="text-sm text-gray-50">4</span>
                    </div>
                    <span>Betalingen</span>
                </h3>

                <div class="w-1/2" v-if="invoice.payments.length > 0">
                    <BaseTable>
                        <template #head>
                            <th>
                                <span
                                    class="flex items-center rounded-lg px-2 py-1 outline-none"
                                >
                                    Datum
                                </span>
                            </th>
                            <th align="right">
                                <span
                                    class="flex items-center rounded-lg px-2 py-1 outline-none"
                                >
                                    Bedrag
                                </span>
                            </th>
                        </template>
                        <tr
                            v-for="payment in invoice.payments"
                            :key="payment.id"
                            class="h-16"
                        >
                            <td class="pl-2">{{ payment.payment_date }}</td>
                            <td class="pl-2">
                                {{ formatMoney(payment.amount) }}
                            </td>
                        </tr>
                    </BaseTable>
                </div>

                <div
                    v-else
                    class="ml-8 cursor-default select-none text-sm text-gray-700"
                >
                    <span class="mt-2">
                        Er zijn nog geen betalingen gedaan.
                    </span>
                </div>
            </div>

            <div class="col-span-6 mb-20">
                <Toggle class="mb-4 ml-8" v-model="showEnglishInvoice">
                    <span>Engels</span>
                </Toggle>

                <iframe
                    class="h-[40rem] w-full"
                    :src="invoiceRoute"
                    frameborder="0"
                ></iframe>
            </div>
        </div>
    </AppLayout>
</template>
