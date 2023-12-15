<script setup>
import { useForm } from '@inertiajs/vue3'
import TextField from '@/Components/TextField.vue'
import MoneyField from '@/Components/MoneyField.vue'
import PercentageField from '@/Components/PercentageField.vue'

const form = useForm({
    name: null,
    price: 0,
    // tax: 0,
    description: null,
})

defineExpose({
    form,
})
</script>

<template>
    <div class="mb-16">
        <div class="grid grid-cols-2 gap-8">
            <TextField v-model="form.name" label="Productnaam" />
            <MoneyField v-model="form.price" label="Bedrag" />
            <!-- <PercentageField v-model="form.tax" label="BTW %" /> -->
            <TextField
                v-model="form.description"
                label="Beschrijving"
                type="textarea"
                class="col-span-2"
            />
        </div>
    </div>
</template>
