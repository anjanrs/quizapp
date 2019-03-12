<template>
    <div class="container quiz-already-completed">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                            You have already completed this quiz. </br>
                            Click <label style="cursor: pointer;text-decoration: underline;" v-on:click="resetState">here</label>
                            to go back to Quiz List
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';

    export default {
        methods: {
            ...mapActions([
                'resetState'
            ]), 
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
