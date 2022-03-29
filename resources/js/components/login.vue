<template>
<div class="center-content row">
    <vue-progress-bar></vue-progress-bar>
    <div class="col-md-4 clearfix center-margin" v-if="showLogin">
        <div class="row">
            <div class="col-md-12 mrg25T mrg25B">
                <div class="content-box">
                    <h2 class="content-box-header content-box-header-alt bg-default">
                        Login Form
                    </h2>
                    <div class="content-box-wrapper">
                        <transition name="fade" v-if="alert.success">
                            <notification-success 
                                title="SUCCESS!"
                                :message="alert.message">
                            </notification-success>
                        </transition>
                        <transition name="fade" v-if="alert.fail">
                            <notification-fail
                                title="FAILED!"
                                :message="alert.message">
                            </notification-fail>
                        </transition>
                        <form id="loginForm" class="mrg20B mrg10T" method="post" v-on:submit.prevent="login()"  data-vv-scope="login">
                            <div class="form-group col-md-10 center-margin" :class="{'has-error': errors.has('login.Email') }">
                                <div class="input-group">
                                    <input class="form-control" 
                                        v-model="email"
                                        :disabled="loginDisabled"
                                        type="text"
                                        placeholder="Enter your email"
                                        style="text-align: center;"
                                        name="Email"
                                        data-vv-validate-on="none"
                                        v-validate="'required|email'">
                                    <span class="input-group-addon bg-blue">
                                        <i class="glyph-icon icon-envelope-o"></i>
                                    </span>
                                </div>
                                <span v-if="errors.has('login.Email')" class="font-red font-size-11">
                                    {{ errors.first('login.Email') }}
                                </span>
                            </div>
                            <div class="form-group col-md-10 center-margin" :class="{'has-error': errors.has('login.Password') }">
                                <div class="input-group">
                                    <input class="form-control"
                                        v-model="password"
                                        :disabled="loginDisabled"
                                        type="password"
                                        placeholder="Enter your password"
                                        style="text-align: center;"
                                        name="Password"
                                        data-vv-validate-on="none"
                                        v-validate="'required'">
                                    <span class="input-group-addon bg-blue">
                                        <i class="glyph-icon icon-unlock-alt"></i>
                                    </span>
                                </div>
                                <span v-if="errors.has('login.Password')" class="font-red font-size-11">
                                    {{ errors.first('login.Password') }}
                                </span>
                            </div>
                            <div class="form-group col-md-10 center-margin">
                                <a
                                    v-on:click="toggleLoginAndRegister()"
                                    href="javascript:void(0);"
                                    title="Register">
                                    Click here to register.
                                </a>
                            </div>

                            <div class="col-md-10 center-margin mrg5B">
                                <button 
                                    form="loginForm"
                                    :disabled="loginDisabled"
                                    type="submit"
                                    class="btn btn-sm btn-success btn-block"
                                    data-toggle="modal"
                                    data-target="#register-modal">
                                    Sign In
                                </button>
                            </div>
                        </form>                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 clearfix center-margin" v-if="showRegister">
        <div class="row">
            <div class="col-md-12 mrg25T mrg25B">
                <div class="content-box">
                    <h2 class="content-box-header content-box-header-alt bg-default">
                        Registration Form
                    </h2>
                    <div class="content-box-wrapper">
                        <transition name="fade">
                            <notification-success v-if="alert.success"
                                title="SUCCESS!"
                                :message="alert.message">
                            </notification-success>
                        </transition>
                        <transition name="fade" v-if="alert.fail">
                            <div class="alert alert-danger">
                                <div class="bg-red alert-icon">
                                    <i class="glyph-icon icon-times"></i>
                                </div>
                                <div class="alert-content">
                                    <h4 class="alert-title">FAILED!</h4>
                                    <p>{{alert.message}}</p>
                                    <span v-for="(value, key) in alert.errors" :key="key">
                                        {{value}}<br>
                                    </span>
                                </div>
                            </div>
                        </transition>
                        <form id="registerForm" class="mrg20B mrg10T" method="post" v-on:submit.prevent="registerUser()"  data-vv-scope="register">
                            <div class="form-group col-md-10 center-margin" :class="{'has-error': errors.has('register.Email') }">
                                <label class="control-label">
                                    Email <i class="font-red">*</i>
                                </label>
                                <input
                                    :disabled="loginDisabled"
                                    type="text"
                                    class="form-control"                                    
                                    name="Email"
                                    data-vv-validate-on="none"
                                    v-validate="'required|email'"
                                    v-model="register.email"
                                    maxlength="64">
                                <span v-if="errors.has('register.Email')" class="font-red font-size-11">
                                    {{ errors.first('register.Email') }}
                                </span>
                            </div>

                            <div class="form-group col-md-10 center-margin" :class="{'has-error': errors.has('register.Username') }">
                                <label class="control-label">
                                    Username <i class="font-red">*</i>
                                </label>
                                <input
                                    :disabled="loginDisabled"
                                    type="text"
                                    class="form-control"                                    
                                    name="Username"
                                    data-vv-validate-on="none"
                                    v-validate="'required'"
                                    v-model="register.name"
                                    maxlength="32">
                                <span v-if="errors.has('register.Username')" class="font-red font-size-11">
                                    {{ errors.first('register.Username') }}
                                </span>
                            </div>

                            <div class="form-group col-md-10 center-margin" :class="{'has-error': errors.has('register.Password') }">
                                <label class="control-label">
                                    Password <i class="font-red">*</i>
                                </label>
                                <input 
                                    :disabled="loginDisabled"
                                    type="password"
                                    ref="password"
                                    class="form-control"                                    
                                    name="Password"
                                    data-vv-validate-on="none"
                                    v-validate="'required|min:12|verifyPassword'"
                                    v-model="register.password"
                                    maxlength="32">
                                <span v-if="errors.has('register.Password')" class="font-red font-size-11">
                                    {{ errors.first('register.Password') }}
                                </span>
                            </div>

                            <div class="form-group col-md-10 center-margin" :class="{'has-error': errors.has('register.Confirm Password') }">
                                <label class="control-label">
                                    Confirm Password <i class="font-red">*</i>
                                </label>
                                <input 
                                    :disabled="loginDisabled"
                                    type="password"
                                    class="form-control"                                    
                                    name="Confirm Password"
                                    data-vv-validate-on="none"
                                    v-validate="'required|confirmed:password|verifyPassword'"
                                    v-model="register.confirmedPassword"
                                    maxlength="32">
                                <span v-if="errors.has('register.Confirm Password')" class="font-red font-size-11">
                                    {{ errors.first('register.Confirm Password') }}
                                </span>
                            </div>

                            <div class="col-md-10 center-margin mrg5B">
                                <button 
                                    form="registerForm"
                                    :disabled="loginDisabled"
                                    type="submit"
                                    class="btn btn-sm btn-success btn-block">
                                    Register
                                </button>
                            </div>

                            <div class="col-md-10 center-margin mrg5B">
                                <button 
                                    v-on:click="toggleLoginAndRegister()"
                                    type="button"
                                    class="btn btn-sm btn-default btn-block">
                                    Go back to Login
                                </button>
                            </div>
                        </form>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  
