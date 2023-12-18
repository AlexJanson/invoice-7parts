<script setup>
import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxLabel,
    ComboboxOption,
    ComboboxOptions,
    TransitionRoot,
} from '@headlessui/vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'
import Searching from '@/Components/Illustrations/Searching.vue'
import { computed, ref, toRefs } from 'vue'
import Spinner from './Spinner.vue'

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    values: {
        type: Array,
        required: true,
    },
    customValues: {
        type: Boolean,
        required: false,
        default: false,
    },
    displayValue: {
        type: Function,
        required: true,
    },
    searchTerm: {
        type: String,
        required: true,
    },
    modelValue: [String, Number, Object],
    hideLabel: Boolean,
    description: String,
    error: String,
    disabled: Boolean,
    loading: Boolean,
    by: {
        type: String,
        default: 'id',
    },
})
const { values, searchTerm, modelValue } = toRefs(props)

const emit = defineEmits(['update:modelValue'])

const selected = ref(modelValue)
const query = ref('')

const customOption = computed(() =>
    // TODO: make this a prop so that we can use the same display function
    query.value === '' ? {} : { id: -1, month: query.value },
)

const filtered = computed(() =>
    query.value === ''
        ? values.value
        : values.value.filter((v) =>
              v[searchTerm.value]
                  .toLowerCase()
                  .replace(/\s+/g, '')
                  .includes(query.value.toLowerCase().replace(/\s+/g, '')),
          ),
)
</script>

<template>
    <div>
        <Combobox
            immediate
            :model-value="selected"
            @update:model-value="
                (value) => emit('update:modelValue', value ?? null)
            "
            :by="by"
            nullable
            :disabled="disabled"
        >
            <div class="relative">
                <ComboboxLabel
                    v-if="!hideLabel"
                    class="mb-2 block text-base font-semibold text-gray-600"
                >
                    {{ label }}
                </ComboboxLabel>

                <div class="mb-2 text-sm text-gray-400" v-if="description">
                    {{ description }}
                </div>

                <div
                    class="relative w-full cursor-default rounded-md shadow-sm"
                >
                    <ComboboxInput
                        class="w-full rounded-md border-gray-300 py-2 pl-3 pr-10 leading-5 focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"
                        :display-value="displayValue"
                        @change="query = $event.target.value"
                        autocomplete="off"
                    />

                    <div
                        class="absolute bottom-1/2 right-5 flex translate-y-1/2 transform items-center"
                        v-if="loading"
                    >
                        <Spinner />
                    </div>

                    <ComboboxButton
                        class="absolute inset-y-0 right-0 flex items-center pr-2"
                    >
                        <ChevronUpDownIcon
                            class="h-5 w-5 text-gray-400"
                            aria-hidden="true"
                        />
                    </ComboboxButton>
                </div>

                <TransitionRoot
                    leave="transition ease-in duration-100"
                    leaveFrom="opacity-100"
                    leaveTo="opacity-0"
                    @after-leave="query = ''"
                >
                    <ComboboxOptions
                        class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                    >
                        <div
                            v-if="
                                filtered.length === 0 &&
                                query !== '' &&
                                !customValues
                            "
                            class="relative grid cursor-default select-none place-items-center px-4 py-2 text-sm text-gray-700"
                        >
                            <Searching class="h-32 w-32" />
                            <span class="mt-2">Niets gevonden.</span>
                        </div>

                        <ComboboxOption
                            v-if="customValues"
                            :value="customOption"
                        >
                        </ComboboxOption>

                        <ComboboxOption
                            v-for="item in filtered"
                            :key="item?.id"
                            :value="item"
                            v-slot="{ selected, active }"
                            as="template"
                        >
                            <li
                                class="relative cursor-default select-none py-2 pl-10 pr-4 text-sm"
                                :class="{
                                    'bg-indigo-500 text-white': active,
                                    'text-gray-900': !active,
                                }"
                            >
                                <span
                                    class="block truncate"
                                    :class="{
                                        'font-medium': selected,
                                        'font-normal': !selected,
                                    }"
                                >
                                    {{ displayValue(item) }}
                                </span>
                                <span
                                    v-if="selected"
                                    class="absolute inset-y-0 left-0 flex items-center pl-3"
                                    :class="{
                                        'text-white': active,
                                        'text-indigo-500': !active,
                                    }"
                                >
                                    <CheckIcon
                                        class="h-5 w-5"
                                        aria-hidden="true"
                                    />
                                </span>
                            </li>
                        </ComboboxOption>
                    </ComboboxOptions>
                </TransitionRoot>
            </div>
        </Combobox>

        <div class="mt-2 text-sm text-red-600" v-if="error">
            {{ error }}
        </div>
    </div>
</template>
