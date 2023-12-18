<script setup>
import { useForm } from '@inertiajs/vue3'
import TextField from '@/Components/TextField.vue'
import MoneyField from '@/Components/MoneyField.vue'
import PercentageField from '@/Components/PercentageField.vue'
import SelectField from '@/Components/SelectField.vue'

const form = useForm({
    name: null,
    price: 0,
    tax: 0,
    unit: {
        unit: 'PIECES',
        name: 'stuks',
    },
    description: null,
})

const units = [
    {
        unit: 'PIECES',
        name: 'stuks',
    },
    {
        unit: 'HOURS',
        name: 'uren',
    },
]

defineExpose({
    form,
})
</script>

<template>
    <div class="mb-16">
        <div class="grid grid-cols-2 gap-8">
            <TextField
                v-model="form.name"
                :error="form.errors.name"
                label="Productnaam"
            />
            <MoneyField
                v-model="form.price"
                :error="form.errors.price"
                label="Bedrag"
            />
            <SelectField
                :values="units"
                :display-value="(item) => item?.name"
                search-term="name"
                by="unit"
                v-model="form.unit"
                label="Eenheid"
                :error="form.errors.unit"
            />
            <PercentageField
                v-model="form.tax"
                :error="form.errors.tax"
                label="BTW %"
            />
            <TextField
                v-model="form.description"
                label="Beschrijving"
                type="textarea"
                class="col-span-2"
                :error="form.errors.description"
            />
        </div>
    </div>
</template>
