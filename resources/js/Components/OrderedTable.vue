<script setup>
import { computed, reactive, ref, watch } from 'vue'

import {
    ArchiveBoxIcon,
    EllipsisVerticalIcon,
    PencilSquareIcon,
    TrashIcon,
    ChevronUpDownIcon,
    EyeIcon,
} from '@heroicons/vue/24/solid'
import {
    ArrowLongUpIcon,
    ArrowLongDownIcon,
    ChevronDownIcon,
    ChevronUpIcon,
    PlusIcon,
    XMarkIcon,
} from '@heroicons/vue/20/solid'
import Checkbox from './Checkbox.vue'
import Dropdown from './Dropdown.vue'
import DropdownLink from './DropdownLink.vue'
import BaseTable from './BaseTable.vue'
import SearchInput from './SearchInput.vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
    data: Object,
    shouldDelete: {
        type: Boolean,
        default: false,
    },
})

const checked = ref([...Array(props.data.rows.value.length).fill(false)])
const anyChecked = computed(
    () =>
        checked.value.some((checkbox) => checkbox === true) &&
        checked.value.length > 0 &&
        !allChecked.value,
)
const allChecked = computed({
    get() {
        return (
            checked.value.every((checkbox) => checkbox === true) &&
            checked.value.length > 0
        )
    },
    // Empty setter to remove the warning for mutating a
    // computed value since we don't need to worry about
    // that for this one.
    set(_) {
        //
    },
})

function checkAll(event) {
    for (let i = 0; i < checked.value.length; i++)
        checked.value[i] = event.target.checked
}

const params = reactive({
    column: route().params.column,
    direction: route().params.direction,
    search: route().params.search,
    archived: route().params.archived,
})

function updateSort(column, direction) {
    params.column = column
    params.direction = direction
}

watch(
    params,
    () => {
        let newParams = params

        Object.keys(newParams).forEach((key) => {
            if (newParams[key] == '' || newParams[key] == null) {
                delete newParams[key]
            }
        })

        const url = props.data.route.parameter
            ? `${route(route().current())}/${props.data.route.parameter}`
            : route(route().current())
        router.get(url, newParams, {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        })
    },
    { deep: true },
)

const showFilters = ref(false)
</script>

