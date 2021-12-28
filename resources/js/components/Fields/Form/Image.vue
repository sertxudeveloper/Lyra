<template>
  <div class="col-span-3 xl:col-span-2">
    <label class="block text-sm font-medium text-gray-600 mb-1">{{ field.name }}</label>
    <div class="gap-4 grid mb-2 items-center"
         :class="[ field.multiple ? 'grid-cols-3' : 'grid-cols-1' ]">
      <div v-for="image in preview">
        <img :src="image" class="border border-gray-300">
      </div>
    </div>
    <div class="flex rounded-md shadow-sm">
      <input class="block border flex-1 focus:border-blue-500 focus:ring-blue-500 outline-none pl-3 pr-2 py-2 rounded-md sm:text-sm w-full text-gray-700"
             :class="[ errors?.length ? errorClass : defaultClass ]"
             :multiple="field.multiple"
             type="file" :name="field.key" @change="onChange">
    </div>
    <div class="mt-1 px-1 text-red-500 text-xs" v-for="error in errors">{{ error }}</div>
  </div>
</template>

<script>
export default {
  name: "Image",
  props: ['field', 'errors'],
  data() {
    return {
      defaultClass: 'border-gray-300',
      errorClass: 'border-red-400',

      // Images preview
      preview: [],
    }
  },
  mounted() {
    this.preview = this.field.files
  },
  methods: {
    onChange(e) {
      for (const file of e.target.files) {
        if (!this.field.multiple) {
          this.field.value = [];
          this.preview = [];
        }

        this.field.value.push(file)
        this.preview.push(URL.createObjectURL(file))
      }
    },
  }
}
</script>
