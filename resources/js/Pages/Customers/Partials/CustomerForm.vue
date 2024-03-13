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
    email: '',
    phone: '',
}
function addContact() {
    const newContact = structuredClone(contactDefaults)

    form.contacts.push(newContact)
}
function deleteContact(idx) {
    form.contacts.splice(idx, 1)
}

defineExpose({
    form,
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
            <TextField
                v-model="form.name"
                :error="form.errors.name"
                label="Naam"
            />
            <TextField
                v-model="form.email"
                :error="form.errors.email"
                label="Email"
            />
            <TextField
                v-model="form.phone"
                :error="form.errors.phone"
                label="Telefoon"
            />
            <TextField
                v-model="form.tax_number"
                :error="form.errors.tax_number"
                label="BTW"
            />
            <TextField
                v-model="form.kvk_number"
                :error="form.errors.kvk_number"
                label="KVK"
            />
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
            <TextField
                v-model="form.shippingAddress.address"
                :error="form.errors['shippingAddress.address']"
                label="Adres"
            />
            <TextField
                v-model="form.shippingAddress.zipcode"
                :error="form.errors['shippingAddress.zipcode']"
                label="Postcode"
            />
            <TextField
                v-model="form.shippingAddress.city"
                :error="form.errors['shippingAddress.city']"
                label="Plaatsnaam"
            />
            <TextField
                v-model="form.shippingAddress.state"
                :error="form.errors['shippingAddress.state']"
                label="Provincie"
            />
            <TextField
                v-model="form.shippingAddress.country"
                :error="form.errors['shippingAddress.country']"
                label="Land"
            />
        </div>

        <hr />

        <h4 class="my-8 text-lg font-semibold">Factuuradres</h4>

        <span class="col-span-2 mb-8 flex items-center gap-4">
            <Checkbox
                v-model:checked="sameAsShippingAddress"
                class="mt-1 self-start"
                id="sameAsShippingAddress"
            />
            <span class="w-1/2">
                <label for="sameAsShippingAddress" class="block font-medium"
                    >Zelfde als vestigingsadres</label
                >
                <span class="text-sm text-gray-500"
                    >Maak het factuuradres hetzelfde als het
                    vestigingsadres</span
                >
            </span>
        </span>

        <div class="grid grid-cols-2 gap-8" v-if="!sameAsShippingAddress">
            <TextField
                v-model="form.billingAddress.address"
                :error="form.errors['billingAddress.address']"
                label="Adres"
                :disabled="sameAsShippingAddress"
            />
            <TextField
                v-model="form.billingAddress.zipcode"
                :error="form.errors['billingAddress.zipcode']"
                label="Postcode"
                :disabled="sameAsShippingAddress"
            />
            <TextField
                v-model="form.billingAddress.city"
                :error="form.errors['billingAddress.city']"
                label="Plaatsnaam"
                :disabled="sameAsShippingAddress"
            />
            <TextField
                v-model="form.billingAddress.state"
                :error="form.errors['billingAddress.state']"
                label="Provincie"
                :disabled="sameAsShippingAddress"
            />
            <TextField
                v-model="form.billingAddress.country"
                :error="form.errors['billingAddress.country']"
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

        <template v-if="form.contacts.length > 0">
            <div
                v-for="(_, idx) in form.contacts"
                class="my-6 flex items-center justify-between gap-4"
            >
                <TextField
                    class="grow"
                    v-model="form.contacts[idx].name"
                    :error="form.errors[`contacts.${idx}.name`]"
                    label="Naam"
                />
                <TextField
                    class="grow"
                    v-model="form.contacts[idx].email"
                    :error="form.errors[`contacts.${idx}.email`]"
                    label="Email"
                />
                <TextField
                    class="grow"
                    v-model="form.contacts[idx].phone"
                    :error="form.errors[`contacts.${idx}.phone`]"
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
        </template>

        <div
            v-if="form.contacts.length <= 0"
            class="my-12 grid place-items-center"
        >
            <NoData class="h-32" />
            <span class="mt-4 text-sm text-gray-600"
                >Nog geen contactpersonen toegevoegd.</span
            >
        </div>
    </div>
</template>
