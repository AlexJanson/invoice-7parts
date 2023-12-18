<script setup>
import {
    EllipsisVerticalIcon,
    PencilSquareIcon,
    PlusIcon,
    TrashIcon,
} from '@heroicons/vue/20/solid'
import { VueDraggableNext as draggable } from 'vue-draggable-next'
import Selection from '@/Components/Illustrations/Selection.vue'
import PercentageField from '@/Components/PercentageField.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import { Squares2X2Icon } from '@heroicons/vue/24/outline'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import QuantityField from '@/Components/QuantityField.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import DialogModal from '@/Components/DialogModal.vue'
import SelectField from '@/Components/SelectField.vue'
import MoneyField from '@/Components/MoneyField.vue'
import TextField from '@/Components/TextField.vue'
import Dropdown from '@/Components/Dropdown.vue'
import { useForm } from '@inertiajs/vue3'
import { computed, ref, toRefs, watch } from 'vue'
import { formatMoney } from '@/helpers'
import { format } from 'v-money3'

const emit = defineEmits(['update:discount'])

const props = defineProps({
    modelValue: {
        type: Object,
        required: true,
    },
    editable: {
        type: Boolean,
        default: false,
    },
    products: {
        type: Array,
        required: false,
    },
    discount: {
        type: Number,
        required: false,
        default: 0,
    },
})
const { modelValue } = toRefs(props)

const showModal = ref(false)

const item = useForm({
    name: '',
    description: '',
    quantity: 100,
    unit: {
        unit: 'PIECES',
        name: 'stuks',
    },
    price: 0,
    total: 0,
    tax: 0,
})

const items = ref(modelValue)

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

function closeModal() {
    showModal.value = false
    selectedProduct.value = null
    item.clearErrors()
}

function addItem() {
    // transform the data
    item.total = (item.tax + 100) * (item.price / 100) * (item.quantity / 100)

    item.clearErrors()

    console.log(item.data())

    editing.value
        ? (items.value[editingIdx.value] = item.data())
        : items.value.push(item.data())

    // Reset the inputs
    item.reset()
    selectedProduct.value = null
    showModal.value = false
    editingIdx.value = null
    editing.value = false
}

function deleteItem(idx) {
    items.value.splice(idx, 1)
}

const editing = ref(false)
const editingIdx = ref(null)
function editItem(current, idx) {
    console.log(current, idx)
    item.name = current.name
    item.description = current.description
    item.quantity = current.quantity
    typeof current.unit === 'string'
        ? (item.unit = units.find((value) => value.unit === current.unit))
        : (item.unit = current.unit)
    item.price = current.price
    item.total = current.total
    item.tax = current.tax

    editing.value = true
    editingIdx.value = idx
    showModal.value = true
}

const config = {
    precision: 2,
    thousands: '.',
    decimal: ',',
    prefix: '',
    suffix: '',
}

const selectedProduct = ref(null)
watch(selectedProduct, (value) => {
    if (!value) return

    item.name = value.name
    item.description = value.description
    item.price = value.price
    item.tax = value.tax
    item.unit = units.find((unit) => unit.unit === value.unit)
})

const displayUnit = (unit) =>
    typeof unit === 'string'
        ? unit === 'PIECES'
            ? 'stuks'
            : 'uren'
        : unit.name

const subtotal = computed(() =>
    items.value.reduce(
        (carry, item) => (carry += item.price * (item.quantity / 100)),
        0,
    ),
)
const tax = computed(() =>
    items.value.reduce(
        (carry, item) =>
            (carry += item.tax * ((item.price / 100) * (item.quantity / 100))),
        0,
    ),
)
const discount = ref(props.discount)
const total = computed(() =>
    formatMoney(subtotal.value + tax.value - discount.value),
)
</script>

