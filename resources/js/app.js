
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Vuex = require('vuex');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

	// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('vc-quiz-list', require('./components/QuizListComponent.vue').default);
Vue.component('vc-quiz-user-reg', require('./components/QuizUserRegComponent.vue').default);
Vue.component('vc-quiz-questions-wizard', require('./components/QuizQuestionsWizardComponent.vue').default);
Vue.component('vc-quiz-result', require('./components/QuizResultComponent.vue').default);
Vue.component('vc-quiz-already-completed', require('./components/QuizAlreadyCompletedComponent.vue').default);
Vue.use(Vuex);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

let initialState = {
	loadingStatus: false,
	activeScreen: 'quiz-list',  //quiz-list, quiz-questions-wizard, register-user, quiz-already-completed
	quizzes: quizzes,
	questions: [],
	activeQuestionIndex: 0,
	registeredUser: {},
	selectedQuizId: 0,
	userEmail: '',
	errMessage: '',
	skippedQuestoinId: 0,
	userAnswersForQuestions: [],
	quizResult: {}
};

let isValidAnswer = (state, questionIndex) => {
	let answerSelected = false;
	_.each(state.questions[questionIndex].quiz_answers, function(answer) {
		if(!answerSelected && answer.selected) {
			answerSelected = true;
		}
	});
	return answerSelected;
}

