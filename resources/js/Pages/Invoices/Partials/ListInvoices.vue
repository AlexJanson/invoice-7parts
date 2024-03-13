<script setup>
import OrderedTable from '@/Components/OrderedTable.vue'
import PaymentStatusIcon from '@/Components/PaymentStatusIcon.vue'
import PaymentStatusSort from '@/Components/PaymentStatusSort.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import { formatMoney } from '@/helpers'
import { Link } from '@inertiajs/vue3'
import { PlusIcon } from '@heroicons/vue/24/solid'
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    invoices: Array,
    pagination: Object,
})

const invoices = {
    cols: [
        {
            name: 'Datum',
            id: 'date',
        },
        {
            name: 'Factuur #',
            id: 'invoice-number',
        },
        {
            name: 'Klant',
            id: 'customer',
        },
        {
            name: 'Referentie',
            id: 'reference',
            sortable: false,
        },
        {
            name: 'Betaald',
            id: 'paid',
            sortComponent: PaymentStatusSort,
        },
        {
            name: 'Openst. bedrag',
            id: 'due-amount',
        },
        {
            name: 'Betaling toevoegen',
            id: '',
            sortable: false,
        },
        {
            name: 'Totaal',
            id: 'total',
            align: 'right',
        },
    ],
    rows: computed(() => props.invoices),
    pagination: computed(() => props.pagination),
    route: {
        name: 'invoice.show',
        paramKey: 'invoice_number',
        paramName: 'invoice',
    },
}
</script>

<template>
    <OrderedTable :data="invoices">
        <template v-slot="{ current }">
            <td class="pl-2">{{ current.invoice_date }}</td>
            <td class="pl-2 font-semibold">
                <Link
                    :href="
                        route('invoice.show', {
                            invoice_number: current.invoice_number,
                        })
                    "
                >
                    <span class="underline">
                        #{{ current.invoice_number }}
                    </span>
                </Link>
            </td>
            <td class="w-1/3 pl-2 font-semibold">
                <Link
                    class="underline"
                    :href="route('customer.show', { slug: current.slug })"
                >
                    {{ current.name }}
                </Link>
            </td>
            <td class="pl-2">
                {{ current.reference ? current.reference : '-' }}
            </td>
            <td class="pl-2">
                <PaymentStatusIcon :status="current.payment_status" />
            </td>
            <td class="pl-2 font-semibold">
                {{ formatMoney(current.due_amount) }}
            </td>
            <td class="pl-2">
                <SecondaryButton
                    :disabled="current.due_amount <= 0"
                    @click="
                        router.visit(
                            route('payment.create', {
                                invoice: current,
                            }),
                        )
                    "
                >
                    <PlusIcon class="-ml-1 mr-2 h-4 w-4" />
                    Nieuwe betaling
                </SecondaryButton>
            </td>
            <td class="pr-4 font-semibold" align="right">
                {{ formatMoney(current.total) }}
            </td>
        </template>
    </OrderedTable>
</template>
