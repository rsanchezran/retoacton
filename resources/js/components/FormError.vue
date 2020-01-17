<template>
    <div>
        <slot></slot>
        <strong class="form-error" v-if="errors[name]!=undefined" class="small text-danger">
            {{ getError }}
        </strong>
    </div>
</template>

<script>
    export default {
        props: ['errors','name'],
        computed: {
            getError: function () {
                return this.errors[this.name][0];
            }
        }
    }
</script>