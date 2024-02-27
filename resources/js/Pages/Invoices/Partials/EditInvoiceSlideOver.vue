<script setup>
import { nextTick, onMounted, reactive, ref, toRefs, watch } from 'vue'
import ConfirmationSlideOver from '@/Components/ConfirmationSlideOver.vue'
import { router, usePage } from '@inertiajs/vue3'
import { toReactive } from '@vueuse/core'
import { formatMoney, getMonthFromString } from '@/helpers'
import { parse } from 'date-fns'
import nl from 'date-fns/esm/locale/nl'
import InvoiceForm from './InvoiceForm.vue'
import InvoiceItems from './InvoiceItems.vue'
import BaseTable from '@/Components/BaseTable.vue'
import {
    EllipsisVerticalIcon,
    TrashIcon,
    EyeIcon,
    PlusIcon,
} from '@heroicons/vue/24/solid'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import DialogModal from '@/Components/DialogModal.vue'
import PaymentForm from './PaymentForm.vue'

const props = defineProps({
    payments: {
        type: Array,
        required: false,
        default: [],
    },
    invoice: Object,
})
// const { invoice } = toRefs(props)
const invoice = reactive({ ...props.invoice })

const invoiceForm = ref(null)
const slideOver = ref(null)
const itemsRef = ref(null)
const invoiceItems = ref(invoice.items)

const show = ref(false)
function open() {
    router.visit(
        route('invoice.edit', {
            invoice,
        }),
        {
            preserveState: true,
        },
    )
    slideOver.value.open()
}

function close() {
    if (invoiceForm.value.form.isDirty) {
        const result = confirm('Unsaved changes are you sure you want to exit?')
        result ? slideOver.value.close() : slideOver.value.open()
        if (result) {
            invoiceForm.value.form.reset()
            router.visit(route('invoices.index'), {
                preserveState: true,
            })
        }
        return
    }

    router.visit(route('invoices.index'), {
        preserveState: true,
    })
    slideOver.value.close()
}

function toggle() {
    show.value ? close() : open()
}

function setOpen(value) {
    value ? open() : close()
}

function submit() {
    // invoiceForm.value.form.items = invoice.value.items
    invoiceForm.value.form.items = invoiceItems.value
    // invoiceForm.value.form.discount = invoice.value.discount

    invoiceForm.value.form.clearErrors()

    invoiceForm.value.form
        .transform((data) => ({
            ...data,
            term: data.term?.month,
            contact: data.contact?.id,
            customer: data.customer?.id,
            items: data.items?.map((item) => ({
                ...item,
                unit: item.unit.unit ?? item.unit,
            })),
        }))
        .put(route('invoice.update', { invoice: props.invoice }))
}

onMounted(() => {
    // Next tick to have customerForm be defined.
    nextTick(() => {
        invoiceForm.value.form.defaults({
            customer: invoice.customer,
            invoice_date: parse(
                invoice.invoice_date,
                'd MMMM, yyyy',
                new Date(),
                {
                    locale: nl,
                },
            ),
            contact: invoice.contact,
            due_date: parse(invoice.due_date, 'd MMMM, yyyy', new Date(), {
                locale: nl,
            }),
            invoice_number: invoice.invoice_number,
            reference: invoice.reference,
            term: { id: getMonthFromString(invoice.term), month: invoice.term },
            comments: invoice.comments,
            year: invoice.year,
            discount: invoice.discount,
        })

        // Need to reset the form after updating the defaults!
        invoiceForm.value.form.reset()
    })
})

const paymentForm = ref(null)
const showPaymentForm = ref(false)
function openPaymentModal() {
    showPaymentForm.value = true

    nextTick(() => {
        paymentForm.value.form.defaults({
            customer_id: props.invoice.customer,
            invoice_id: props.invoice,
            amount: props.invoice.due_amount,
        })

        paymentForm.value.form.reset()
    })
}

