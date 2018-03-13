<template>
    <div v-if="trip" id="trip-name">
        <div v-show="!editMode">
            {{ trip.name }}
        </div>
        <div v-show="editMode" class="edit-trip-name">
            <input type="text" class="form-control" v-model="name" v-on:blur="saveName()" />
        </div>
    </div>
</template>

<script>
    import { EventBus } from '../../event-bus';

    export default {

        props: ['trip'],

        data () {
            return {
                name: this.trip.name
            }
        },

        computed: {
            editMode() {
                return this.$store.getters.editMode;
            }
        },


        methods: {

            saveName() {
                axios.put('/api/trip/' + this.trip.id, { name: this.name })
                    .then(function (response) {
                        EventBus.$emit('refresh-trip');
                    })
                    .catch(function (error) {});
            }

        }
    }
</script>