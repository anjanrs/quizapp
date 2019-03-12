<template>
    <div class="container quiz-question-wizard">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card quiz-question-wizard-card" v-for="question in questions" :key="question.id" 
                    v-show="question.id == activeQuestion.id">
                    <div class="card-header">{{ question.question }}</div>
                    <div class="card-body">
                        <div v-for="answer in question.quiz_answers" :key="answer.id">
                            <input type="checkbox" :id="answer.id" :value="answer.id" v-model="answer.selected" v-on:click="answerClick($event, question.id, answer.id)" />
                            <label :for="answer.id">{{answer.answer}}</label>
                        </div>
                        <div class="err-msg">{{errMessage}}</div>
                    </div>
                </div>
            </div>
            
            
        </div>
        <div class="row justify-content-center" style="margin-top: 20px;">
             <div class="col-md-8 quiz-wizard-buttons">
                <button 
                    v-on:click="showPreviousQuestion(activeQuestionIndex)" 
                    v-show="showPreviousButton">
                        Previous
                </button> &nbsp;&nbsp;
                <button class="right-align"
                    v-on:click="showNextQuestion(activeQuestionIndex)" 
                    v-show="!showSubmitButton">
                        Next
                </button>&nbsp;&nbsp;
                <!--<button 
                    v-on:click="skipQuestion(activeQuestionIndex)" 
                    v-show="!skippedQuestionId">
                        Skip
                </button>&nbsp;-->
                <button class="right-align"
                    v-on:click="submitAnswers(activeQuestionIndex)" 
                    v-show="showSubmitButton">
                        Submit Answers
                </button>&nbsp;&nbsp;
             </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';

    export default {
        methods: {
            ...mapActions([
                'displayScreen',
                'showPreviousQuestion',
                'showNextQuestion',
                'skipQuestion',
                'selectAnswer',
                'disSelectAnswer',
                'submitAnswers'
            ]), 
            showRegistrationForm: function(quizid) {
                this.displayScreen('register-user');
                this.$store.dispatch('selectQuiz', quizid);
                //this.$store.commit('setSelectedQuizId', 1);
            },
            shouldDisplay: function(screenid) {
                return this.activeScreen == screenid;
            },
            answerClick: function(event, questionId, answerId) {
                if (event.target.checked) {
                    this.selectAnswer({questionId, answerId});
                } else {
                    this.disSelectAnswer({questionId, answerId});
                }
            }
        },
        computed: {
            ...mapGetters([
                'questions',
                'answers',
                'activeQuestion',
                'activeQuestionIndex',
                'skippedQuestionId',
                'errMessage'
            ]),
            showSubmitButton: function() {
                if(this.activeQuestionIndex == this.questions.length -1) {
                    return true;
                } else {
                    return false;
                }
            },
            showPreviousButton: function() {
                if(this.activeQuestionIndex == 0) {
                    return false;
                } else {
                    return true;
                }
            }

        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
