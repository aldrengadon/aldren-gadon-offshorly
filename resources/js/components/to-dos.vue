<template>
<div class="center-content row">
    <vue-progress-bar></vue-progress-bar>
    <div class="col-md-8 clearfix center-margin">
        <div class="row">
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
            <div class="col-md-12 mrg25T mrg25B" v-if="!viewToDo">
                <div class="content-box">
                    <h2 class="content-box-header content-box-header-alt bg-default font-size-23">
                        To do list:

                        <div class="pull-right">
                            <button
                                :disabled="loginDisabled"
                                v-on:click="viewToDo = !viewToDo"
                                type="button"
                                class="btn btn-sm btn-success">
                                Add new list
                            </button>

                            <button
                                v-on:click="logout()"
                                :disabled="loginDisabled"
                                type="button"
                                class="btn btn-sm btn-default">
                                Logout
                            </button>
                        </div>
                    </h2>
                    <div class="content-box-wrapper">
                        <table class="table table-hover table-condensed" cellspacing="0" style="margin-top:10px;">
                            <thead style="background-color: #f9fafe; color: #4b5056;">
                                <tr style="height: 30px;">
                                    <th style="font-weight: bold;">Title</th>
                                    <th style="font-weight: bold;">Number of Items</th>
                                    <th style="font-weight: bold;">Number of Items to do</th>
                                    <th style="font-weight: bold;">Date Created</th>
                                    <th style="font-weight: bold;">Date Updated</th>
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(value, key, index) in toDoList" :key="index"> 
                                    <td> {{ key }} </td>
                                    <td> {{ value.list.length }} </td>
                                    <td> {{ getDone(value.list) }} </td>
                                    <td> {{ value.created_at }} </td>
                                    <td> {{ value.updated_at }} </td>
                                    <td style="text-align: center;">
                                        <a :disabled="loginDisabled" class="btn btn-sm" v-on:click="viewToDos(key, value)" title="view to dos">
                                            <span class="glyph-icon icon-search"> </span>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>                                                
                    </div>
                </div>
            </div>


            <div class="col-md-12 mrg25T mrg25B" v-if="viewToDo">
                <div class="content-box">
                    <h2 class="content-box-header content-box-header-alt bg-default font-size-23">
                        Add New To do list:

                        <div class="pull-right">
                            <button
                                v-on:click="reload()"
                                type="button"
                                class="btn btn-sm btn-default">
                                Go Back
                            </button>
                            <button
                                :disabled="loginDisabled"
                                v-on:click="addTodo()"
                                type="button"
                                class="btn btn-sm btn-info">
                                Add to do
                            </button>
                            <button
                                v-on:click="saveTodos()"
                                :disabled="loginDisabled"
                                type="button"
                                class="btn btn-sm btn-success">
                                Save
                            </button>                            
                        </div>
                    </h2>
                    <div class="content-box-wrapper">
                        <form class="mrg20B mrg10T" method="post" data-vv-scope="addlist">
                            <div class="form-group col-md-10 center-margin" :class="{'has-error': errors.has('addlist.Title') }">
                                <label class="control-label">
                                    Title <i class="font-red">*</i>
                                </label>
                                <textarea
                                    :disabled="loginDisabled"
                                    name="Title"
                                    class="form-control"
                                    data-vv-validate-on="none"
                                    v-validate="'required'"
                                    v-model="title"
                                    maxlength="256"
                                    ></textarea>
                                <span v-if="errors.has('addlist.Title')" class="font-red font-size-11">
                                    {{ errors.first('addlist.Title') }}
                                </span>
                            </div>

                            <hr class="col-md-10 center-margin">                            

                            <div v-for="(value, index) in todos" :key="index" 
                                class="form-group col-md-10 center-margin" :class="{'has-error': errors.has('addlist.To do #'+(index+1))}">

                                <label class="control-label pull-left" :for="'verify-database'+index">
                                    <input class="" type="checkbox"
                                        name="database verification"                                      
                                        v-model="value.is_done"
                                        :true-value="1"
                                        :false-value="0">
                                </label>
                                <label class="control-label" :id="index">
                                    {{"To do #"+(index+1)}} <i class="font-red">*</i>
                                </label>
                                <input
                                    :disabled="loginDisabled"
                                    type="text"
                                    class="form-control"                                    
                                    :name="'To do #'+(index+1)"
                                    data-vv-validate-on="none"
                                    v-validate="'required'"
                                    v-model="value.todo"
                                    maxlength="256">
                                <span v-if="errors.has('addlist.To do #'+(index+1))" class="font-red font-size-11">
                                    {{ errors.first('addlist.To do #'+(index+1))}}
                                </span>
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

