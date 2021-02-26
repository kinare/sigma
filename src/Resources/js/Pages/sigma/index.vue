<template>
    <v-app id="inspire">
<!--        App Bar -->
        <v-app-bar app color="white" flat>
            <v-container class="py-0 fill-height">
                <v-avatar class="mr-10" color="grey darken-1" size="32"></v-avatar>

                <v-toolbar-title>Sigma</v-toolbar-title>

                <v-spacer />

                <v-btn icon @click="refresh">
                    <v-icon>mdi-refresh</v-icon>
                </v-btn>
            </v-container>
        </v-app-bar>

<!-- main        -->
        <v-main class="grey lighten-3">
            <v-container fluid >
                <v-row>
                    <v-col cols="8">
                        <v-card class="mb-3 mx-auto" v-for="(provider, i) in providers" :key="i">
                            <v-card-title>
                                {{  provider.Provider || "No Providers set" }}
                                <v-spacer></v-spacer>

                                <v-chip small label>
                                    BaseUrl: {{ provider.baseConnectionPath }}
                                </v-chip>

                            </v-card-title>
                            <v-data-table @click:row="selectWrapper" class="log-table" :headers="headers" :items="provider.wrappers" dense></v-data-table>
                        </v-card>
                    </v-col>

                    <v-col cols="4">
                        <v-card class="mx-auto">
                            <v-toolbar dense color="bg-black accent-4" dark>
                                <v-toolbar-title>{{ wrapper.entityID  || 'Select Wrapper'}}</v-toolbar-title>
                                <v-spacer />
                                {{ '/'+ (wrapper.downStreamID || '') }}
                            </v-toolbar>

                            <v-subheader>
                                Actions
                                <v-chip v-if="wrapper.Get" class="ma-2" color="cyan  lighten-3" label x-small>
                                    Get
                                </v-chip>
                                <v-chip v-if="wrapper.Insert" class="ma-2" color="green lighten-3" label x-small>
                                    Insert
                                </v-chip>
                                <v-chip v-if="wrapper.Edit"  class="ma-2" color="orange  lighten-3" label x-small>
                                    Edit
                                </v-chip>
                                <v-chip v-if="wrapper.Delete"  class="ma-2" color="red  lighten-3" label x-small>
                                    Delete
                                </v-chip>
                            </v-subheader>

                            <v-data-table hide-default-footer :items-per-page="50" class="mt-5 log-table" :headers="fieldHeaders" :items="wrapper.fields" dense>

                            </v-data-table>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
export default {
name: "index",
    data: () => ({
        headers: [
            { text: 'Entity ID', align: 'start', value: 'entityID' },
            { text: 'UpStream ID', value: 'upStreamID' },
            { text: 'DownStream ID', value: 'downStreamID' },
            { text: 'Description', value: 'description' },
            { text: 'Disabled', value: 'disabled' },
        ],
        fieldHeaders: [
            { text: 'UpStream Key', value: 'UpStreamFieldID' },
            { text: 'DownStream Key', value: 'DownStreamFieldID' },
            { text: 'Mandatory', value: 'MandatoryValue' },
            { text: 'Key', value: 'Key' },
        ],
        providers: [],
        wrapper: []
    }),
    methods: {
        refresh: function (){
            this.$inertia.post(this.route('refresh'));
        },

        selectWrapper: function (wrapper){
            this.wrapper = wrapper;
        }
    },

    created() {
        this.providers = this.$page.providers ? this.$page.providers : [];
    }
}
</script>

<style scoped>

</style>
