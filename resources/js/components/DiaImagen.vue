<template id="dias-img-template">

    <img :src="imagen" width="100%">

    <div class="container">
        <div>
            <ul v-for="a in this.comentariosdata">
                <li>@{{a.comentario}}</li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id','dia', 'imagen'],
        data: function () {
            return {
                comentariosdata: []
            }
        },
        methods: {

            comentarios: function (dia) {
                let vm = this;
                vm.errors = [];

                axios.post('/usuarios/comentarios/'+dia+'/'+this.id).then(function (response) {
                    this.comentariosdata = response.data;
                    console.log(this.comentariosdata)
                }).catch(function (error) {
                    vm.errors = error.response.data.errors;
                });
            }
        },
        created: function () {
            this.comentariosdata(this.dia);
        }
    }

</script>
