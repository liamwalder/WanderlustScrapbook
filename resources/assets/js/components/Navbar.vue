<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <!--<a class="navbar-brand" href="#">-->
            <!--<img src="/images/logo.png" width="100" alt="">-->
        <!--</a>-->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="location-add">
            <div class="location">
                <label :class="{ 'text-danger': errors.location }">Location*</label>
                <GmapAutocomplete
                    @place_changed="setPlace"
                    placeholder="Ho Chi Minh City, Ho Chi Minh, Vietnam"
                >
                </GmapAutocomplete>
            </div>
            <div class="datepicker">
                <label>From</label>
                <date-picker
                    v-model="from"
                    lang="en"
                    placeholder="12/07/2017"
                    format="dd/MM/yyyy"
                    :not-after="notAfterDate == null ? '' : notAfterDate"
                ></date-picker>
            </div>
            <div class="datepicker">
                <label>To</label>
                <date-picker
                        v-model="to"
                        lang="en"
                        format="dd/MM/yyyy"
                        placeholder="19/07/2017"
                        :not-before="notBeforeDate"
                ></date-picker>
            </div>
            <div class="add-to-trip">
                <span @click="storeLocation" class="add-to-trip">Add To Trip</span>
            </div>
        </div>
    </nav>
</template>

<script>
    import { EventBus } from '../event-bus';
    import DatePicker from 'vue2-datepicker';

    export default {

        components: { DatePicker },

        props: [],

        data () {
            return {
                to: null,
                from: null,
                errors: [],
                notAfterDate: null,
                notBeforeDate: null
            }
        },

        created() {
            let self = this;
        },

        watch: {
            from: function(from) {
                let fromDate = new Date(from);
                this.notBeforeDate = fromDate.getFullYear() + '-' + (fromDate.getMonth() + 1) + '-' + fromDate.getDate();
            },
            to: function(to) {
                let toDate = new Date(to);
                this.notAfterDate = toDate.getFullYear() + '-' + (toDate.getMonth() + 1) + '-' + toDate.getDate();
            }
        },

        methods: {

            reset() {
                this.to = null;
                this.from = null;
                this.place = null;
                this.place = null;
                this.errors = null;
                this.notBeforeDate = null;
            },

            storeLocation() {
                let self = this;
                let postData = {
                    to: this.to,
                    from: this.from
                };

                if (this.place) {
                    postData['location'] = {
                        lat: this.place.geometry.location.lat(),
                        lng: this.place.geometry.location.lng(),
                        name: this.place.address_components[0].long_name
                    }
                }

                axios.post('/api/location', postData)
                    .then(function (response) {
                        self.reset();
                        EventBus.$emit('refresh-trip');
                    })
                    .catch(function (error) {
                        self.errors = error.response.data;
                    });

            },

            setPlace(place) {
                this.place = place
            }

        }

    }
</script>