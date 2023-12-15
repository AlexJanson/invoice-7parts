<script setup>
import { ref, watch } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/solid'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import TextField from '@/Components/TextField.vue'
import Checkbox from '@/Components/Checkbox.vue'
import { PlusIcon } from '@heroicons/vue/24/solid'
import {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from '@headlessui/vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'
import NoData from '@/Components/Illustrations/NoData.vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: null,
    email: null,
    phone: null,
    tax_number: null,
    kvk_number: null,
    shippingAddress: {
        address: null,
        zipcode: null,
        city: null,
        state: null,
        country: 'Nederland',
    },
    billingAddress: {
        address: null,
        zipcode: null,
        city: null,
        state: null,
        country: 'Nederland',
    },
    contacts: [],
})

const sameAsShippingAddress = ref(false)
watch(sameAsShippingAddress, (newValue) =>
    setBillingToShippingAddress(newValue),
)
function setBillingToShippingAddress(value) {
    if (value) {
        form.billingAddress = {
            ...form.shippingAddress,
        }
    } else {
        form.billingAddress = {
            address: null,
            zipcode: null,
            city: null,
            state: null,
            country: 'Nederland',
        }
    }
}

const contactDefaults = {
    name: 'Administratie',
    email: 'administratie@example.com',
    phone: '+31 0 612345678',
    default: false,
}
function addContact() {
    form.contacts.push(structuredClone(contactDefaults))
}
function deleteContact(idx) {
    form.contacts.splice(idx, 1)
}
const selectedPerson = ref(null)
watch(selectedPerson, (newContact, oldContact) => {
    if (oldContact) oldContact.default = 0
    newContact.default = 1
})

defineExpose({
    form,
    selectedPerson,
    sameAsShippingAddress,
})
</script>

