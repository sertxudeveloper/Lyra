<template>
    <div class="col-span-3 md:col-span-2 xl:col-span-1">
        <label class="block text-sm font-medium text-gray-600">{{ field.name }}</label>
        <div class="flex mt-1 rounded-md shadow-sm relative">
            <input
                class="block border flex-1 focus:border-blue-600 focus:ring-blue-600 outline-none pl-3 pr-10 py-2 rounded-md sm:text-sm w-full text-gray-700"
                :class="[ errors?.length ? errorClass : defaultClass ]"
                :type="[mask ? 'password' : 'text']" :name="field.key" v-model.lazy="value"
                :placeholder="field.placeholder">

            <button v-if="mask" @click="mask = false"
                    class="absolute h-full inline-flex items-center px-3 right-0 text-gray-700 top-0">
                <Icon name="eye-open" class="w-4" />
            </button>

            <button v-else @click="mask = true"
                    class="absolute h-full inline-flex items-center px-3 right-0 text-gray-700 top-0">
                <Icon name="eye-closed" class="w-4" />
            </button>
        </div>

        <div class="mt-1 px-1 text-red-500 text-xs" v-for="error in errors">{{ error }}</div>
    </div>
</template>

<script>
export default {
    name: 'Password',
    props: ['field', 'errors'],
    data() {
        return {
            defaultClass: 'border-gray-300',
            errorClass: 'border-red-400',
            value: '',
            mask: true,
        }
    },
}
</script>
