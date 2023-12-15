<script setup>
import { ref, useSlots } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/solid'
import SlideOver from '@/Components/SlideOver.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'

const emit = defineEmits(['slide:canceled', 'slide:confirmed', 'slide:closed'])

const slots = useSlots()

const show = ref(false)
const open = () => (show.value = true)
const close = () => (show.value = false)

defineExpose({
    open,
    close,
})
</script>

<template>
    <SlideOver :open="show" @click-outside="emit('slide:closed')">
        <template #side v-if="slots.side">
            <div class="flex h-full flex-col">
                <header class="h-36 rounded-tl-md bg-indigo-600 p-8 text-white">
                    <div class="mb-4 flex items-center justify-between">
                        <h1 class="text-2xl font-semibold leading-tight">
                            <slot name="side-title" />
                        </h1>
                    </div>

                    <p class="text-gray-100">
                        <slot name="side-description" />
                    </p>
                </header>

                <main class="grow p-8">
                    <slot name="side" />
                </main>

                <footer
                    class="flex h-20 items-center justify-end gap-6 bg-gray-100 p-8"
                >
                    <slot name="side-footer" />
                </footer>
            </div>
        </template>

        <div class="flex h-full flex-col">
            <header class="h-36 rounded-tl-md bg-indigo-600 p-8 text-white">
                <div class="mb-4 flex items-center justify-between">
                    <h1 class="text-2xl font-semibold leading-tight">
                        <slot name="title" />
                    </h1>

                    <PrimaryButton
                        tabindex="1"
                        @click="emit('slide:closed')"
                        class="bg-transparent hover:bg-white hover:bg-opacity-10 focus:bg-white focus:bg-opacity-10 active:bg-transparent"
                    >
                        <XMarkIcon class="h-5 w-5" />
                    </PrimaryButton>
                </div>

                <p class="text-gray-100">
                    <slot name="description" />
                </p>
            </header>

            <main class="grow p-8">
                <slot />
            </main>

            <footer
                class="flex h-20 items-center justify-end gap-6 bg-gray-100 p-8"
            >
                <SecondaryButton @click="emit('slide:canceled')">
                    Annuleren
                </SecondaryButton>

                <PrimaryButton @click="emit('slide:confirmed')">
                    Opslaan
                </PrimaryButton>
            </footer>
        </div>
    </SlideOver>
</template>
