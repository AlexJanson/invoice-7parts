<template>
    <table class="w-full">
        <thead class="h-10 border-b border-gray-300">
            <tr class="text-left text-sm text-gray-500">
                <slot name="head" />
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 whitespace-nowrap">
            <slot />
        </tbody>
        <tfoot class="whitespace-nowrap border-t border-gray-200">
            <slot name="foot" />
        </tfoot>
    </table>
</template>

<style>
th {
    font-weight: normal;
}

:slotted(td) {
    padding-top: 0.35rem;
}
</style>