function createPayment() {
    paymentForm.value.form.clearErrors()

    paymentForm.value.form
        .transform((data) => ({
            ...data,
            customer_id: data.customer_id.id,
            invoice_id: data.invoice_id.id,
        }))
        .post(route('payment.store'), {
            onSuccess: () => {
                paymentForm.value.form.reset()
                showPaymentForm.value = false
            },
        })
}

defineExpose({
    open,
    close,
    toggle,
    setOpen,
})
</script>

<template>
    <ConfirmationSlideOver
        v-if="invoice"
        @slide:confirmed="submit()"
        @slide:canceled="close()"
        @slide:closed="close()"
        ref="slideOver"
    >
        <!-- Main panel -->
        <template #title> Factuur regels </template>

        <template #description> Vul hieronder de factuurregels in. </template>

        <InvoiceItems
            v-model="invoiceItems"
            :products="usePage().props.products"
            editable
            :discount="invoice.discount"
            @update:discount="
                (discount) => (invoiceForm.form.discount = discount)
            "
            ref="itemsRef"
        />

        <!-- Side panel -->
        <template #side-title> Nieuwe factuur toevoegen </template>

        <template #side-description>
            Ga aan de slag door de onderstaande informatie in te vullen om een
            nieuwe factuur aan te maken.
        </template>

        <template #side>
            <Suspense>
                <InvoiceForm ref="invoiceForm" />
            </Suspense>

            <div class="mb-8 flex items-center justify-between">
                <h3
                    class="text-2xl font-bold leading-tight tracking-tight text-gray-800"
                >
                    <span>Betalingen</span>
                </h3>

                <SecondaryButton
                    @click="openPaymentModal"
                    type="button"
                    class="flex items-center text-gray-600 transition duration-100 hover:text-indigo-600"
                    :disabled="invoice.due_amount <= 0"
                >
                    <span>Betaling toevoegen</span>
                    <PlusIcon class="ml-1 h-4 w-4" />
                </SecondaryButton>
            </div>

            <BaseTable v-if="payments.length > 0">
                <template #head>
                    <th>
                        <span
                            class="flex items-center rounded-lg px-2 py-1 outline-none"
                        >
                            Datum
                        </span>
                    </th>
                    <th align="right">
                        <span
                            class="flex items-center rounded-lg px-2 py-1 outline-none"
                        >
                            Bedrag
                        </span>
                    </th>
                    <th align="right" class="w-12"></th>
                </template>

                <tr v-for="payment in payments" :key="payment.id" class="h-16">
                    <td class="pl-2">{{ payment.payment_date }}</td>
                    <td class="pl-2">{{ formatMoney(payment.amount) }}</td>

                    <td align="right" class="w-12">
                        <Dropdown>
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
                                    :href="route('payment.show', payment)"
                                >
                                    <div class="flex items-center">
                                        <EyeIcon class="mr-2 h-5 w-5" />
                                        Bekijken
                                    </div>
                                </DropdownLink>
                                <DropdownLink
                                    method="DELETE"
                                    :href="route('payment.destroy', payment)"
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
                    </td>
                </tr>
            </BaseTable>

            <div
                v-else
                class="cursor-default select-none text-sm text-gray-700"
            >
                <span class="mt-2">Er zijn nog geen betalingen gedaan. </span>
                <button
                    class="rounded text-indigo-600 underline transition duration-100 hover:text-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    @click="openPaymentModal"
                >
                    Voeg een betaling toe.
                </button>
            </div>
        </template>
    </ConfirmationSlideOver>

    <DialogModal :show="showPaymentForm" @close="showPaymentForm = false">
        <template #title> Betaling toevoegen </template>

        <template #content>
            <PaymentForm ref="paymentForm" />
        </template>

        <template #footer>
            <SecondaryButton @click="showPaymentForm = false">
                Annuleren
            </SecondaryButton>

            <PrimaryButton class="ml-3" @click="createPayment">
                Toevoegen
            </PrimaryButton>
        </template>
    </DialogModal>
</template>
