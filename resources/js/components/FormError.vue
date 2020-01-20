<template>
    <div>
        <slot></slot>
        <strong v-if="errors[name]!=undefined" class="form-error small text-danger">
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