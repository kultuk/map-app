<template>
  <div class="login-form">
    <section>
        <b-button type="is-light" @click="toggleRegister">{{this.register ? "Already has a user?" : "New User?"}}</b-button>
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
  data: function () {
    var password,username,register;
    return {password,username,register}
  },
  methods:{
      toggleRegister: function () {
        this.register = !this.register
      },
      login: function () {
      $users.login(this.username,this.password, this.register).then(
        login=>{
          var message;
          if(login.success){
            message = 'success!'; 
            this.$parent.authToken = login.accessToken;
          }else{
            message= login.error;
          }
          Toast.open(message);
        });
    }
  }
}
</script>

<style scoped>
    .login-form {
        width: 50%;
        margin: auto;
    }
</style>
