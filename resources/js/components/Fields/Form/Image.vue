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
      <input class="appearance-none bg-gray-50 block border border-gray-300 cursor-pointer focus:outline-none rounded-lg text-gray-900 text-sm w-full"
             :class="[ errors?.length ? errorClass : defaultClass ]"
             :multiple="field.multiple" :accept="field.accept"
             type="file" :name="field.key" @change="onChange">
    </div>
    <div class="mt-1 px-1 text-red-500 text-sm" v-for="error in errors">{{ error }}</div>
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
