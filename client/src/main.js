import Vue from 'vue'
import App from './App.vue'
import Buefy from 'buefy'
import 'buefy/dist/buefy.css'

// import routes from './Handlers/routes'
// import VueRouter from 'vue-router'

var setting = require('./config.json'); 

global.c = {
  SERVER_LOCATIONS: setting.server.address + ":" + setting.server.port
}

Vue.use(Buefy);
// Vue.use(VueRouter);
// const router = new VueRouter({
//   routes // short for `routes: routes`
// })
Vue.config.productionTip = false


new Vue({  
  render: h => h(App),
  // router
}).$mount('#app')


