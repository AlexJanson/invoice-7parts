<script setup>
import { computed, ref, toRefs } from 'vue'
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import {
    CalendarIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
} from '@heroicons/vue/20/solid'
import PrimaryButton from './PrimaryButton.vue'
import SecondaryButton from './SecondaryButton.vue'
import {
    startOfToday,
    format,
    parse,
    eachDayOfInterval,
    endOfMonth,
    startOfWeek,
    endOfWeek,
    isSameMonth,
    add,
    isToday,
    isEqual,
} from 'date-fns'
import nl from 'date-fns/esm/locale/nl'

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    hideLabel: Boolean,
    modelValue: Date,
    error: String,
})
const { modelValue } = toRefs(props)

const emit = defineEmits(['update:modelValue'])

let previousSelected
function handleOpen() {
    currentMonth.value = format(selected.value, 'MMM-yyyy')
    previousSelected = selected.value
}

const today = startOfToday()
const selected = computed(() => modelValue.value ?? today)
emit('update:modelValue', selected.value)
const currentMonth = ref(format(selected.value, 'MMM-yyyy'))
const firstDayCurrentMonth = computed(() =>
    parse(currentMonth.value, 'MMM-yyyy', new Date()),
)

const days = computed(() =>
    eachDayOfInterval({
        start: startOfWeek(firstDayCurrentMonth.value, { weekStartsOn: 1 }),
        end: endOfWeek(endOfMonth(firstDayCurrentMonth.value), {
            weekStartsOn: 1,
        }),
    }),
)

function nextMonth() {
    const firstDayNextMonth = add(firstDayCurrentMonth.value, { months: 1 })
    currentMonth.value = format(firstDayNextMonth, 'MMM-yyyy')
}

function previousMonth() {
    const firstDayLastMonth = add(firstDayCurrentMonth.value, { months: -1 })
    currentMonth.value = format(firstDayLastMonth, 'MMM-yyyy')
}

function revert(close) {
    emit('update:modelValue', previousSelected)
    close()
}
</script>

<template>
    <Popover v-slot="{ open }" class="relative">
        <PopoverButton as="div" @click="handleOpen">
            <span
                v-if="!hideLabel"
                class="mb-2 block select-none text-base font-semibold text-gray-600"
            >
                {{ label }}
            </span>
            <button
                class="relative w-full rounded-md border border-gray-300 py-1.5 pl-3 text-left shadow-sm transition duration-100 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus-visible:border-indigo-500 focus-visible:ring-1 focus-visible:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"
                :class="{
                    'bg-gray-100 text-gray-500': open,
                }"
            >
                <span>{{
                    format(modelValue ?? today, 'd MMMM, yyyy', { locale: nl })
                }}</span>

                <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                    <CalendarIcon class="h-5 w-5 text-gray-400" />
                </div>
            </button>
        </PopoverButton>

        <div class="mt-2 text-sm text-red-600" v-if="error">
            {{ error }}
        </div>

        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="translate-y-1 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="translate-y-1 opacity-0"
        >
            <PopoverPanel
                v-slot="{ close }"
                class="absolute left-1/2 z-10 mt-3 w-full -translate-x-1/2 transform bg-white"
            >
                <div
                    class="rounded-lg p-2 shadow-lg ring-1 ring-black ring-opacity-5"
                >
                    <div class="grid grid-cols-7 place-items-center py-4">
                        <button type="button" @click="previousMonth">
                            <ChevronLeftIcon class="h-6 w-6 text-gray-600" />
                        </button>

                        <span class="col-span-5 capitalize">{{
                            format(firstDayCurrentMonth, 'MMMM yyyy', {
                                locale: nl,
                            })
                        }}</span>

                        <button type="button" @click="nextMonth">
                            <ChevronRightIcon class="h-6 w-6 text-gray-600" />
                        </button>
                    </div>

                    <div class="mt-4 grid grid-cols-7 place-items-center">
                        <span class="text-sm font-semibold">Ma</span>
                        <span class="text-sm font-semibold">Di</span>
                        <span class="text-sm font-semibold">Wo</span>
                        <span class="text-sm font-semibold">Do</span>
                        <span class="text-sm font-semibold">Vr</span>
                        <span class="text-sm font-semibold">Za</span>
                        <span class="text-sm font-semibold">Zo</span>
                    </div>

                    <div class="mt-2 grid grid-cols-7 place-items-center">
                        <div v-for="day in days" class="py-2">
                            <button
                                @click="emit('update:modelValue', day)"
                                class="mx-auto flex h-8 w-8 items-center justify-center rounded-full"
                                :class="{
                                    'text-gray-950': isSameMonth(
                                        day,
                                        firstDayCurrentMonth,
                                    ),
                                    'text-gray-400': !isSameMonth(
                                        day,
                                        firstDayCurrentMonth,
                                    ),
                                    'text-indigo-600': isToday(day),
                                    'bg-indigo-600 text-white':
                                        isEqual(day, selected) && isToday(day),
                                    'bg-gray-950 font-semibold text-white':
                                        isEqual(day, selected) && !isToday(day),
                                }"
                            >
                                <time :datetime="format(day, 'yyyy-MM-dd')">{{
                                    format(day, 'd')
                                }}</time>
                            </button>
                        </div>
                    </div>

                    <hr />

                    <footer
                        class="flex items-center justify-between gap-4 px-2 pb-2 pt-4"
                    >
                        <SecondaryButton class="w-full" @click="revert(close)">
                            <span class="w-full">Annuleren</span>
                        </SecondaryButton>

                        <PrimaryButton class="w-full" @click="close()">
                            <span class="w-full">Selecteren</span>
                        </PrimaryButton>
                    </footer>
                </div>
            </PopoverPanel>
        </transition>
    </Popover>
</template>
