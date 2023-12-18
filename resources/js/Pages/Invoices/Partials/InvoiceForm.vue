<script setup>
import DatePicker from '@/Components/DatePicker.vue'
import SelectField from '@/Components/SelectField.vue'
import TextField from '@/Components/TextField.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { add, startOfToday } from 'date-fns'
import { computed, ref, watch } from 'vue'
import {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from '@headlessui/vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'

const form = useForm({
    customer: null,
    invoice_date: null,
    contact: null,
    due_date: add(startOfToday(), { weeks: 2 }),
    invoice_number: null,
    reference: null,
    term: null,
    year: 2023,
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

const years = [{ year: 2023 }, { year: 2024 }]

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
            <SelectField
                :values="[
                    { id: 1, month: 'Januari' },
                    { id: 0, month: 'Februari' },
                ]"
                :display-value="(item) => item?.month"
                search-term="month"
                v-model="form.term"
                label="Periode"
                :error="form.errors.term"
                custom-values
            />

            <div>
                <p class="mb-2 block text-base font-semibold text-gray-600">
                    Jaar
                </p>

                <Listbox v-model="form.year" by="id">
                    <div class="relative mt-1">
                        <ListboxButton
                            class="relative w-full rounded-md border border-gray-300 py-2 pl-3 pr-10 text-left shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"
                        >
                            <span class="block truncate" v-if="form.year"
                                >{{ form.year }}
                            </span>
                            <span
                                class="block truncate italic text-gray-500"
                                v-else
                                >Kies het jaartal</span
                            >
                            <span
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                            >
                                <ChevronUpDownIcon
                                    class="h-5 w-5 text-gray-400"
                                    aria-hidden="true"
                                />
                            </span>
                        </ListboxButton>

                        <transition
                            leave-active-class="transition duration-100 ease-in"
                            leave-from-class="opacity-100"
                            leave-to-class="opacity-0"
                        >
                            <ListboxOptions
                                class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                            >
                                <ListboxOption
                                    v-slot="{ active, selected }"
                                    v-for="{ year } in years"
                                    :key="year"
                                    :value="year"
                                    as="template"
                                >
                                    <li
                                        :class="[
                                            active
                                                ? 'bg-indigo-100 text-indigo-900'
                                                : 'text-gray-900',
                                            'relative cursor-default select-none py-2 pl-10 pr-4',
                                        ]"
                                    >
                                        <span
                                            :class="[
                                                selected
                                                    ? 'font-medium'
                                                    : 'font-normal',
                                                'block truncate',
                                            ]"
                                            >{{ year }}</span
                                        >
                                    </li>
                                </ListboxOption>
                            </ListboxOptions>
                        </transition>
                    </div>
                </Listbox>
            </div>

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
