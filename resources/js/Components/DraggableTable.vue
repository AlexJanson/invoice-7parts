<script setup>
import BaseTable from './BaseTable.vue'
import { Squares2X2Icon } from '@heroicons/vue/24/outline'
import {
    EllipsisVerticalIcon,
    PencilIcon,
    TrashIcon,
} from '@heroicons/vue/24/solid'
import Dropdown from './Dropdown.vue'
import DropdownLink from './DropdownLink.vue'
import { computed } from 'vue'

const props = defineProps({
    data: Object,
    editable: {
        type: Boolean,
        default: false,
    },
})
</script>

<template>
    <BaseTable>
        <template #head>
            <th align="middle" class="w-16 pb-1"></th>

            <th
                v-for="th in props.data.cols"
                :key="th.id"
                :id="th.id"
                :align="th.align"
                :class="th.class"
            >
                {{ th.name }}
            </th>

            <th align="right"></th>
        </template>

        <tr v-for="(item, idx) in props.data.rows" :key="idx" class="h-16">
            <td
                align="middle"
                class="w-12"
                :class="[props.editable ? 'visible' : 'invisible']"
            >
                <Squares2X2Icon class="h-6 w-6" />
            </td>

            <slot :current="item" />

            <td
                align="right"
                :class="[props.editable ? 'visible' : 'invisible']"
            >
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
                        <DropdownLink as="button">
                            <div class="flex items-center">
                                <PencilIcon class="mr-2 h-5 w-5" />
                                Edit
                            </div>
                        </DropdownLink>
                        <DropdownLink as="button" bg-class="hover:bg-red-600">
                            <div class="flex items-center">
                                <TrashIcon
                                    class="mr-2 h-5 w-5 transition duration-150 ease-in-out group-hover:text-white"
                                />
                                <span
                                    class="transition duration-150 ease-in-out group-hover:text-white"
                                    >Delete</span
                                >
                            </div>
                        </DropdownLink>
                    </template>
                </Dropdown>
            </td>
        </tr>
        <template #foot>
            <slot name="foot" />
        </template>
    </BaseTable>
</template>
