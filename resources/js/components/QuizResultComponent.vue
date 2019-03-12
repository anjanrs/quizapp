<template>
    <div class="container quiz-result">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card quiz-result-card">
                    <div class="card-header"><h2>Quiz Result</h2></div>
                    <div class="card-body">
                        Out of {{ quizResult.total }} questions you have answered {{ quizResult.correct }} correctly.
                    </div>
                </div>
                <div class="go-back" v-on:click="resetState('quiz-list')">Go to Quiz List</div>
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
            ]) 
        },
        computed: {
            ...mapGetters([
                'quizResult',
            ]),
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
