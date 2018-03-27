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
                <label>From*</label>
                <datepicker
                    :limit="fromLimit"
                    class="form-control"
                    :date="{ time: from }"
                    :default-date="fromDefault"
                    @change="fromDateChanged"
                    :option="datepickerOptions"
                ></datepicker>
            </div>

            <div class="form-group">
                <label>To*</label>
                <datepicker
                    :limit="toLimit"
                    class="form-control"
                    :date="{ time: to }"
                    @change="toDateChanged"
                    :default-date="toDefault"
                    :option="datepickerOptions"
                ></datepicker>
            </div>

            <div class="form-group save-entry">
                <button v-on:click="addLocation()" class="btn button-primary">Add Location</button>
            </div>
        </div>
    </div>
</template>

<script>

    import myDatepicker from 'vue-datepicker';
    import { EventBus } from '../../event-bus';

    export default {

        props: ['tripId', 'locations'],

        components: {
            'datepicker': myDatepicker
        },

        data () {
            return {
                name: null,
                errors: [],
                place: null,
                country: null,

                to: moment().add(1, 'days').format('DD/MM/YYYY'),
                from: moment().format('DD/MM/YYYY'),

                toDefault: moment().add(1, 'days').format('YYYY-MM-DD'),
                fromDefault: moment().format('YYYY-MM-DD'),

                fromLimit: [{
                    type: 'fromto',
                    from: '1970-01-01',
                    to: this.toDefault
                }],

                toLimit: [{
                    type: 'fromto',
                    from: this.fromDefault,
                    to: '2099-12-30'
                }],

                datepickerOptions: {
                    type: 'day',
                    month: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    format: 'DD/MM/YYYY',
                    placeholder: 'Select your date'
                },
            }
        },

        created() {
            let self = this;
        },

        watch: {
            locations: function(locations) {
                if (locations !== null && locations.length !== 0) {
                    let lastLocation = locations.slice(-1)[0];
                    this.from = moment(lastLocation.to).format('DD/MM/YYYY');
                    this.fromDefault = moment(lastLocation.to).format('YYYY-MM-DD');

                    this.to = moment(lastLocation.to).add(1, 'days').format('DD/MM/YYYY');
                    this.toDefault = moment(lastLocation.to).add(1, 'days').format('YYYY-MM-DD');

                    this.fromLimit[0].to = this.toDefault;
                    this.toLimit[0].from = this.fromDefault;
                }
            }
        },

        methods: {

            /**
             * @param date
             */
            fromDateChanged(date) {
                this.from = moment(date, 'DD/MM/YYYY').format('DD/MM/YYYY');
                this.fromDefault = moment(date, 'DD/MM/YYYY').format('YYYY-MM-DD');
                this.toLimit[0].from = this.fromDefault;
            },

            /**
             *
             * @param date
             */
            toDateChanged(date) {
                this.to = moment(date, 'DD/MM/YYYY').format('DD/MM/YYYY');
                this.toDefault = moment(date, 'DD/MM/YYYY').format('YYYY-MM-DD');
                this.fromLimit[0].to = this.toDefault;
            },

            /**
             *
             */
            cancel () {
                this.reset();
                EventBus.$emit('input-screen-cancelled');
            },

            /**
             *
             */
            reset() {
                this.to = null;
                this.from = null;
                this.name = null;
                this.errors = [];
                this.place = null;
                this.notBeforeDate = null;
                $('#autocomplete input').val('');
            },

            /**
             * Add location
             */
            addLocation() {

                let loader = this.$loading.show();

                let self = this;

                let postData = {
                    name: this.name,
                    to: this.toDefault,
                    from: this.fromDefault
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
                        loader.hide();
                    })
                    .catch(function (error) {
                        self.errors = error.response.data;
                        loader.hide();
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
