<template>
    <input :style="'width:'+width+'px; height:'+height+'px;'" class="form-control datepicker" ref="input" :value="value"/>
</template>

<script>
    export default {
        props: {
            width: {
                type: Number
            },
            height: {
                type: Number
            },
            value: {
                type: String
            },
            format:{
                type: String,
                default:'dd/mm/yyyy'
            }
        },
        mounted: function () {
            let self = this;
            Vue.nextTick(function () {
                self.buildDatePicker();
            });
        },
        methods: {
            buildDatePicker: function () {
                let self = this;
                let input = $(self.$refs.input);
                input.datepicker({
                    todayHighlight: false,
                    autoclose: true,
                    format: self.format,
                    language:'es'
                }).on('changeDate', function (e) {
                    let date = e.format(self.format);
                    self.$emit('input', date);
                }).on('clearDate', function () {
                    self.$emit('input', '');
                });
            },
            updateDatePicker: function () {
                let input = $(this.$refs.input);
                input.datepicker("update");
            }
        }
    }
</script>

<style scoped>
</style>