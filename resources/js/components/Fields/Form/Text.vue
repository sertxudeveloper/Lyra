<template>
    <div class="col-span-3 xl:col-span-2">
        <label class="block text-sm font-medium text-gray-600 mb-1">{{ field.name }}</label>
        <input
            class="form-input"
            :class="[ errors?.length ? errorClass : defaultClass ]"
            type="text" :name="field.key" v-model="field.value" :placeholder="field.name">

        <div class="mt-1 px-1 text-red-500 text-xs" v-for="error in errors">{{ error }}</div>
    </div>
</template>

<script>
export default {
    name: 'Text',
    props: ['field', 'errors'],
    data() {
        return {
            defaultClass: 'border-gray-300',
            errorClass: 'border-red-400',
        }
    },
}
</script>
