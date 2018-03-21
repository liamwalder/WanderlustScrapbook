<template>
    <div class="location-dates">
        <p class="date" v-if="location.from && location.to"  v-show="!editMode">
            {{ location.from | moment("Do MMMM YYYY") }}  - {{ location.to | moment("Do MMMM YYYY") }}
        </p>

        <div class="location-add"  v-show="editMode">
            <div class="datepicker">
                <label>From</label>
                <datepicker
                    :limit="fromLimit"
                    class="form-control"
                    :date="{ time: from }"
                    :default-date="fromDefault"
                    @change="fromDateChanged"
                    :option="datepickerOptions"
                ></datepicker>
            </div>
            <div class="datepicker">
                <label>To</label>
                <datepicker
                    :limit="toLimit"
                    class="form-control"
                    :date="{ time: to }"
                    @change="toDateChanged"
                    :default-date="toDefault"
                    :option="datepickerOptions"
                ></datepicker>
            </div>
        </div>
    </div>
</template>

<script>


    import myDatepicker from 'vue-datepicker';
    import DatePicker from 'vue2-datepicker';
    import { EventBus } from '../../event-bus';

    export default {

        components: {
            'datepicker': myDatepicker
        },

        props: ['location'],

        data () {
            return {
                to: this.location.to ? moment(this.location.to).format('DD/MM/YYYY') : null,
                toDefault: this.location.to ? moment(this.location.to).format('YYYY-MM-DD') : null,

                from: this.location.from ? moment(this.location.from).format('DD/MM/YYYY') : null,
                fromDefault: this.location.from ? moment(this.location.from).format('YYYY-MM-DD') : null,

                fromLimit: [{
                    type: 'fromto',
                    from: '1970-01-01',
                    to: this.location.to ? moment(this.location.to).format('YYYY-MM-DD') : null
                }],

                toLimit: [{
                    type: 'fromto',
                    from: this.location.from ? moment(this.location.from).format('YYYY-MM-DD') : null,
                    to: '2099-12-30'
                }],

                datepickerOptions: {
                    type: 'day',
                    month: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    format: 'DD/MM/YYYY',
                    placeholder: 'when?'
                },
            }
        },

        computed: {
            editMode() {
                return this.$store.getters.editMode;
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

                this.saveDates(this.toDefault, this.fromDefault);
            },

            /**
             *
             * @param date
             */
            toDateChanged(date) {
                this.to = moment(date, 'DD/MM/YYYY').format('DD/MM/YYYY');
                this.toDefault = moment(date, 'DD/MM/YYYY').format('YYYY-MM-DD');
                this.fromLimit[0].to = this.toDefault;

                this.saveDates(this.toDefault, this.fromDefault);
            },


            saveDates(toDate, fromDate) {
                let postData = {
                    to: toDate,
                    from: fromDate
                };

                axios.put('/api/location/' + this.location.id, postData)
                    .then(function (response) {
                        EventBus.$emit('refresh-trip');
                    })
                    .catch(function (error) {});

            }
        }

    }
</script>