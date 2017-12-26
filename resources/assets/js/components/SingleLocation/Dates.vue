<template>
    <div>
        <div v-if="editingLocationDates === null">
            <p class="date" v-if="location.from && location.to">
                {{ location.from | moment("Do MMMM YYYY") }}  - {{ location.to | moment("Do MMMM YYYY") }}
                <a v-on:click="editDates(location)">Edit Dates</a>
            </p>
        </div>
        <div class="location-add" v-else>
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
                <span @click="saveDates()" class="add-to-trip">Save Dates</span>
            </div>
        </div>
    </div>
</template>

<script>

    import DatePicker from 'vue2-datepicker';
    import { EventBus } from '../../event-bus';

    export default {

        components: { DatePicker },

        props: ['location'],

        data () {
            return {
                to: null,
                from: null,
                notAfterDate: null,
                notBeforeDate: null,
                editingLocationDates: null
            }
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
            editDates(location) {
                this.to = location.to;
                this.from = location.from;
                this.editingLocationDates = location;
            },

            saveDates() {
                let self = this;
                let postData = {
                    to: this.to,
                    from: this.from
                };

                axios.put('/api/location/' + this.location.id, postData)
                    .then(function (response) {
                        EventBus.$emit('refresh-trip');
                        self.editingLocationDates = null;
                    })
                    .catch(function (error) {});

            }
        }

    }
</script>