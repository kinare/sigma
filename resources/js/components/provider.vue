<template>
    <v-data-table
        :headers="wrapperHeaders"
        :items="wrappers"
        :single-expand="true"
        :expanded.sync="expanded"
        show-expand
        class="elevation-1 mb-5"
    >
        <template v-slot:top>
            <v-toolbar flat>
                <v-toolbar-title>
                    {{  provider.Provider }}
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-chip small label>
                    BaseUrl: {{ provider.baseConnectionPath }}
                </v-chip>
            </v-toolbar>
        </template>

        <template v-slot:expanded-item="{ headers, item }">
            <td :colspan="headers.length">
                <v-subheader>
                    Actions
                    <v-chip v-if="item.Get" class="ma-2" color="cyan  lighten-3" label x-small>
                        Get
                    </v-chip>
                    <v-chip v-if="item.Insert" class="ma-2" color="green lighten-3" label x-small>
                        Insert
                    </v-chip>
                    <v-chip v-if="item.Edit"  class="ma-2" color="orange  lighten-3" label x-small>
                        Edit
                    </v-chip>
                    <v-chip v-if="item.Delete"  class="ma-2" color="red  lighten-3" label x-small>
                        Delete
                    </v-chip>
                </v-subheader>
                <v-divider />

                <v-row>
                    <v-col class="d-flex" cols="12" md="5">
                        <json-viewer :value="fields(item)" style="width: 100%"/>
                    </v-col>
                    <v-col class="d-flex" cols="12" md="7">
                        <v-row>
                            <v-col cols="12">
                                <v-select
                                    dense
                                    :items="['GET', 'POST', 'PATCH', 'DELETE']"
                                    label="Method"
                                    outlined
                                ></v-select>
                                <v-chip small label>
                                    {{ provider.baseConnectionPath + item.downStreamID }}
                                </v-chip>

                            </v-col>
                            <v-col cols="12">
                                <v-jsoneditor :plus="false" :options="{ mode: 'code' }" height="300px"/>
                            </v-col>
                            <v-col cols="12">
                                <v-btn color="success" class="mr-4">Submit</v-btn>
                            </v-col>
                            <v-col cols="12">
                                <v-sheet color="grey" dark rounded>
                                    <v-subheader>Results</v-subheader>
                                </v-sheet>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </td>
        </template>
    </v-data-table>
</template>

<script>
import JsonViewer from "vue-json-viewer";
import VJsoneditor from 'v-jsoneditor'

import 'vue-json-viewer/style.css'
export default {
    name: "provider",
    components: { JsonViewer, VJsoneditor },
    props: ['provider'],
    data: function (){
        return{
            expanded: [],
            formData: {

            },
            singleExpand: false,
            wrapperHeaders: [
                { text: 'Entity ID', align: 'start', value: 'entityID' },
                { text: 'UpStream ID', value: 'upStreamID' },
                { text: 'DownStream ID', value: 'downStreamID' },
                { text: 'Description', value: 'description' },
                { text: 'Disabled', value: 'disabled' },
                { text: '', value: 'data-table-expand' }
            ],
        }
    },
    computed: {
        wrappers(){
            return this.provider ? this.provider.wrappers : { fields: [] }
        }
    },
    methods: {
        fields: function (wrapper){
            let json = {};
            let fields = wrapper.fields;

            for (let key in fields)
            {
                json[fields[key]['DownStreamFieldID']] = `${fields[key]['MandatoryValue'] ? 'required' : 'optional'}` + `${fields[key]['Key'] ? '(PK)': ''}`
            }
            return json
        }

    }
}
</script>

<style scoped>

</style>
