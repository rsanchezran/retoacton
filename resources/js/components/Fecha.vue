<template>
    <span>
        {{format()}}
    </span>
</template>

<script>
    export default {
        props: {
            fecha: {
                type: String,
                twoWay: true,
                default: ''
            },
            formato:{
                type: String,
                default:'dd/mm/yyyy'
            }
        },
        methods: {
            format:function () {
                if (this.fecha == null) {
                    return '';
                } else {
                    if (this.fecha == '') {
                        return '';
                    } else {
                        let items = this.fecha.split('-');
                        let subitems = items[2].split(' ');
                        let horas = subitems.length > 1 ? subitems[1].split(':') : [];
                        switch (this.formato){
                            case 'dd/mm/yyyy':
                                return subitems[0] + '/' + items[1] + '/' + items[0];
                            case 'dd/mm/yyyy hh:ii:ss':
                                return  subitems[0] + '/' + items[1] + '/' + items[0]+' '+horas[0]+':'+horas[1]+':'+horas[2];
                            case 'dd/mm/yyyy hh:ii':
                                return subitems[0] + '/' + items[1] + '/' + items[0]+' '+horas[0]+':'+horas[1];
                        }
                    }
                }
            }
        }
    }
</script>