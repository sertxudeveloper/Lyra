<template>
    <div class="mt-5 md:mt-0 md:col-span-3 shadow rounded-md">
        <div class="px-4 py-5 bg-white space-y-6 sm:p-6 rounded-t-md">
            <slot></slot>
        </div>

        <div class="bg-gray-50 flex items-center justify-end px-4 py-3 rounded-b-md space-x-2">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: "Panel",
}
</script>
