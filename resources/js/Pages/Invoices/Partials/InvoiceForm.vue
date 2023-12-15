<script setup>
import DatePicker from '@/Components/DatePicker.vue'
import SelectField from '@/Components/SelectField.vue'
import TextField from '@/Components/TextField.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { add, startOfToday } from 'date-fns'
import { computed, ref, watch } from 'vue'

const form = useForm({
    customer: null,
    invoice_date: null,
    contact: null,
    due_date: add(startOfToday(), { weeks: 2 }),
    invoice_number: null,
    reference: null,
    term: null,
    comments: null,
    items: [],
    discount: null,
})

const customers = computed(() => usePage().props.customers)
const contacts = ref([])
const processing = ref(false)
watch(
    () => form.customer,
    async (customer) => {
        if (!customer) {
            contacts.value = []
            return
        }

        processing.value = true
        contacts.value = []
        contacts.value = (
            await axios.get(route('customer.contacts', customer))
        ).data.contacts
        processing.value = false

        if (form.contact)
            contacts.value.some((contact) => contact.id == form.contact.id)
                ? form.reset('contact')
                : (form.contact = null)
    },
)

defineExpose({
    form,
    contacts,
})
</script>

<template>
    <div class="mb-16">
        <div class="grid grid-cols-2 gap-8">
            <SelectField
                class="z-10"
                :values="customers ?? []"
                :display-value="(item) => item?.name"
                search-term="name"
                v-model="form.customer"
                label="Klant"
                :error="form.errors.customer"
            />
            <DatePicker
                v-model="form.invoice_date"
                label="Datum"
                :error="form.errors.invoice_date"
            />
            <SelectField
                :values="contacts ?? []"
                :display-value="(item) => item?.name"
                search-term="name"
                v-model="form.contact"
                label="Contactpersoon"
                :error="form.errors.contact"
                :disabled="contacts.length === 0 || processing"
                :loading="processing"
            />
            <DatePicker
                v-model="form.due_date"
                label="Vervaldatum"
                :error="form.errors.due_date"
            />
            <TextField
                v-model="form.invoice_number"
                label="Factuurnummer"
                :error="form.errors.invoice_number"
                disabled
            />
            <TextField
                v-model="form.reference"
                label="Referentie"
                :error="form.errors.reference"
            />
            <TextField
                v-model="form.term"
                label="Periode"
                :error="form.errors.term"
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