<template>
    <div class="mb-6 grid h-10 grid-cols-3 gap-4">
        <SearchInput v-model="params.search" />

        <div class="col-span-2 flex justify-end">
            <button
                @click="showFilters = !showFilters"
                class="mt-1 flex w-fit items-center rounded-md px-3 outline-none ring-offset-2 transition duration-150 ease-in-out focus:ring-2 focus:ring-indigo-500"
                :class="[
                    showFilters ? 'bg-indigo-500 text-white' : 'bg-gray-100',
                ]"
            >
                <span class="font-medium">Filters</span>
                <ChevronDownIcon
                    class="ml-2 h-5 w-5 -rotate-90 transition-transform duration-150 ease-in-out"
                    :class="{ 'rotate-0': showFilters }"
                />
            </button>
        </div>
    </div>

    <div class="mb-8 flex flex-wrap gap-2" v-show="showFilters">
        <div
            v-if="params.archived"
            class="mt-1 flex h-10 w-fit items-center rounded-md bg-gray-100 px-3 outline-none"
        >
            <span class="mr-2 whitespace-nowrap font-medium">Gearchiveerd</span>
            <button
                @click="params.archived = false"
                class="rounded-md outline-none focus:ring-2 focus:ring-indigo-500"
            >
                <XMarkIcon class="h-5 w-5" />
            </button>
        </div>
        <Dropdown align="left">
            <template #trigger="{ open }">
                <button
                    class="mt-1 flex h-10 w-fit items-center rounded-md px-3 outline-none ring-offset-2 focus:ring-2 focus:ring-indigo-500"
                    :class="[open ? 'bg-indigo-500 text-white' : 'bg-gray-100']"
                >
                    <PlusIcon class="h-5 w-5" />
                </button>
            </template>

            <template #content>
                <DropdownLink
                    @click="params.archived = true"
                    v-if="!shouldDelete"
                    as="button"
                >
                    <div class="flex items-center">
                        <ArchiveBoxIcon class="mr-2 h-5 w-5" />
                        Gearchiveerd
                    </div>
                </DropdownLink>
            </template>
        </Dropdown>
    </div>

    <BaseTable>
        <template #head>
            <th align="middle" class="w-12 pb-1">
                <Checkbox
                    v-model:checked="allChecked"
                    @change="checkAll($event)"
                    :indeterminate="anyChecked"
                />
            </th>

            <th
                v-for="th in data.cols"
                :key="th.id"
                :id="th.id"
                :align="th.align"
                :class="th.class"
            >
                <Dropdown :align="th.align ?? 'left'">
                    <template #trigger="{ open }">
                        <button
                            class="flex items-center rounded-lg px-2 py-1 outline-none transition duration-150 ease-in-out hover:bg-gray-100 focus:ring-2 focus:ring-indigo-500"
                            :class="{
                                'bg-gray-100': open || params.column == th.id,
                            }"
                        >
                            {{ th.name }}
                            <ChevronUpDownIcon
                                v-if="params.column != th.id"
                                class="ml-1 h-5 w-5 text-gray-400"
                            />
                            <ChevronUpDownIcon
                                v-else-if="
                                    params.column === th.id &&
                                    th.sortComponent != null
                                "
                                class="ml-1 h-5 w-5 text-indigo-500"
                            />
                            <ChevronDownIcon
                                v-else-if="
                                    params.column === th.id &&
                                    params.direction === 'DESC' &&
                                    th.sortComponent == null
                                "
                                class="ml-1 h-5 w-5 text-indigo-500"
                            />
                            <ChevronUpIcon
                                v-else-if="
                                    params.column === th.id &&
                                    params.direction === 'ASC' &&
                                    th.sortComponent == null
                                "
                                class="ml-1 h-5 w-5 text-indigo-500"
                            />
                        </button>
                    </template>
                    <template #content v-if="!th.sortComponent">
                        <DropdownLink
                            as="button"
                            @click="updateSort(th.id, 'ASC')"
                        >
                            <div class="flex items-center">
                                <ArrowLongUpIcon class="mr-2 h-5 w-5" />
                                Oplopend
                            </div>
                        </DropdownLink>
                        <DropdownLink
                            as="button"
                            @click="updateSort(th.id, 'DESC')"
                        >
                            <div class="flex items-center">
                                <ArrowLongDownIcon class="mr-2 h-5 w-5" />
                                Aflopend
                            </div>
                        </DropdownLink>
                    </template>
                    <template #content v-else>
                        <component
                            :is="th.sortComponent"
                            :update-sort="updateSort"
                            :id="th.id"
                        />
                    </template>
                </Dropdown>
            </th>
            <th align="right" class="w-12"></th>
        </template>

        <tr v-for="(item, idx) in data.rows.value" :key="idx" class="h-16">
            <td align="middle" class="w-12">
                <Checkbox v-model:checked="checked[idx]" />
            </td>

            <slot :current="item" />

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
                            v-if="data.route"
                            :href="
                                route(
                                    data.route.name,
                                    item[data.route.paramKey],
                                )
                            "
                        >
                            <div class="flex items-center">
                                <EyeIcon class="mr-2 h-5 w-5" />
                                Bekijken
                            </div>
                        </DropdownLink>
                        <DropdownLink
                            :href="
                                route(
                                    `${data.route.name.split('.')[0]}.edit`,
                                    item[data.route.paramKey],
                                )
                            "
                        >
                            <div class="flex items-center">
                                <PencilSquareIcon class="mr-2 h-5 w-5" />
                                Edit
                            </div>
                        </DropdownLink>
                        <DropdownLink
                            method="DELETE"
                            :href="
                                route(
                                    `${data.route.name.split('.')[0]}.destroy`,
                                    item[data.route.paramKey],
                                )
                            "
                            bg-class="hover:bg-red-600 focus:bg-red-600"
                        >
                            <div class="flex items-center">
                                <template v-if="shouldDelete">
                                    <TrashIcon
                                        class="mr-2 h-5 w-5 transition duration-150 ease-in-out group-hover:text-white group-focus-visible:text-white"
                                    />
                                    <span
                                        class="transition duration-150 ease-in-out group-hover:text-white group-focus-visible:text-white"
                                        >Delete</span
                                    >
                                </template>

                                <template v-else>
                                    <ArchiveBoxIcon
                                        class="mr-2 h-5 w-5 transition duration-150 ease-in-out group-hover:text-white group-focus-visible:text-white"
                                    />
                                    <span
                                        class="transition duration-150 ease-in-out group-hover:text-white group-focus-visible:text-white"
                                        >Archiveren</span
                                    >
                                </template>
                            </div>
                        </DropdownLink>
                    </template>
                </Dropdown>
            </td>
        </tr>
    </BaseTable>

    <div
        class="flex w-full justify-between rounded-b-md bg-gray-50 p-5 text-sm"
    >
        <p>
            Toont {{ data.pagination.value.from ?? 0 }} tot
            {{ data.pagination.value.to ?? 0 }} van de
            {{ data.pagination.value.total }} resultaten.
        </p>
        <div class="flex space-x-6">
            <Link
                :href="data.pagination.value.prev_page_url ?? ''"
                :class="[
                    !data.pagination.value.prev_page_url
                        ? 'cursor-not-allowed text-gray-400'
                        : 'cursor-pointer text-indigo-600',
                ]"
                class="underline decoration-gray-400"
                >← Previous</Link
            >
            <Link
                :href="data.pagination.value.next_page_url ?? ''"
                :class="[
                    !data.pagination.value.next_page_url
                        ? 'cursor-not-allowed text-gray-400'
                        : 'cursor-pointer text-indigo-600',
                ]"
                class="underline decoration-gray-400"
                >Next →</Link
            >
        </div>
    </div>
</template>
