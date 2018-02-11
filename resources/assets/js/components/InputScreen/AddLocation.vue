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
            <div class="form-group" id="autocomplete">
                <label :class="{ 'text-danger': errors.location }">Location*</label>
                <GmapAutocomplete
                    ref="autocomplete"
                    :class="{ 'is-invalid': errors.location }"
                    class="form-control"
                    @place_changed="setPlace"
                    placeholder="Search for a location..."
                ></GmapAutocomplete>
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
                <button v-on:click="addLocation()" class="btn button-primary">Add Location</button>
            </div>
        </div>
    </div>
</template>

<script>
    import { EventBus } from '../../event-bus';
    import DatePicker from 'vue2-datepicker';

    export default {

        props: ['tripId', 'locations'],

        components: { DatePicker },

        data () {
            return {
                to: null,
                from: null,
                name: null,
                errors: [],
                place: null,
                country: null,
                notAfterDate: null,
                notBeforeDate: null
            }
        },

        created() {
            let self = this;
        },

        watch: {

            locations: function(locations) {
                if (locations !== null && locations.length !== 0) {
                    let lastLocation = locations.slice(-1)[0];
                    let lastLocationToDate = new Date(lastLocation.to);
                    this.from = lastLocationToDate.getFullYear() + '-' + (lastLocationToDate.getMonth() + 1) + '-' + lastLocationToDate.getDate();
                }
            },

            from: function (from) {
                let fromDate = new Date(from);
                this.notBeforeDate = fromDate.getFullYear() + '-' + (fromDate.getMonth() + 1) + '-' + fromDate.getDate();

                let toDate = new Date(from);
                let toDateAddOneDay = new Date(toDate.setDate(toDate.getDate() + 1));

                this.to = toDateAddOneDay.getFullYear() + '-' + (toDateAddOneDay.getMonth() + 1) + '-' + toDateAddOneDay.getDate();
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
                this.name = null;
                this.errors = [];
                this.place = null;
                this.notBeforeDate = null;
                $('#autocomplete input').val('');
            },

            addLocation() {
                let self = this;
                let toDate = new Date(this.to);
                let fromDate = new Date(this.from);

                let postData = {
                    name: this.name,
                    to: toDate.getFullYear() + '-' + (toDate.getMonth() + 1) + '-' + toDate.getDate(),
                    from: fromDate.getFullYear() + '-' + (fromDate.getMonth() + 1) + '-' + fromDate.getDate()
                };

                if (this.place) {
                    postData['location'] = {
                        lat: this.place.geometry.location.lat(),
                        lng: this.place.geometry.location.lng()
                    };
                    postData['country'] = this.country;
                }

                axios.post('/api/trip/' + self.tripId + '/location', postData)
                    .then(function (response) {
                        self.reset();
                        EventBus.$emit('refresh-trip');
                        EventBus.$emit('map-set-center', postData['location']);
                    })
                    .catch(function (error) {
                        self.errors = error.response.data;
                    });

            },

            /**
             *
             * @param place
             */
            setPlace(place) {
                let self = this;

                this.place = place;
                this.name = place.formatted_address;

                place.address_components.forEach(function(addressComponent) {
                    addressComponent.types.forEach(function(placeType) {
                        if (placeType == 'country') {
                            self.country = addressComponent.long_name;
                        }
                    });

                });
            }

        }
    }
</script>
