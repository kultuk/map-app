import Vue from 'vue'
import App from './App.vue'
import Buefy from 'buefy'
import 'buefy/dist/buefy.css'
import * as VueGoogleMaps from 'vue2-google-maps'
 
var setting = require('./config.json'); 
var credentials = require('./creds.json'); 

global.c = {...setting,
  SERVER_LOCATIONS: setting.server.address + ":" + setting.server.port 
}

Vue.use(VueGoogleMaps, {
  load: {
    key: credentials.GOOGLE_API_KEY
  }
})
Vue.use(Buefy);
Vue.config.productionTip = false


new Vue({  
  render: h => h(App),
}).$mount('#app')


