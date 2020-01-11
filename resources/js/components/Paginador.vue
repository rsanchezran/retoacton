<template>
    <nav v-show="pagesNumber.length > 1">
        <ul class="pagination">
            <li class="page-item" v-show="items.current_page > 1">
                <a class="page-link" href="#" v-on:click="previous(items.current_page)" aria-label="Previous">
                    <span aria-hidden="true">«</span>
                </a>
            </li>
            <li class="page-item" v-for="page in pagesNumber"
                :class="[ page == isActived ? 'active' : '']">
                <a class="page-link" href="#" v-on:click="cambiarPagina(page)">{{ page }}</a>
            </li>
            <li class="page-item" v-show="items.current_page < items.last_page">
                <a class="page-link" href="#" v-on:click="next(items.current_page)" aria-label="Next">
                    <span aria-hidden="true">»</span>
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        props: ['url'],
        data: function () {
            return {
                offset: 4,
                pagesArray: [],
                items:[],
                filtros:{},
                page:1
            }
        },
        computed: {
            isActived: function () {
                return this.items.current_page
            },
            pagesNumber: function () {
                if (!this.items.to) {
                    return [];
                }
                var from = this.items.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if (to >= this.items.last_page) {
                    to = this.items.last_page;
                }
                this.pagesArray = [];
                while (from <= to) {
                    this.pagesArray.push(from);
                    from++;
                }
                return this.pagesArray;
            }
        },
        methods: {
            cambiarPagina:function (page) {
                this.page = page;
                this.consultar(this.filtros,page);
            },
            previous:function (page) {
                this.page = page-1;
                this.consultar(this.filtros,this.page);
            },
            next:function (page) {
                this.page = page+1;
                this.consultar(this.filtros,this.page);
            },
            consultar: function (filtros,page) {
                var c_vm = this;
                c_vm.filtros =filtros;
                this.buscando = true;
                axios.get(this.url, {
                    params: {
                        page:page,
                        campos: c_vm.filtros
                    }
                })
                    .then(function (response) {
                        c_vm.items = response.data;
                        c_vm.$emit('loaded', c_vm.items);
                    });
            }
        }
    }
</script>