export default {
    data () {
        return {
            toDoList: toDoList,
            viewToDo: false,
            id: 0,
            title: "",
            todos: [],
            loginDisabled: false,
            alert: {
                success: false,
                fail: false,
                message: "",
                errors: {}
            },
            update: false
        }
    },
    computed: {
        todosIndex(){
            return this.todos.length;
        }
    },
    methods: {
        reload(){
            location.reload();
        },
        getDone(todos){
            return todos.filter(function(todo){
                return todo.is_done == 1;
            }).length;
        },
        addTodo(){
            this.$validator.validateAll('addlist').then((validate) => {
                if(validate){
                    this.todos.push({ 
                        todo: ""
                    });
                    this.$nextTick(function() {
                        var element = document.getElementById(this.todosIndex - 1);
                        element.scrollIntoView({ behavior: 'smooth' });
                    });
                }
            });
        },
        registerTodos(){
            this.$validator.validateAll('addlist').then((validate) => {
                if(validate){
                    this.$Progress.start();
                    var data = {
                        title   : this.title,
                        todos   : this.todos,
                    };
                    this.loginDisabled = true;
                    axios.post('register-to-dos', data)
                    .then((response) => {
                        if(response.data.success === true){
                            this.$Progress.finish();
                            this.alert.success = true;
                            this.alert.fail = false;
                            this.alert.message = "Saved!";
                        }else{
                            this.alert.success = false;
                            this.alert.fail = true;
                            this.alert.message = "Something went wrong please try again!";
                            this.alert.errors = {};
                            this.loginDisabled = false;
                            this.$Progress.fail();                            
                        };                    
                    })
                    .catch( error => {
                        this.alert.success = false;
                        this.alert.fail = true;
                        this.alert.message = "Something went wrong please try again!";
                        this.alert.errors = {};
                        this.loginDisabled = false;
                        this.$Progress.fail();  
                    });
                }
            });
        },
        viewToDos(title, toDos){
            this.update = true;
            this.viewToDo = !this.viewToDo;
            this.title = title;
            this.id = toDos.id;
            for(var i in toDos.list)
                this.todos.push({
                    id: toDos.list[i].id,
                    todo: toDos.list[i].todo,
                    is_done: toDos.list[i].is_done
                });
        },
        updateToDos(){
            this.$validator.validateAll('addlist').then((validate) => {
                if(validate){
                    this.$Progress.start();
                    var data = {
                        id   : this.id,
                        title: this.title,
                        todos: this.todos,
                    };
                    this.loginDisabled = true;
                    axios.post('register-to-dos', data)
                    .then((response) => {
                        if(response.data.success === true){
                            this.$Progress.finish();
                            this.alert.success = true;
                            this.alert.fail = false;
                            this.alert.message = "Saved!";
                        }else{
                            this.alert.success = false;
                            this.alert.fail = true;
                            this.alert.message = "Something went wrong please try again!";
                            this.alert.errors = {};
                            this.loginDisabled = false;
                            this.$Progress.fail();                            
                        };                    
                    })
                    .catch( error => {
                        this.alert.success = false;
                        this.alert.fail = true;
                        this.alert.message = "Something went wrong please try again!";
                        this.alert.errors = {};
                        this.loginDisabled = false;
                        this.$Progress.fail();  
                    });
                }
            });
        },
        saveTodos(){
            if(this.update)
                this.updateToDos();
            else
                this.registerTodos();
        },
        logout(){
            window.location.href = 'logout';
        }
    },
}
</script>