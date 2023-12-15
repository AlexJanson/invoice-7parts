<script setup></script>

<template>
    <div class="col-span-1 my-8 space-y-2 border-t-2 border-gray-100 py-8">
        <div class="font-medium">
            <slot name="heading" />
        </div>

        <div class="text-5xl font-medium">
            <slot />
        </div>
    </div>
</template>
