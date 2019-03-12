<template>
    <div class="container quiz-list">
        <div class="row justify-content-center">
            <div class="col-md-8" v-show="shouldDisplay('quiz-list')">
                <div class="card quiz-list-card" v-for="quiz in quizzes" :key="quiz.id" v-on:click="showRegistrationForm(quiz.id)">
                    <!--<div class="card-header">Take quiz "{{ quiz.name }}"</div>-->
                    <div class="card-body">
                        <h1>Take quiz "{{ quiz.name }}"</h1>
                    </div>
                </div>
            </div>
            <vc-quiz-user-reg v-show="shouldDisplay('register-user')" :quizid="selectedQuizId"></vc-quiz-user-reg>
            <vc-quiz-questions-wizard v-show="shouldDisplay('quiz-questions-wizard')"></vc-quiz-questions-wizard>
            <vc-quiz-result v-show="shouldDisplay('quiz-result')"></vc-quiz-result>
            <vc-quiz-already-completed  v-show="shouldDisplay('quiz-already-completed')"></vc-quiz-already-completed>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';

    export default {
        methods: {
            ...mapActions([
                'displayScreen'
            ]), 
            showRegistrationForm: function(quizid) {
                this.displayScreen('register-user');
                this.$store.dispatch('selectQuiz', quizid);
                //this.$store.commit('setSelectedQuizId', 1);
            },
            shouldDisplay: function(screenid) {
                return this.activeScreen == screenid;
            } 
        },
        computed: {
            ...mapGetters([
                'quizzes',
                'selectedQuizId',
                'activeScreen'
            ]),
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
