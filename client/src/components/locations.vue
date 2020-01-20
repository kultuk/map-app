<template>
  <div >
    <b-button type="is-primary" class="logout-btn" @click="logout">Logout</b-button>
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
  </div>
</template>

<script>
import $users from '../Handlers/users'; 
import { ToastProgrammatic as Toast } from 'buefy'


export default {
  name: 'Locations',
  created(){
      $users.getLocations().then(locations=> {
            if(locations.invalidToken){
                this.logout();
            }
          this.locations = locations
          });
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
          $users.addLocation(location).then(result=>{
              if(!result.success && result.invalidToken){
                this.logout();
              }
          });
      }
  }
}
</script>

<style scoped>
#map {
    overflow:hidden;
    padding-bottom:56.25%;
    position:relative;
    height:100vh;
    width:100%;
    margin: auto;
}
#map iframe{
    left:0;
    top:0;
    height:100%;
    width:100%;
    position:absolute;
}
button.logout-btn {position: absolute;top: calc(92%);left: calc(50% - 76px);z-index: 2;}
</style>
