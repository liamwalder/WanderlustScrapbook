<template>
    <div class="location-dates">
        <p class="date" v-if="location.from && location.to"  v-show="!editMode">
            {{ location.from | moment("Do MMMM YYYY") }}  - {{ location.to | moment("Do MMMM YYYY") }}
        </p>

        <div class="location-add"  v-show="editMode">
            <div class="datepicker">
                <label>From</label>
                <date-picker
                    lang="en"
                    v-model="from"
                    format="dd/MM/yyyy"
                    placeholder="12/07/2017"
                    :not-after="notAfterDate == null ? '' : notAfterDate"
                ></date-picker>
            </div>
            <div class="datepicker">
                <label>To</label>
                <date-picker
                    lang="en"
                    v-model="to"
                    format="dd/MM/yyyy"
                    placeholder="19/07/2017"
                    :not-before="notBeforeDate"
                ></date-picker>
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
                to: this.location.to ? this.location.to : null,
                from: this.location.from ? this.location.from : null,
                notAfterDate: this.location.to ? this.location.to : null,
                notBeforeDate: this.location.from ? this.location.from : null
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
                this.saveDates();
            },
            to: function(to) {
                let toDate = new Date(to);
                this.notAfterDate = toDate.getFullYear() + '-' + (toDate.getMonth() + 1) + '-' + toDate.getDate();
                this.saveDates();
            }
        },

        methods: {


            confirmed() {
                console.log('ere');
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
                    })
                    .catch(function (error) {});

            }
        }

    }
</script>