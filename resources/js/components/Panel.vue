<template>
    <div class="md:grid md:grid-cols-4 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-xl font-medium leading-6 text-gray-900">{{ title }}</h3>
                <p class="mt-2 text-sm text-gray-600">{{ description }}</p>
            </div>
        </div>

<!--        <div class="mt-5 md:mt-0 md:col-span-3 shadow rounded-md">
            <div class="px-4 py-5 bg-white space-y-6 sm:p-6 rounded-t-md">
                <div v-for="field in fields" class="gap-6 grid grid-cols-3">
                    <Component :is="`form-${field.component}`" :field="field"/>
                </div>
            </div>

            <div class="bg-gray-50 flex items-center justify-end px-4 py-3 rounded-b-md space-x-2">
                <slot name="footer"></slot>
            </div>
        </div>-->

        <div class="mt-5 md:mt-0 md:col-span-3 shadow rounded-md px-4 py-5 bg-white space-y-6">
            <div v-for="field in fields" class="gap-6 grid grid-cols-3">
                <Component :is="`form-${field.component}`" :field="field"/>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Panel",
    props: {
        title: {
            type: String,
            required: true,
        },
        description: {
            type: String,
            required: true,
        },
        fields: {
            type: Array,
            required: true,
        },
    },
}
</script>
