<template>
    <div :id="'reply-'+id" class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+data.owner.name" v-text="data.owner.name">
                    </a>
                    said <span v-text="ago"></span>
                </h5>

                <div v-if="signedIn">
                    <favorite :reply="data"> </favorite>
                </div>

            </div>
        </div>
        <div class="panel-body">
            <div v-if="editting">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"> </textarea>
                </div>
                <button type="submit" class="btn btn-xs btn-primary" @click="update"> Update </button>
                <button type="submit" class="btn btn-xs btn-link" @click="editting=false"> Cancel </button>
            </div>
            <div v-else v-text="body">
            </div>
        </div>

        <div class="panel-footer level" v-if="canUpdate">
            <button type="submit" class="btn btn-xs mr-1" @click="editting=true">Edit</button>
            <button type="submit" class="btn btn-danger btn-xs mr-1" @click="destroy">Delete</button>
        </div>

    </div>
</template>

<script>
    import favorite from "./Favorite";
    import moment from 'moment';
    export default {
        name: "Reply",
        components:{favorite},
        props:['data'],
        data()
        {
            return{
                editting:false,
                id:this.data.id,
                body:this.data.body
            }
        },
        computed:{
            ago()
            {
                return moment(this.data.created_at).fromNow() + "...";
            },
            signedIn()
            {
                return window.App.signedIn;
            },
            canUpdate()
            {
                return this.authorize(user => this.data.user_id == user.id);
            }
        },
        methods:{
            update(){

                axios.patch('/replies/' + this.data.id,{
                    body:this.body
                });

                this.editting = false;

                flash('Updated');
            },

            destroy()
            {
                axios.delete('/replies/' + this.data.id);
                this.$emit('deleted',this.data.id);
            }
        }

    }
</script>

<style scoped>

</style>
