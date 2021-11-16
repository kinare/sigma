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
            <v-container>
                <v-row>
                    <v-col v-for="(provider, i) in providerList" :key="i">
                        <provider :provider="provider" />
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>

import Provider from "../../components/provider";
export default {
name: "index",
    components: { Provider },
    computed:{
        providerList(){
            return this.$page.providers
        }
    },
    methods: {
        refresh: function (){
            this.$inertia.post(this.route('refresh'));
        }
    },

}
</script>

<style scoped>

</style>