const store = new Vuex.Store({
	strict: true,
	state: {...initialState},
	mutations: {
		setLoadingStatus: (state, status) => {
			state.loadingStatus = status;
		},
		setQuizzes: (state, quizzes) => {
			state.quizzes = quizzes;
		},
		setActiveScreen: (state, screen) => {
			state.activeScreen = screen;
		},
		setRegisteredUser: (state, registeredUser) => {
			state.registeredUser = registeredUser;	
		},
		setUserEmail: (state, email) => {
			state.userEmail = email;
		},
		setSelectedQuizId: (state, quizid) => {
			state.selectedQuizId = quizid;	
		},
		setErrorMessage: (state, message) => {
			state.errMessage = message;		
		},
		setQuestions: (state, questions) => {
			state.questions = questions;			
		},
		setActiveQuestionIndex: (state, index) => {
			state.activeQuestionIndex = index;			
		},
		setSkippedQuestionId: (state, questionId) => {
			state.skippedQuestoinId = questionId;
		}, 
		setAnswerSelected: (state, params) => {
			_.map(state.questions, question => {
				if(question.id == params.questionId) {
					_.map(question.quiz_answers, answer => {
						if(answer.id == params.answerId) {
							answer.selected = 1;
						}
					});
				}
			});
		}, 
		setAnswerUnSelected: (state, params) => {
			_.map(state.questions, question => {
				if(question.id == params.questionId) {
					_.map(question.quiz_answers, answer => {
						if(answer.id == params.answerId) {
							answer.selected = 0;
						}
					});
				}
			});
		}, 
		setAllAnswersAsUnSelected: (state, questionId) => {
			_.map(state.questions, question => {
				if(question.id == questionId) {
					_.map(question.quiz_answers, answer => {
						answer.selected = 0;
					});
				}
			});
		},
		setQuizResult: (state, quizResult) => {
			state.quizResult = quizResult;
		},
		initializeState: state => {
			Object.keys(initialState).forEach(key => {
		         state[key] = initialState[key]
	        });
		}
	},
	actions: {
		resetState(context) {
			context.commit('initializeState');
		},
		fetchQuizzes(context) {
			context.commit('setLoadingStatus', true);
			context.commit('setQuizzes', quizzes);
			context.commit('setLoadingStatus', false);
		},
		registeruser(context, params) {
			context.commit('setLoadingStatus', true);	
			//register user
			axios.post('api/quiz/register/user',{
                    quizid: params.quizId,
                    email: params.email
                })
                .then(response => {
                	context.commit('setLoadingStatus', false);	
                    let $objData = response.data;
                    if($objData.status === 'success') {
                    	context.commit('setRegisteredUser', $objData.registeredUser);	
                    	context.commit('setUserEmail', $objData.registeredUser.email);
                    	axios.post('api/quiz/questions', {
                    			quizid: params.quizId
                    		})
                    		.then(response => {
                        		let $objQuestionData = response.data;
	                    		context.commit('setQuestions', $objQuestionData.quizQuestions);	
	                    		context.commit('setActiveScreen', 'quiz-questions-wizard');	
	                    		context.commit('setActiveQuestionIndex', 0);	
	                    	}) 
	                    	.catch(error => {
			                	context.commit('setLoadingStatus', false);	
			                	context.commit('setErrorMessage', 'some error occured');
			                });
	                    	
                    }

                    if($objData.status === 'error') {
                    	context.commit('setActiveScreen', 'quiz-already-completed');	
                    }
                })
                .catch(error => {
                	context.commit('setLoadingStatus', false);	
                	context.commit('setErrorMessage', 'some error occured');
                });
		},
		displayScreen: (context, newScreen) => {
			context.commit('setActiveScreen', newScreen);
		},
		selectQuiz: (context, quizId) => {
			context.commit('setSelectedQuizId', quizId);	
		},
		showPreviousQuestion: (context, currentIndex) => {
			context.commit('setErrorMessage', '');
			let newIndex = currentIndex -1;
			if (newIndex > -1) {
				context.commit('setActiveQuestionIndex', newIndex);			
			}
			
		},
		showNextQuestion: (context, currentIndex) => {
			context.commit('setErrorMessage', '');
			let newIndex = currentIndex + 1;
			let validAnswer = isValidAnswer(context.state, currentIndex);
			let canSkip = (context.state.skippedQuestoinId == 0 || context.state.skippedQuestoinId == context.state.questions[currentIndex].id);
			if(!validAnswer && !canSkip) {
				context.commit('setErrorMessage', 'Must select at least one answer.');
			} 
			else if(newIndex <= context.state.questions.length-1) {
				//set skipped question id
				if(!validAnswer && canSkip) {
					context.commit('setSkippedQuestionId', context.state.questions[currentIndex].id);				
				} 
				if(validAnswer && canSkip) {
					context.commit('setSkippedQuestionId', 0);					
				}
				context.commit('setActiveQuestionIndex', newIndex);			
			}
		},
		skipQuestion: (context, currentIndex) => {
			let newIndex = currentIndex + 1;
			if(newIndex <= context.state.questions.length-1) {
				context.commit('setActiveQuestionIndex', newIndex);			
				context.commit('setAllAnswersAsUnSelected', context.state.questions[currentIndex].id);
				context.commit('setSkippedQuestionId', context.state.questions[currentIndex].id);	
			}
		},
	    selectAnswer: (context, params) => {
	    	context.commit('setAnswerSelected', params);			
	    },
	    disSelectAnswer: (context, params) => {
	    	context.commit('setAnswerUnSelected', params);			
	    },
	    submitAnswers: (context, currentIndex) => {
	    	context.commit('setErrorMessage', '');
	    	let validAnswer = isValidAnswer(context.state, currentIndex);
			let canSkip = (context.state.skippedQuestoinId == 0 || context.state.skippedQuestoinId == context.state.questions[currentIndex].id);
			if(!validAnswer && !canSkip) {
				context.commit('setErrorMessage', 'Must select at least one answer.');
				return;
			} 
			//set skipped question id
			if(!validAnswer && canSkip) {
				context.commit('setSkippedQuestionId', context.state.questions[currentIndex].id);
			} 
			if(validAnswer && canSkip) {
				context.commit('setSkippedQuestionId', 0);					
			}
			
	    	context.commit('setLoadingStatus', true);	
			//register user
			axios.post('api/quiz/submit/answers',{
					userId: context.state.registeredUser.id,
                    quizId: context.state.selectedQuizId,
                    questionsAnswers: context.state.questions
                })
                .then(response => {
                	context.commit('setLoadingStatus', false);	
                    let $objData = response.data;
                    if($objData.status === 'success') {
                    	axios.post('api/quiz/result', {
                    			quizId: context.state.selectedQuizId,
                    			userId: context.state.registeredUser.id
                    		})
                    		.then(response => {
                        		let $objQuizResult = response.data;
                        		context.commit('setQuizResult', $objQuizResult.quizResult);
	                    		context.commit('setActiveScreen', 'quiz-result');	
	                    	}) 
	                    	.catch(error => {
			                	context.commit('setLoadingStatus', false);	
			                	context.commit('setErrorMessage', 'some error occured');
			                });
	                    	
                    } 
                })
                .catch(error => {
                	context.commit('setLoadingStatus', false);	
                	context.commit('setErrorMessage', 'some error occured');
                });
	    }
	},
	getters: {
		quizzes: state => {
            return state.quizzes;
        },
        activeScreen: state  => {
            return state.activeScreen;
        },
        selectedQuizId: state =>  {
            return state.selectedQuizId;
        },
        userEmail: state => {
        	return state.userEmail;
        },
        questions: state => {
        	return state.questions;
        },
        activeQuestion: state => {
        	return state.questions[state.activeQuestionIndex];
        },
        activeQuestionIndex: state => {
        	return state.activeQuestionIndex;
        },
        skippedQuestionId: state => {
        	return state.skippedQuestoinId;
        },
        quizResult: state => {
        	return state.quizResult;
        },
        errMessage: state => {
        	return state.errMessage
        }
	}
});

//from component we dispatch
// this.$store.dispatch('fetchQuizzes');

//from compoent we can use getter
// this.$store.getters.getActiveQuizzes

const app = new Vue({
    el: '#app',
    store: store
});