<template>
    <div class="mb-16">
        <h3
            class="mb-8 flex items-center space-x-2 text-2xl font-bold leading-tight tracking-tight text-gray-800"
        >
            <div
                class="flex h-6 w-6 items-center justify-center rounded-full bg-sky-600"
            >
                <span class="text-sm text-gray-50">1</span>
            </div>
            <span>Algemene gegevens</span>
        </h3>

        <div class="grid grid-cols-2 gap-8">
            <TextField v-model="form.name" label="Naam" />
            <TextField v-model="form.email" label="Email" />
            <TextField v-model="form.phone" label="Telefoon" />
            <TextField v-model="form.tax_number" label="BTW" />
            <TextField v-model="form.kvk_number" label="KVK" />
        </div>
    </div>

    <div class="mb-16">
        <h3
            class="mb-8 flex items-center space-x-2 text-2xl font-bold leading-tight tracking-tight text-gray-800"
        >
            <div
                class="flex h-6 w-6 items-center justify-center rounded-full bg-sky-600"
            >
                <span class="text-sm text-gray-50">2</span>
            </div>
            <span>Vestigings- & Factuuradres</span>
        </h3>

        <h4 class="mb-8 text-lg font-semibold">Vestigingsadres</h4>

        <div class="mb-8 grid grid-cols-2 gap-8">
            <TextField v-model="form.shippingAddress.address" label="Adres" />
            <TextField
                v-model="form.shippingAddress.zipcode"
                label="Postcode"
            />
            <TextField v-model="form.shippingAddress.city" label="Plaatsnaam" />
            <TextField v-model="form.shippingAddress.state" label="Provincie" />
            <TextField v-model="form.shippingAddress.country" label="Land" />
        </div>

        <hr />

        <h4 class="my-8 text-lg font-semibold">Factuuradres</h4>

        <div class="grid grid-cols-2 gap-8" v-if="!sameAsShippingAddress">
            <TextField
                v-model="form.billingAddress.address"
                label="Adres"
                :disabled="sameAsShippingAddress"
            />
            <TextField
                v-model="form.billingAddress.zipcode"
                label="Postcode"
                :disabled="sameAsShippingAddress"
            />
            <TextField
                v-model="form.billingAddress.city"
                label="Plaatsnaam"
                :disabled="sameAsShippingAddress"
            />
            <TextField
                v-model="form.billingAddress.state"
                label="Provincie"
                :disabled="sameAsShippingAddress"
            />
            <TextField
                v-model="form.billingAddress.country"
                label="Land"
                :disabled="sameAsShippingAddress"
            />
        </div>

        <div class="grid grid-cols-2 gap-8" v-else>
            <TextField
                v-model="form.shippingAddress.address"
                label="Adres"
                :disabled="sameAsShippingAddress"
            />
            <TextField
                v-model="form.shippingAddress.zipcode"
                label="Postcode"
                :disabled="sameAsShippingAddress"
            />
            <TextField
                v-model="form.shippingAddress.city"
                label="Plaatsnaam"
                :disabled="sameAsShippingAddress"
            />
            <TextField
                v-model="form.shippingAddress.state"
                label="Provincie"
                :disabled="sameAsShippingAddress"
            />
            <TextField
                v-model="form.shippingAddress.country"
                label="Land"
                :disabled="sameAsShippingAddress"
            />
        </div>

        <span class="col-span-2 mt-8 flex items-center gap-4">
            <Checkbox
                v-model:checked="sameAsShippingAddress"
                class="mt-1 self-start"
            />
            <span class="w-1/2">
                <span class="block font-medium"
                    >Zelfde als vestigingsadres</span
                >
                <span class="text-sm text-gray-500"
                    >Maak het factuuradres hetzelfde als het
                    vestigingsadres</span
                >
            </span>
        </span>
    </div>

    <div class="mb-8">
        <div class="mb-8 flex items-center justify-between">
            <h3
                class="flex items-center space-x-2 text-2xl font-bold leading-tight tracking-tight text-gray-800"
            >
                <div
                    class="flex h-6 w-6 items-center justify-center rounded-full bg-sky-600"
                >
                    <span class="text-sm text-gray-50">3</span>
                </div>
                <span>Contactpersonen</span>
            </h3>

            <SecondaryButton
                @click="addContact"
                class="flex items-center text-gray-600 transition duration-100 hover:text-indigo-600"
            >
                Contactpersoon toevoegen
                <PlusIcon class="ml-1 h-4 w-4 shrink-0" />
            </SecondaryButton>
        </div>

        <div
            v-for="(_, idx) in form.contacts"
            class="my-6 flex items-center justify-between gap-4"
        >
            <TextField
                class="grow"
                v-model="form.contacts[idx].name"
                label="Naam"
            />
            <TextField
                class="grow"
                v-model="form.contacts[idx].email"
                label="Email"
            />
            <TextField
                class="grow"
                v-model="form.contacts[idx].phone"
                label="Telefoon"
            />

            <PrimaryButton
                @click="deleteContact(idx)"
                class="group shrink self-end bg-transparent text-gray-950 hover:bg-red-600 hover:text-white focus:bg-red-600 focus:text-white focus:ring-red-600 active:bg-red-600 active:text-white"
            >
                <XMarkIcon
                    class="h-5 w-5 text-gray-950 transition duration-150 ease-in-out group-hover:text-white"
                />
            </PrimaryButton>
        </div>

        <div
            v-if="form.contacts.length <= 0"
            class="my-12 grid place-items-center"
        >
            <NoData class="h-32" />
            <span class="mt-4 text-sm text-gray-600"
                >Nog geen contactpersonen toegevoegd.</span
            >
        </div>

        <div>
            <h4 class="my-4 mt-12 text-lg font-semibold">
                Hoofdcontactpersoon
            </h4>

            <Listbox
                v-model="selectedPerson"
                :disabled="form.contacts.length <= 0"
                by="id"
            >
                <div class="relative mt-1">
                    <ListboxButton
                        class="relative w-2/5 rounded-md border border-gray-300 py-2 pl-3 pr-10 text-left shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"
                    >
                        <span class="block truncate" v-if="selectedPerson"
                            >{{ selectedPerson.name }}
                            <span class="italic text-gray-500"
                                >({{ selectedPerson.email }})</span
                            >
                        </span>
                        <span class="block truncate italic text-gray-500" v-else
                            >Kies een hoofdcontactpersoon</span
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
                            class="absolute mt-1 max-h-60 w-2/5 overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                        >
                            <ListboxOption
                                v-slot="{ active, selected }"
                                v-for="contact in form.contacts"
                                :key="contact.name"
                                :value="contact"
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
                                        >{{ contact.name }}</span
                                    >
                                    <span
                                        v-if="selected"
                                        class="absolute inset-y-0 left-0 flex items-center pl-3 text-indigo-600"
                                    >
                                        <CheckIcon
                                            class="h-5 w-5"
                                            aria-hidden="true"
                                        />
                                    </span>
                                </li>
                            </ListboxOption>
                        </ListboxOptions>
                    </transition>
                </div>
            </Listbox>
        </div>
    </div>
</template>
