<script setup>
import { nextTick, onMounted, ref, toRefs, useSlots, watch } from 'vue'
import { onClickOutside } from '@vueuse/core'
import { useFocusTrap } from '@vueuse/integrations/useFocusTrap'

const props = defineProps({
    open: {
        type: Boolean,
        required: true,
    },
})
const { open } = toRefs(props)

const emit = defineEmits(['click-outside'])

const mounted = ref(false)
onMounted(() => {
    mounted.value = true
})

const slots = useSlots()
const panel = ref(null)
const main = ref(null)
onClickOutside(main, () => open.value && !slots.side && emit('click-outside'))

const { activate, deactivate } = useFocusTrap(panel)
watch(open, (value) => nextTick(() => (value ? activate() : deactivate())))
</script>

<template>
    <teleport to="#main" v-if="mounted">
        <transition
            enter-active-class="duration-300 ease-out"
            enter-from-class="transform opacity-0 backdrop-blur-none bg-opacity-0"
            enter-to-class="opacity-100 backdrop-blur-sm bg-opacity-50"
            leave-active-class="duration-200 ease-in"
            leave-from-class="opacity-100 backdrop-blur-sm bg-opacity-50"
            leave-to-class="transform opacity-0 bg-opacity-0 backdrop-blur-none"
        >
            <div
                v-show="open"
                class="absolute top-0 z-50 grid h-full w-full grid-cols-12 overflow-hidden"
            >
                <div
                    class="absolute inset-0 bg-gray-500 bg-opacity-50 backdrop-blur-sm transition-opacity"
                />

                <transition
                    enter-active-class="duration-300 ease-out"
                    enter-from-class="transform translate-x-10"
                    enter-to-class="transform translate-x-0"
                    leave-active-class="duration-200 ease-in"
                    leave-from-class="transform translate-x-0"
                    leave-to-class="transform translate-x-10"
                >
                    <div
                        ref="panel"
                        class="relative col-span-12 flex justify-end overflow-auto"
                        v-show="open"
                    >
                        <div
                            v-if="slots.side"
                            class="relative z-10 m-2 shrink-0 basis-[42%] overflow-y-auto rounded-md bg-white scrollbar:!h-1.5 scrollbar:!w-1.5 scrollbar:bg-transparent scrollbar-track:!rounded-r scrollbar-track:!bg-zinc-200 scrollbar-thumb:!rounded scrollbar-thumb:!bg-zinc-400"
                        >
                            <slot name="side" />
                        </div>

                        <div
                            ref="main"
                            class="relative z-10 row-start-1 m-2 ml-0 overflow-y-auto rounded-md bg-white scrollbar:!h-1.5 scrollbar:!w-1.5 scrollbar:bg-transparent scrollbar-track:!rounded-r scrollbar-track:!bg-zinc-200 scrollbar-thumb:!rounded scrollbar-thumb:!bg-zinc-400"
                            :class="{
                                grow: slots.side,
                            }"
                        >
                            <slot />
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>
