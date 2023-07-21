<template>
    <div class="lg:px-8 max-w-screen-xl mx-auto py-6 sm:px-6">
        <div class="flex flex-col" v-if="resource?.data">
            <!-- Toolbar -->
            <div class="md:grid md:grid-cols-4 md:gap-6">
                <!-- Back button -->
                <div class="flex justify-between mb-2 md:col-span-3 md:col-start-2">
                    <div class="flex">
                        <RouterLink
                            :to="{ name: 'resource-index', params: { resourceName: $route.params.resourceName } }"
                            class="bg-white h-9 hover:text-blue-600 p-2.5 rounded shadow text-gray-700 w-9">
                            <Icon name="arrow-left" class="w-4"/>
                        </RouterLink>
                    </div>
                </div>
            </div>

            <!-- Fields -->
            <form ref="form" @submit.prevent="submit" class="space-y-4">
                <div class="space-y-6">
                    <template v-for="panel in resource.data.panels">
                        <Panel
                            :title="panel.title"
                            :description="panel.description">

                            <div v-for="field in panel.fields" class="gap-6 grid grid-cols-3">
                                <Component
                                    :is="`form-${field.component}`"
                                    :field="field" />
                            </div>
                        </Panel>
                    </template>
                </div>

                <div class="flex items-center justify-end space-x-2">
                    <button class="btn-secondary" type="button" @click.prevent="cancel">Cancel</button>

                    <button @click="changeMode = false" name="create" class="btn-primary" type="submit">
                        <span>Create {{ resource?.labels?.singular.toLowerCase() }}</span>
                    </button>

                    <button @click="changeMode = false" name="create-and-edit" class="btn-primary" type="submit">
                        <span>Create & edit</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "Create",
    data() {
        return {
            errors: {},
            resource: {},
            changeMode: false,
            saveMode: 'create', // create, create-and-edit
        }
    },
    mounted() {
        this.getResource()
    },
    methods: {
        getResource() {
            this.$http.get(`/resources/${this.$route.params.resourceName}/create`)
                .then(response => this.resource = response.data)
        },
        cancel() {
            this.$router.push({name: 'resource-index', params: {resourceName: this.$route.params.resourceName}})
        },
        submit() {
            this.errors = {}

            let formData = new FormData(this.$refs.form);
            const isCreateAndEdit = document.activeElement.name === 'create-and-edit'

            this.$http.post(`/resources/${this.$route.params.resourceName}`, formData)
                .then(response => {
                    this.$notify({
                        type: 'success',
                        title: 'Resource created',
                        text: 'The resource has been created correctly.',
                        timeout: 4000
                    })

                    if (!isCreateAndEdit) {
                        return this.$router.back()
                    }

                    this.$router.replace({
                        name: 'resource-edit',
                        params: {resourceName: this.$route.params.resourceName, resourceId: response.data.data.key}
                    })
                })
                .catch(error => {
                    if (error.response.status === 409) {
                        this.$notify({
                            type: 'error',
                            title: 'Error',
                            text: 'A conflict has been detected, the resource has been edited by other session.',
                            timeout: 8000
                        })
                        return null
                    }

                    const data = error.response.data
                    this.errors = data.errors
                })
        }
    }
}
</script>
