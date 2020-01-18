<template>
  <div class="hello">
    <b-button type="is-primary" @click="logout">Logout</b-button>
    <ul>
        <li v-for="location in locations" :key="location.lon + ':' + location.lat">{{location.lon + ':' + location.lat}}</li>
    </ul>
    <b-button type="is-info" @click="addLocation">addLocation</b-button>
  </div>
</template>

<script>
import $users from '../Handlers/users';

export default {
  name: 'Locations',
  props: {
    msg: String
  },
  created(){
      $users.getLocations().then(locations=> {this.locations = locations})
  },
  data(){
      this.locations = this.locations || []
      return {locations:this.locations}
  },
  methods: {
      logout(){
          $users.logout();
          this.$parent.authToken = null;
      },
      addLocation(){
          var location = {
              lon: 88,
              lat: 99
          }
          this.locations.push(location);
          $users.addLocation(location);
      }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>
