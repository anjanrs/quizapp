<template>
    <div class="container quiz-user-reg">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card quiz-user-reg-card">
                    <!--<div class="card-header"></div>-->
                    <div class="card-body">
                            <h2>Enter email address to get started</h2>
                            <input type="text" placeholder="email@gmail.com" v-model="email"/>
                            <button v-on:click="registerUser">Get Stated</button>
                            <div v-show="errMsg" class="err-msg">{{errMsg}}</div>
                    </div>
                </div>
                <div class="go-back" v-on:click="goToQuizList">Go to Quiz List</div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';

    export default {
        data: function () {
            return {
                email: '',
                errMsg: '',
            }
        }, 
        methods: {
            ...mapActions([
                'displayScreen',
                'resetState'
            ]), 
            registerUser: function(event) {
                this.errMsg = "";
                if (!this.email) {
                    this.errMsg = "Email cannot be empty";
                    return;
                }
                var isValidEmail = /(.+)@(.+){2,}\.(.+){2,}/.test(this.email);
                if(!isValidEmail) {
                    this.errMsg = "Please enter valid email address";
                    return;
                }
                this.$store.dispatch('registeruser', { email: this.email, quizId: this.selectedQuizId })
                this.email = '';
            },
            goToQuizList: function() {
                this.email = '';
                this.resetState();
            }
        },
        computed: {
            ...mapGetters([
                'selectedQuizId',
                'userEmail'
            ]),
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
