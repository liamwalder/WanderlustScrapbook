<template>
    <div v-if="trip">
        <div v-show="editingTripName == null">
            {{ trip.name }}
            <a class="notice instruction"  v-on:click="editName(trip)" v-show="editMode">Change</a>
        </div>
        <div v-show="editMode && editingTripName !== null" class="edit-trip-name">
            <input type="text" class="form-control" v-model="name" />
            <a class="notice instruction" v-on:click="saveName()">Save</a>
        </div>
    </div>
</template>

<script>
    import { EventBus } from '../../event-bus';

    export default {

        props: ['trip'],

        data () {
            return {
                name: null,
                editingTripName: null
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
                this.editingTripName = location;
            },

            saveName() {
                let self = this;
                axios.put('/api/trip/' + this.trip.id, { name: this.name })
                    .then(function (response) {
                        EventBus.$emit('refresh-trip');
                        self.editingTripName = null;
                    })
                    .catch(function (error) {});
            }

        }
    }
</script>