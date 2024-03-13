<script setup>
import OrderedTable from '@/Components/OrderedTable.vue'
import PaymentStatusIcon from '@/Components/PaymentStatusIcon.vue'
import PaymentStatusSort from '@/Components/PaymentStatusSort.vue'
import { formatMoney } from '@/helpers'
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    dueInvoices: Object,
    pagination: Object,
})

const dueInvoices = {
    cols: [
        {
            name: 'Factuur #',
            id: 'number',
        },
        {
            name: 'Datum',
            id: 'date',
        },
        {
            name: 'Klant',
            id: 'customer',
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
            name: 'Totaal',
            id: 'total',
            align: 'right',
        },
    ],
    rows: computed(() => props.dueInvoices),
    pagination: computed(() => props.pagination),
    route: {
        name: 'invoice.show',
        paramKey: 'invoice_number',
        paramName: 'invoice',
    },
}
</script>

<template>
    <h3 class="mb-8 text-lg font-bold leading-tight text-gray-800">
        Openstaande facturen
    </h3>

    <OrderedTable :data="dueInvoices">
        <template v-slot="{ current }">
            <td class="font-semibold">
                <Link
                    class="underline"
                    :href="
                        route('invoice.show', {
                            invoice_number: current.invoice_number,
                        })
                    "
                >
                    #{{ current.invoice_number }}
                </Link>
            </td>
            <td>{{ current.invoice_date }}</td>
            <td class="w-1/3 font-semibold">
                <Link
                    class="underline"
                    :href="route('customer.show', { slug: current.slug })"
                >
                    {{ current.name }}
                </Link>
            </td>
            <td><PaymentStatusIcon :status="current.payment_status" /></td>
            <td class="font-semibold">{{ formatMoney(current.due_amount) }}</td>
            <td class="font-semibold" align="right">
                {{ formatMoney(current.total) }}
            </td>
        </template>
    </OrderedTable>
</template>