<template>
    <div>
        <template v-if="items.length > 0">
            <div class="mb-2 grid grid-cols-12 px-5 text-sm text-gray-600">
                <h3 class="col-span-4">Items</h3>
                <h3 class="col-span-2 text-center">Aantal</h3>
                <h3 class="col-span-2 text-center">Prijs</h3>
                <h3 class="col-span-1 text-right">BTW</h3>
                <h3 class="col-span-2 text-right">Totaal</h3>
            </div>

            <div class="rounded-xl bg-gray-50">
                <draggable
                    :list="items"
                    handle=".handle"
                    class="divide-y divide-gray-200/80"
                >
                    <div
                        v-for="(item, idx) in items"
                        :key="item.name"
                        class="grid grid-cols-12 content-start gap-2 p-5"
                    >
                        <div class="col-span-4 flex">
                            <Squares2X2Icon
                                class="handle h-5 w-5 shrink-0 cursor-grab"
                                :class="{
                                    hidden: !editable,
                                }"
                            />
                            <div
                                class="ml-2 overflow-hidden text-ellipsis"
                                :title="item.name"
                            >
                                <span
                                    class="block overflow-hidden text-ellipsis font-semibold text-indigo-500"
                                    >{{ item.name }}</span
                                >
                            </div>
                        </div>

                        <span class="col-span-2 text-center">
                            {{ format(item.quantity / 100, config) }}
                            {{ displayUnit(item.unit) }}
                        </span>

                        <span class="col-span-2 text-center">{{
                            formatMoney(item.price)
                        }}</span>

                        <span class="col-span-1 text-right"
                            >{{ item.tax }} %</span
                        >

                        <span class="col-span-2 text-right">{{
                            formatMoney(item.total)
                        }}</span>

                        <div
                            class="mx-2 flex space-x-2 self-start justify-self-end"
                        >
                            <Dropdown v-if="editable">
                                <template #trigger>
                                    <button
                                        type="button"
                                        class="h-6 w-6 pt-1 text-gray-400 transition duration-150 ease-in-out hover:text-gray-600"
                                    >
                                        <EllipsisVerticalIcon />
                                    </button>
                                </template>
                                <template #content>
                                    <DropdownLink
                                        as="button"
                                        @click="editItem(item, idx)"
                                    >
                                        <div class="flex items-center">
                                            <PencilSquareIcon
                                                class="mr-2 h-5 w-5"
                                            />
                                            Edit
                                        </div>
                                    </DropdownLink>
                                    <DropdownLink
                                        as="button"
                                        @click="deleteItem(idx)"
                                        bg-class="hover:bg-red-600 focus:bg-red-600"
                                    >
                                        <div class="flex items-center">
                                            <TrashIcon
                                                class="mr-2 h-5 w-5 transition duration-150 ease-in-out group-hover:text-white group-focus-visible:text-white"
                                            />
                                            <span
                                                class="transition duration-150 ease-in-out group-hover:text-white group-focus-visible:text-white"
                                            >
                                                Delete
                                            </span>
                                        </div>
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>

                        <span
                            v-if="item.description"
                            class="col-span-12 row-start-2 mx-7 block w-4/5 text-sm text-gray-600"
                        >
                            {{ item.description }}
                        </span>
                    </div>
                </draggable>
            </div>
        </template>

        <div
            v-else
            class="relative grid cursor-default select-none place-items-center px-4 py-2 text-sm text-gray-700"
        >
            <Selection class="h-80 w-80" />
            <span class="mt-2"
                >Ga aan de slag door een regel toe te voegen.</span
            >
        </div>

        <div class="mb-4 mt-8 flex w-full items-center justify-center">
            <SecondaryButton
                v-if="editable"
                @click="showModal = true"
                type="button"
                class="flex items-center text-gray-600 transition duration-100 hover:text-indigo-600"
            >
                <span>Regel toevoegen</span>
                <PlusIcon class="ml-1 h-4 w-4" />
            </SecondaryButton>

            <hr
                class="grow"
                :class="{
                    'ml-5': editable,
                }"
            />
        </div>

        <div class="grid grid-cols-12 items-end px-5">
            <span class="col-start-9">Subtotaal</span>
            <span class="col-span-2 text-right">{{
                formatMoney(subtotal)
            }}</span>

            <span class="col-start-9 mt-4">BTW</span>
            <!-- <PercentageField
                v-if="editable"
                v-model="tax"
                label="BTW"
                hide-label
                class="col-span-2 ml-auto mr-0 mt-4 text-right"
                align="right"
            /> -->
            <span class="col-span-2 text-right">{{ formatMoney(tax) }}</span>

            <span class="col-start-9 mt-4">Korting</span>
            <MoneyField
                v-if="editable"
                v-model="discount"
                @update:model-value="emit('update:discount', discount)"
                label="BTW"
                hide-label
                class="col-span-2 ml-auto mr-0 mt-4 text-right"
                align="right"
            />
            <span v-if="!editable" class="col-span-2 text-right">{{
                formatMoney(discount)
            }}</span>

            <span class="col-start-9 mt-8 text-lg font-semibold">Totaal</span>
            <span
                class="col-span-2 mt-8 text-right text-lg font-semibold text-indigo-500"
                >{{ total }}</span
            >
        </div>

        <DialogModal :show="showModal && editable" @close="showModal = false">
            <template #title> Factuurregel toevoegen </template>

            <template #content>
                <SelectField
                    class="relative z-10"
                    :values="products"
                    :display-value="(item) => item?.name"
                    search-term="name"
                    by="id"
                    v-model="selectedProduct"
                    label="Selecteer een product"
                    :disabled="products?.length <= 0"
                />

                <div class="relative my-6">
                    <div
                        class="absolute inset-0 flex items-center"
                        aria-hidden="true"
                    >
                        <div
                            class="w-full border-t-[1px] border-gray-200"
                        ></div>
                    </div>
                    <div
                        class="relative flex justify-center font-medium leading-normal"
                    >
                        <span class="bg-white px-6 text-base text-gray-700"
                            >Of creëer een nieuw product</span
                        >
                    </div>
                </div>

                <div class="mb-6 grid grid-cols-2 gap-8">
                    <TextField
                        class="col-span-2"
                        v-model="item.name"
                        label="Naam"
                        :error="item.errors.name"
                    />
                    <TextField
                        class="col-span-2"
                        v-model="item.description"
                        label="Beschrijving"
                        type="textarea"
                    />
                    <QuantityField v-model="item.quantity" label="Aantal" />
                    <SelectField
                        :values="units"
                        :display-value="(item) => item?.name"
                        search-term="name"
                        by="unit"
                        v-model="item.unit"
                        label="Eenheid"
                    />
                    <MoneyField v-model="item.price" label="Prijs" />
                    <PercentageField v-model="item.tax" label="BTW %" />
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="closeModal">
                    Annuleren
                </SecondaryButton>

                <PrimaryButton class="ml-3" @click="addItem">
                    Toevoegen
                </PrimaryButton>
            </template>
        </DialogModal>
    </div>
</template>