</template>

<script>
import VeeValidate from 'vee-validate';
import axios from 'axios';

Vue.use(VeeValidate);

VeeValidate.Validator.extend( 'verifyPassword', {
   getMessage: field => `The password must contain at least: 1 uppercase letter, 1 lowercase letter, 1 number, and one special character (E.g. ! @ # $ etc)`,
   validate: value => {
      var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()])(?=.{8,})");
      return strongRegex.test(value);
   }
});

export default {
    data () {
        return {
            showLogin: true,
            showRegister: false,

            email: '',
            password: '',

            loginDisabled: false,
            register: {},

            alert: {
                success: false,
                fail: false,
                message: "",
                errors: {}
            }
        }
    },
    methods: {
        login(){
            this.$validator.validateAll('login').then((validate) => {
                if(validate){
                    this.$Progress.start();
                    var data = {
                        email   : this.email,
                        password   : this.password,
                    };
                    this.loginDisabled = true;
                    axios.post('authenticate', data)
                    .then((response) => {
                        console.log(response);
                        if(response.data.success === true){
                            this.$Progress.finish();
                            this.alert.success = true;
                            this.alert.fail = false;
                            this.alert.message = "Login Successful!";
                            window.location.href = 'to-dos';
                        }else{
                            this.alert.success = false;
                            this.alert.fail = true;
                            this.alert.message = "Email or Password is Incorrect!";
                            this.alert.errors = {};
                            this.loginDisabled = false;
                            this.$Progress.fail();                            
                        };                    
                    })
                    .catch( error => {
                        this.alert.success = false;
                        this.alert.fail = true;
                        this.alert.message = "Email or Password is Incorrect!";
                        this.alert.errors = {};
                        this.loginDisabled = false;
                        this.$Progress.fail();  
                    });
                }
            });            
        },
        toggleLoginAndRegister(){
            this.showLogin = !this.showLogin;
            this.showRegister = !this.showRegister;

            this.username = '';
            this.password = '';

            this.loginDisabled = false;
            this.register = {};

            this.alert = {
                success: false,
                fail: false,
                message: "",
                errors: {}
            };
        },
        registerUser(){
            this.$validator.validateAll('register').then((validate) => {
                if(validate){
                    this.loginDisabled = true;
                    this.$Progress.start();
                    axios.post('register-user', this.register).then(
                        (response) => {
                            if(response.data.success === true){
                                console.log(this.alert)
                                this.alert.success = true;
                                this.alert.fail = false;
                                this.alert.message = "User has been registered.";
                                this.$Progress.finish();
                            }else{
                                this.alert.success = false;
                                this.alert.fail = true;
                                this.alert.message = "";
                                this.alert.errors = response.data.errors;
                                this.loginDisabled = false;
                                this.$Progress.fail();
                            }                                
                        }, (error)  =>  {
                            console.log(error);
                            this.alert.success = false;
                            this.alert.fail = true;
                            this.alert.message = "Something went wrong, please try again!";
                            this.alert.errors = {};
                            this.loginDisabled = false;
                            this.$Progress.fail();
                        }
                    );
                }
            });            
        },
    },
}
</script>

