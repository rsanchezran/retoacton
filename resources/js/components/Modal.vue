<script>
    /**
     * Bootstrap Style Modal Component for Vue
     * Depend on Bootstrap.css
     */

    export default {
        props: {
            id:{
                type:String,
            },
            p_show: {
                type: Boolean,
                twoWay: true,
                default: false
            },
            showok: {
                type: Boolean,
                default: true
            },
            showcancel: {
                type: Boolean,
                default: true
            },
            wide: {
                type: Boolean,
                default: false
            },
            title: {
                type: String,
                default: 'Modal'
            },
            small: {
                type: Boolean,
                default: false
            },
            large: {
                type: Boolean,
                default: false
            },
            full: {
                type: Boolean,
                default: false
            },
            force: {
                type: Boolean,
                default: false
            },
            transition: {
                type: String,
                default: 'modal'
            },
            oktext: {
                type: String,
                default: 'Aceptar'
            },
            cancelText: {
                type: String,
                default: 'Cancelar'
            },
            okClass: {
                type: String,
                default: 'btn btn-success'
            },
            cancelClass: {
                type: String,
                default: 'btn btn-default'
            },
            closeWhenOK: {
                type: Boolean,
                default: false
            },
            okdisabled: {
                type: Boolean,
                default: false
            },
            cancelDisabled: {
                type: Boolean,
                default: false
            },
            height: {
                type:String,
            }
            ,width: {
                type:String,
            },
            showfooter:{
                type:Boolean,
                default:true
            },
            showheader:{
                type:Boolean,
                default:true
            },
            btncerrar:{
                type: Boolean,
                default: true
            }
        },
        data() {
            return {
                duration: null,
                show: false,
                error: false,
                errorMsg: '',
                working: false,
            };
        },
        computed: {
            modalClass() {
                return {
                    'modal-lg': this.large,
                    'modal-sm': this.small,
                    'modal-full': this.full
                }
            }
        },
        created() {
            this.show = this.p_show;
            if (this.p_show) {
                document.body.className += ' modal-open';
            }
        },
        beforeDestroy() {
            document.body.className = document.body.className.replace(/\s?modal-open/, '');
        },
        watch: {
            show(value) {
                // 在显示时去掉body滚动条，防止出现双滚动条
                if (value) {
                    document.body.className += ' modal-open';
                }
                // 在modal动画结束后再加上body滚动条
                else {
                    if (!this.duration) {
                        this.duration = window.getComputedStyle(this.$el)['transition-duration'].replace('s', '') * 1000;
                    }

                    window.setTimeout(() => {
                        document.body.className = document.body.className.replace(/\s?modal-open/, '');
                    }, this.duration || 0);
                }
            }
        },
        methods: {
            ok() {
                this.working = true;
                this.$emit('ok');
                if (this.closeWhenOK) {
                    this.show = false;
                }
            },
            cancel() {
                this.$emit('cancel');
                this.show = false;
                this.error = false;
            },
            closeModal() {
                this.show = false;
                this.error = false;
                this.working = false;
            },
            showModal() {
                this.show = true;
            },
            clickMask() {
                if (!this.force && !this.working) {
                    this.cancel();
                }
            },
            setError(error) {
                this.error = true;
                this.errorMsg = error;
            },
            setWorking(bool) {
                this.working = bool;
            }
        }
    };
</script>

<template>
    <transition :name="transition">
        <div v-if="show">
            <div class="modal show" :id="id">
                <div class="modal-dialog" :class="modalClass" :style="wide?'width: 80%;':'normal'" ref="dialog">
                    <div class="modal-content" :style="'height:'+height+'px; width:'+width+'px;'">
                        <!--Header-->
                        <div class="modal-header" v-if="showheader">
                            <slot name="header">
                                <h5 class="modal-title">
                                    <slot name="title">
                                        {{title}}
                                    </slot>
                                </h5>
                                <button v-if="btncerrar" :disabled="working" type="button" class="form-control col-1" @click="cancel" align="left">
                                    X
                                </button>
                            </slot>
                        </div>
                        <!--Container-->
                        <div class="modal-body" style="padding-top: 0rem;">
                            <slot></slot>
                            <div v-if="error" class="text-danger">
                                <strong>{{errorMsg}}</strong>
                            </div>
                        </div>
                        <!--Footer-->
                        <div class="modal-footer" v-if="showfooter">
                            <slot name="footer">
                                <i v-if="working" class="far fa-spinner fa-spin fa-fw"></i>
                                <button :disabled="working || cancelDisabled" v-if="showcancel" type="button" :class="cancelClass" @click="cancel" >{{cancelText}}</button>
                                <button :disabled="working || okdisabled" v-if="showok" type="button" :class="okClass" @click="ok">{{oktext}}
                                </button>
                            </slot>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop in"></div>
        </div>
    </transition>
</template>

<style scoped>
    .modal {
        display: block;
    }

    .modal-enter-active {
        transition: all .2s ease;
    }

    .modal-leave-active {
        transition: all .2s ease;
    }

    .modal-enter, .modal-leave-to
    {
        /*transform: translatey(-100px);*/
        opacity: 0;
    }

    .modal-body {
        overflow: auto;
    }

    .modal-backdrop {
        opacity: 0.7;
    }

</style>

