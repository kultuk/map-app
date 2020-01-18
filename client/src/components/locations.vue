<template>
  <div class="container">
    <b-button type="is-primary" class="logout-btn" @click="logout">Logout</b-button>
    <!-- <ul>
        <li v-for="location in locations" :key="location.lng + ':' + location.lat">{{location.lng + ':' + location.lat}}</li>
    </ul> -->
    <div class="map-wrap">
        <GmapMap
            :center="{lat:32.79282903947754, lng:35.038893926862364}"
            :zoom="18"
            map-type-id="terrain"
            id="map"
            @click="addLocation"
            >
            <GmapMarker
                :key="index"
                v-for="(m, index) in markers"
                :position="m.position"
                :clickable="true"
                :draggable="true"
                @click="center=m.position"
            />
            </GmapMap>
    </div>
    <!-- <b-button type="is-info" @click="addLocation">addLocation</b-button> -->
  </div>
</template>

<script>
import $users from '../Handlers/users'; 
import { ToastProgrammatic as Toast } from 'buefy'


export default {
  name: 'Locations',
  props: {
    msg: String
  },
  created(){
      $users.getLocations().then(locations=> {this.locations = locations});
  },
  data(){
      this.locations = this.locations || []
      return {locations:this.locations}
  },
  computed:{
      markers(){
        return this.locations.map(location=>{
            return {position: {lat:parseFloat(location.lat), lng:parseFloat(location.lng)}}
        })

      }
  },
  methods: {
      logout(){
          $users.logout();
          this.$parent.authToken = null;
      },
      addLocation(event){
            var location = {lat:event.latLng.lat(), lng:event.latLng.lng()}
          Toast.open("Location Saved!");
          this.locations.push(location);
          $users.addLocation(location);
      }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#map {
    overflow:hidden;
    padding-bottom:56.25%;
    position:relative;
    height:0;
    max-width: 500px; 
    max-height: 300px; 
    margin: auto;
}
#map iframe{
    left:0;
    top:0;
    height:100%;
    width:100%;
    position:absolute;
}
button.logout-btn {position: absolute;top: 13px;left: calc(50% - 76px);z-index: 2;}
</style>
