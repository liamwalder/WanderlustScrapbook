<template>
    <div>
        <div v-show="editingLocationDates === null">
            <p class="date" v-if="location.from && location.to">
                {{ location.from | moment("Do MMMM YYYY") }}  - {{ location.to | moment("Do MMMM YYYY") }}
                <a v-on:click="editDates(location)" class="instruction" v-show="editMode">Change Dates</a>
            </p>
        </div>
        <div class="location-add"  v-show="editMode && editingLocationDates !== null">
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
                <span @click="saveDates()" class="instruction">Save Dates</span>
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

        computed: {
            editMode() {
                return this.$store.getters.editMode;
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
                let toDate = new Date(this.to);
                let fromDate = new Date(this.from);

                let postData = {
                    to: toDate.getFullYear() + '-' + (toDate.getMonth() + 1) + '-' + toDate.getDate(),
                    from: fromDate.getFullYear() + '-' + (fromDate.getMonth() + 1) + '-' + fromDate.getDate()
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