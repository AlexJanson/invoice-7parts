<script setup>
import { computed, ref, watch } from 'vue'
import axios from 'axios'
import { useForm, usePage } from '@inertiajs/vue3'
import SelectField from '@/Components/SelectField.vue'
import TextField from '@/Components/TextField.vue'
import DatePicker from '@/Components/DatePicker.vue'
import MoneyField from '@/Components/MoneyField.vue'
import { startOfToday } from 'date-fns'

const today = startOfToday()
const form = useForm({
    payment_date: today,
    customer_id: null,
    invoice_id: null,
    amount: 0,
    comments: null,
})

defineExpose({
    form,
})

const customers = computed(() => usePage().props.customers)
const invoices = ref([])
const processing = ref(false)
watch(
    () => form.customer_id,
    async (customer_id) => {
        if (!customer_id) {
            invoices.value = []
            return
        }

        processing.value = true
        invoices.value = []
        invoices.value = (
            await axios.get(route('customer.invoices', customer_id))
        ).data.invoices
        processing.value = false

        invoices.value.some(
            (invoice) =>
                invoice.invoice_number === form.invoice?.invoice_number,
        )
            ? form.reset('invoice')
            : (form.invoice = null)
    },
)
</script>

<template>
    <div class="mb-16">
        <div class="grid grid-cols-2 gap-8">
            <DatePicker
                v-model="form.payment_date"
                label="Datum"
                :error="form.errors.payment_date"
            />
            <SelectField
                :values="customers ?? []"
                :display-value="(item) => item?.name"
                search-term="name"
                v-model="form.customer_id"
                label="Klant"
                :error="form.errors.customer_id"
            />
            <SelectField
                :values="invoices ?? []"
                :display-value="(item) => item?.invoice_number"
                search-term="invoice_number"
                v-model="form.invoice_id"
                label="Factuur"
                :disabled="invoices.length === 0 || processing"
                :loading="processing"
                :error="form.errors.invoice_id"
            />
            <MoneyField
                v-model="form.amount"
                label="Bedrag"
                :error="form.errors.amount"
            />
            <TextField
                v-model="form.comments"
                label="Notities"
                type="textarea"
                class="col-span-2"
                :error="form.errors.comments"
            />
        </div>
    </div>
</template>
