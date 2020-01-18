<template>
  <div id="app">
    <!-- {{SERVER_LOCATIONS}} -->
    <!-- <img alt="Vue logo" src="./assets/logo.png"> -->
    <userLogin register v-if="!this.authToken"/>
    <locations v-else/>
  </div>
</template>

<script>
// import HelloWorld from './components/HelloWorld.vue'
import userLogin from './components/userLogin.vue'
import locations from './components/locations.vue'

export default {
  name: 'app',
  data(){
    this.authToken = localStorage.getItem('auth_token');
    return {authToken:this.authToken}
  },
  components: {
    userLogin,
    locations
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
