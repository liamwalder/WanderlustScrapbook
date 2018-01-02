<template>
    <div class="content-sidebar add-location">
        <div class="col-12">
            <div class="header">
                <h3>Add Location</h3>
                <a class="float-right" v-on:click="cancel()">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </a>
            </div>
            <p class="notice">You will be able to re-order this location in the list once added</p>
            <p class="notice">The location field is to ensure we find the correct location on the map. The name field is what you would like this locations name to be presented as.</p>
            <div class="form-group">
                <label :class="{ 'text-danger': errors.location }">Location*</label>
                <GmapAutocomplete
                    :class="{ 'is-invalid': errors.location }"
                    class="form-control"
                    @place_changed="setPlace"
                    placeholder="Search for a location..."
                >
                </GmapAutocomplete>
            </div>

            <div class="form-group">
                <label :class="{ 'text-danger': errors.name }">Name*</label>
                <input type="text" class="form-control" v-model="name" :class="{ 'is-invalid': errors.name }" />
            </div>

            <div class="form-group">
                <label>From</label>
                <date-picker
                        input-class="form-control"
                        v-model="from"
                        lang="en"
                        placeholder="12/07/2017"
                        format="dd/MM/yyyy"
                        :not-after="notAfterDate == null ? '' : notAfterDate"
                        width="0"
                ></date-picker>
            </div>

            <div class="form-group">
                <label>To</label>
                <date-picker
                        input-class="form-control"
                        v-model="to"
                        lang="en"
                        format="dd/MM/yyyy"
                        placeholder="19/07/2017"
                        :not-before="notBeforeDate"
                        :width="0"
                ></date-picker>
            </div>

            <div class="form-group save-entry">
                <button v-on:click="addLocation()">Add Location</button>
            </div>
        </div>
    </div>
</template>

<script>
    import { EventBus } from '../../event-bus';
    import DatePicker from 'vue2-datepicker';

    export default {

        props: ['tripId'],

        components: { DatePicker },

        data () {
            return {
                to: null,
                from: null,
                name: null,
                errors: [],
                notAfterDate: null,
                notBeforeDate: null
            }
        },

        created() {
            let self = this;
        },

        watch: {
            from: function (from) {
                let fromDate = new Date(from);
                this.notBeforeDate = fromDate.getFullYear() + '-' + (fromDate.getMonth() + 1) + '-' + fromDate.getDate();
            },
            to: function (to) {
                let toDate = new Date(to);
                this.notAfterDate = toDate.getFullYear() + '-' + (toDate.getMonth() + 1) + '-' + toDate.getDate();
            }
        },

        methods: {

            cancel () {
                this.reset();
                EventBus.$emit('input-screen-cancelled');
            },

            reset() {
                this.to = null;
                this.from = null;
                this.place = null;
                this.name = null;
                this.errors = null;
                this.notBeforeDate = null;
            },

            addLocation() {
                let self = this;
                let postData = {
                    to: this.to,
                    from: this.from,
                    name: this.name
                };

                if (this.place) {
                    postData['location'] = {
                        lat: this.place.geometry.location.lat(),
                        lng: this.place.geometry.location.lng()
                    }
                }

                axios.post('/api/trip/' + self.tripId + '/location', postData)
                        .then(function (response) {
                            self.reset();
                            EventBus.$emit('refresh-trip');
                        })
                        .catch(function (error) {
                            self.errors = error.response.data;
                        });

            },

            setPlace(place) {
                this.place = place;
                this.name = place.formatted_address;
            }

        }
    }
</script>
