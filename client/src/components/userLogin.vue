<template>
  <div class="login-form">
    <section>
        <form>
            <b-field label="Username"
                type="is-success">
                <b-input v-model="username" placeholder="Enter user name"></b-input>
            </b-field>
            <b-field label="Password">
                <b-input type="password"
                    placeholder="Enter password"
                    v-model="password"
                    password-reveal>
                </b-input>
            </b-field>
            <b-button v-if="!register" type="is-primary" @click="login">Login</b-button>
            <b-button v-if="register" type="is-primary" @click="login">Register</b-button>
        </form>
    </section>
  </div>
</template>

<script>
import { ToastProgrammatic as Toast } from 'buefy'
import $users from '../Handlers/users';

export default {
  name: 'userLogin',
  props: {
    msg: String,
    register: Boolean
  },
  data: function () {
    var password,username;
    return {password,username}
  },
  methods:{
    login: function () {
      // alert(global.c.SERVER_LOCATIONS)
      $users.login(this.username,this.password, this.register).then(
        login=>{
          var message;
          if(login.success){
            message = 'success!'; 
            localStorage.setItem('auth_token',login.accessToken);
          }else{
            message= login.error;
          }
          Toast.open(message);
        });
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .login-form {
        width: 50%;
        margin: auto;
    }
</style>
