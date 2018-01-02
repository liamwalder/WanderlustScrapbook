<template>
    <div>
        <h3 class="location-name">
            <div v-show="editingLocationName == null">
                {{ location.name }}
                <a class="instruction" v-on:click="editName(location)" v-show="editMode">Change name</a>
            </div>
            <div v-show="editMode && editingLocationName !== null" class="edit-location-name">
                <input type="text" class="form-control" v-model="name" />
                <a class="instruction" v-on:click="saveName()">Save name</a>
            </div>
        </h3>
    </div>
</template>

<script>

    import { EventBus } from '../../event-bus';

    export default {

        props: ['location'],

        data () {
            return {
                name: null,
                editingLocationName: null
            }
        },

        computed: {
            editMode() {
                return this.$store.getters.editMode;
            }
        },

        methods: {
            editName(location) {
                this.name = location.name;
                this.editingLocationName = location;
            },

            saveName() {
                let self = this;
                axios.put('/api/location/' + this.location.id, { name: this.name })
                    .then(function (response) {
                        EventBus.$emit('refresh-trip');
                        self.editingLocationName = null;
                    })
                    .catch(function (error) {});
            }
        }

    }
</script>