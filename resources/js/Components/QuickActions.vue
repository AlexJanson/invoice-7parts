<script setup>
import QuickActionButton from './QuickActionButton.vue'
import { PlusIcon } from '@heroicons/vue/20/solid'
</script>

<template>
    <div class="m-3 flex gap-2">
        <QuickActionButton
            :href="route('customer.create')"
            bg-class="bg-indigo-500 hover:bg-indigo-600"
        >
            <div class="flex items-center gap-0.5">
                <PlusIcon class="h-4 w-4" />
                <span class="text-sm">Klant</span>
            </div>
        </QuickActionButton>

        <QuickActionButton
            :href="route('product.create')"
            bg-class="bg-amber-500 hover:bg-amber-600"
        >
            <div class="flex items-center gap-0.5">
                <PlusIcon class="h-4 w-4" />
                <span class="text-sm">Product</span>
            </div>
        </QuickActionButton>

        <QuickActionButton
            :href="route('invoice.create')"
            bg-class="bg-sky-500 hover:bg-sky-600"
        >
            <div class="flex items-center gap-0.5">
                <PlusIcon class="h-4 w-4" />
                <span class="text-sm">Factuur</span>
            </div>
        </QuickActionButton>

        <QuickActionButton
            :href="route('payment.create')"
            bg-class="bg-purple-500 hover:bg-purple-600"
        >
            <div class="flex items-center gap-0.5">
                <PlusIcon class="h-4 w-4" />
                <span class="text-sm">Betaling</span>
            </div>
        </QuickActionButton>
    </div>
</template>
