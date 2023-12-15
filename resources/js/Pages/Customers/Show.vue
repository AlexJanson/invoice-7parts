<script setup>
import { computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import ListInvoicesForCustomer from './Partials/ListInvoicesForCustomer.vue'

const props = defineProps({
    customer: Object,
    invoices: Object,
})

const pagination = computed(() => {
    return {
        from: props.invoices.from,
        to: props.invoices.to,
        total: props.invoices.total,
        prev_page_url: props.invoices.prev_page_url,
        next_page_url: props.invoices.next_page_url,
    }
})

const invoices = computed(() => props.invoices.data)
</script>

<template>
    <AppLayout :title="customer.name">
        <template #header>
            <h1 class="text-3xl font-semibold leading-tight text-gray-800">
                {{ customer.name }}
            </h1>
        </template>

        <div class="grid grid-cols-6 gap-x-12 gap-y-28">
            <div class="col-span-3">
                <h3
                    class="mb-8 flex items-center space-x-2 text-2xl font-bold leading-tight tracking-tight text-gray-800"
                >
                    <div
                        class="flex h-6 w-6 items-center justify-center rounded-full bg-sky-600"
                    >
                        <span class="text-sm text-gray-50">1</span>
                    </div>
                    <span>Algemene gegevens</span>
                </h3>

                <div class="ml-8 grid w-1/2 grid-cols-2 gap-4">
                    <span class="font-semibold text-gray-500">Naam</span>
                    <span class="font-semibold">{{ customer.name }}</span>

                    <span class="font-semibold text-gray-500">Email</span>
                    <span class="font-semibold">{{ customer.email }}</span>

                    <span class="font-semibold text-gray-500">Telefoon</span>
                    <span class="font-semibold">{{ customer.phone }}</span>

                    <span class="font-semibold text-gray-500">BTW</span>
                    <span class="font-semibold">{{ customer.tax }}</span>

                    <span class="font-semibold text-gray-500">KVK</span>
                    <span class="font-semibold">{{ customer.kvk }}</span>
                </div>
            </div>

            <div class="col-span-3">
                <h3
                    class="mb-8 flex items-center space-x-2 text-2xl font-bold leading-tight tracking-tight text-gray-800"
                >
                    <div
                        class="flex h-6 w-6 items-center justify-center rounded-full bg-sky-600"
                    >
                        <span class="text-sm text-gray-50">2</span>
                    </div>
                    <span>Vestigings- & Factuuradres</span>
                </h3>

                <div class="ml-8 grid grid-cols-2 gap-12">
                    <div>
                        <h4 class="mb-8 text-lg font-semibold">
                            Vestigingsadres
                        </h4>

                        <div
                            class="grid grid-cols-2 gap-4 border-t-2 border-gray-100 pt-6"
                        >
                            <span class="font-semibold text-gray-500"
                                >Adres</span
                            >
                            <span class="font-semibold">{{
                                customer.shipping_address.address
                            }}</span>

                            <span class="font-semibold text-gray-500"
                                >Postcode</span
                            >
                            <span class="font-semibold">{{
                                customer.shipping_address.zipcode
                            }}</span>

                            <span class="font-semibold text-gray-500"
                                >Plaatsnaam</span
                            >
                            <span class="font-semibold"
                                >{{ customer.shipping_address.city }},
                                {{ customer.shipping_address.state }}</span
                            >

                            <span class="font-semibold text-gray-500"
                                >Land</span
                            >
                            <span class="font-semibold">{{
                                customer.shipping_address.country
                            }}</span>
                        </div>
                    </div>

                    <div>
                        <h4 class="mb-8 text-lg font-semibold">Factuuradres</h4>

                        <div
                            class="grid grid-cols-2 gap-4 border-t-2 border-gray-100 pt-6"
                        >
                            <span class="font-semibold text-gray-500"
                                >Adres / Postbus</span
                            >
                            <span class="font-semibold">{{
                                customer.billing_address.address
                            }}</span>

                            <span class="font-semibold text-gray-500"
                                >Postcode</span
                            >
                            <span class="font-semibold">{{
                                customer.billing_address.zipcode
                            }}</span>

                            <span class="font-semibold text-gray-500"
                                >Plaatsnaam</span
                            >
                            <span class="font-semibold"
                                >{{ customer.billing_address.city }},
                                {{ customer.billing_address.state }}</span
                            >

                            <span class="font-semibold text-gray-500"
                                >Land</span
                            >
                            <span class="font-semibold">{{
                                customer.billing_address.country
                            }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-3">
                <h3
                    class="mb-8 flex items-center space-x-2 text-2xl font-bold leading-tight tracking-tight text-gray-800"
                >
                    <div
                        class="flex h-6 w-6 items-center justify-center rounded-full bg-sky-600"
                    >
                        <span class="text-sm text-gray-50">3</span>
                    </div>
                    <span>Contactpersonen</span>
                </h3>

                <div class="ml-8">
                    <h4 class="mb-8 text-lg font-semibold">
                        Hoofdcontactpersoon
                    </h4>

                    <div class="grid w-1/2 grid-cols-2 gap-4">
                        <span class="font-semibold text-gray-500">Naam</span>
                        <span class="font-semibold">{{
                            customer.main_contact.name
                        }}</span>

                        <span class="font-semibold text-gray-500">Email</span>
                        <span class="font-semibold">{{
                            customer.main_contact.email
                        }}</span>

                        <span class="font-semibold text-gray-500"
                            >Telefoon</span
                        >
                        <span class="font-semibold">{{
                            customer.main_contact.phone
                        }}</span>
                    </div>

                    <h4 class="my-8 text-lg font-semibold">
                        Overige contactpersonen
                    </h4>

                    <div
                        class="divide-y-2 divide-gray-100 [&>*:first-child]:pb-6 [&>*:not(:first-child)]:py-6"
                    >
                        <div
                            v-for="contact in customer.other_contacts"
                            class="grid w-1/2 grid-cols-2 gap-4"
                        >
                            <span class="font-semibold text-gray-500"
                                >Naam</span
                            >
                            <span class="font-semibold">{{
                                contact.name
                            }}</span>

                            <span class="font-semibold text-gray-500"
                                >Email</span
                            >
                            <span class="font-semibold">{{
                                contact.email
                            }}</span>

                            <span class="font-semibold text-gray-500"
                                >Telefoon</span
                            >
                            <span class="font-semibold">{{
                                contact.phone
                            }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-3 col-start-4 row-span-3 row-start-1">
                <h3
                    class="mb-8 flex items-center space-x-2 text-2xl font-bold leading-tight tracking-tight text-gray-800"
                >
                    <div
                        class="flex h-6 w-6 items-center justify-center rounded-full bg-sky-600"
                    >
                        <span class="text-sm text-gray-50">4</span>
                    </div>
                    <span>Facturen</span>
                </h3>

                <ListInvoicesForCustomer
                    :invoices="invoices"
                    :pagination="pagination"
                    :slug="customer.slug"
                />
            </div>
        </div>
    </AppLayout>
</template>
