<template>
    <span :style="estilo">
       {{caracter?'$ ':''}} {{format()}}
    </span>
</template>

<script>
    export default {
        props: {
            cantidad: {
                type: String,
                default: ''
            },
            estilo: {
                type: String,
                default: ''
            },
            caracter:{
                type: Boolean,
                default: false
            },
            decimales:{
                type: Number,
                default: 2
            },
            adicional:{
                type: String,
                default: ''
            }

        },
        methods: {
            format:function () {
                if (this.cantidad == null) {
                    return '';
                } else {
                    if (this.cantidad == '') {
                        return '';
                    } else {
                        return parseFloat(this.cantidad).toFixed(this.decimales).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+this.adicional;
                    }
                }
            }
        },
    }
</script>