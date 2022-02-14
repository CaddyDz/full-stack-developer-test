import Vue from 'vue'
import Router from 'vue-router'
import Currencies from './components/Currencies.vue'
import History from './components/History.vue'
import NotFound from './components/NotFound.vue'

Vue.use(Router)

export default new Router({
	mode: 'history',
	base: process.env.BASE_URL,
	routes: [{
			path: '/',
			name: 'currencies',
			component: Currencies
		},
		{
			path: '/history',
			name: 'history',
			// route level code-splitting
			// this generates a separate chunk (history.[hash].js) for this route
			// which is lazy-loaded when the route is visited.
			component: () => import( /* webpackChunkName: "history" */ './components/History.vue')
		},
		{
			path: '*',
			component: NotFound
		}
	]
})
