<template>
    <div class="lg:px-8 max-w-screen-xl mx-auto py-6 sm:px-6">
        <div class="flex flex-col" v-if="resource?.data">
            <!-- Toolbar -->
            <div class="md:grid md:grid-cols-4 md:gap-6">
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
            <form ref="form" @submit.prevent="submit">
                <div class="md:grid md:grid-cols-4 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-xl font-medium leading-6 text-gray-900">
                                Create {{ resource?.labels?.singular }}
                            </h3>
                            <p class="mt-2 text-sm text-gray-600">This is the basic information of the resource.</p>
                        </div>
                    </div>

                    <Panel>
                        <div v-for="field in resource.data.fields" class="gap-6 grid grid-cols-3">
                            <Component :is="`form-${field.component}`" :field="field"/>
                        </div>

                        <template v-slot:footer>
                            <button class="btn-secondary" type="button" @click.prevent="cancel">Cancel</button>

                            <div class="btn-group-primary">
                                <button v-if="saveMode === 'create'" @click="changeMode = false"
                                        name="create" type="submit">
                                    <span>Create {{ resource?.labels?.singular.toLowerCase() }}</span>
                                </button>

                                <button v-if="saveMode === 'create-and-edit'" @click="changeMode = false"
                                        name="create-and-edit" type="submit">
                                    <span>Create & edit</span>
                                </button>

                                <div v-click-away="() => this.changeMode = false">
                                    <button type="button" @click.prevent="changeMode = !changeMode">
                                        <Icon name="chevron-down" class="w-3"/>
                                    </button>

                                    <Transition
                                        enter-from-class="transform opacity-0 scale-95"
                                        enter-to-class="transform opacity-100 scale-100"
                                        enter-active-class="transition ease-out duration-100"
                                        leave-from-class="transform opacity-100 scale-100"
                                        leave-to-class="transform opacity-0 scale-95"
                                        leave-active-class="transition ease-in duration-75">

<!--                                        <div class="dropdown-menu" v-if="changeMode">
                                            <div class="py-1 flex flex-col">
                                                &lt;!&ndash; Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" &ndash;&gt;
                                                <button @click.prevent="saveMode = 'create'" class="dropdown-item">
                                                    <span class="text-blue-700 w-3.5">
                                                        <Icon name="check" class="w-3.5"
                                                              v-show="saveMode === 'create'"/>
                                                    </span>

                                                    <span class="lowercase first-letter:uppercase -mt-0.5">
                                                        Create {{ resource?.labels?.singular }}
                                                    </span>
                                                </button>

                                                <button class="dropdown-item"
                                                        @click.prevent="saveMode = 'create-and-edit'">
                                                    <span class="text-blue-700 w-3.5">
                                                      <Icon name="check" class="w-3.5"
                                                            v-show="saveMode === 'create-and-edit'"/>
                                                    </span>

                                                    <span class="lowercase first-letter:uppercase">Create & edit</span>
                                                </button>
                                            </div>
                                        </div>-->
                                        <DropdownMenu>
                                            <button @click.prevent="saveMode = 'create'"
                                                    class="flex items-center space-x-3 px-4 py-2 text-gray-700 text-sm w-full">
                                                    <span class="text-blue-700 w-3.5">
                                                        <Icon name="check" class="w-3.5"
                                                              v-show="saveMode === 'create'"/>
                                                    </span>

                                                <span class="lowercase first-letter:uppercase -mt-0.5">
                                                        Create {{ resource?.labels?.singular }}
                                                    </span>
                                            </button>

                                            <button class="flex items-center space-x-3 px-4 py-2 text-gray-700 text-sm w-full"
                                                    @click.prevent="saveMode = 'create-and-edit'">
                                                    <span class="text-blue-700 w-3.5">
                                                      <Icon name="check" class="w-3.5"
                                                            v-show="saveMode === 'create-and-edit'"/>
                                                    </span>

                                                <span class="lowercase first-letter:uppercase">Create & edit</span>
                                            </button>
                                        </DropdownMenu>
                                    </Transition>
                                </div>
                            </div>
                        </template>
                    </Panel>
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
                .then(response => {
                    this.resource = response.data
                })
        },
        cancel() {
            this.$router.push({name: 'resource-index', params: {resourceName: this.$route.params.resourceName}})
        },
        submit() {
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

                    if (isCreateAndEdit) {
                        this.$router.replace({
                            name: 'resource-edit',
                            params: {resourceName: this.$route.params.resourceName, resourceId: response.data.data.key}
                        })
                    } else {
                        this.$router.back()
                    }
                })
        }
    }
}
</script>
