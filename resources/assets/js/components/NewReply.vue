<template>
<!--    @if(auth()->check())-->

<!--    <form method="POST" action="{{ $thread->path().'/replies' }}">-->
<!--        {{ csrf_field() }}-->
    <div>
        <div v-if="signedIn">
            <div class="form-group">
            <textarea name="body" id="body"
                      class="form-control"
                      placeholder="Have something to say"
                      rows="5"
                      required
                      v-model="body">

            </textarea>
            </div>
            <button type="submit"
                    class="btn btn-default"
                    @click="addReply" >POST</button>
        </div>
        <div v-else>
            <p class="text-center">Please
                <a href="/login"> sign in </a> to participate in the forum</p>
        </div>


    </div>


</template>

<script>
    export default {
        name: "NewReply",
        props:['endpoint'],
        data()
        {
            return{
                body: ' ',
            }
        },
        computed:{
            signedIn()
            {
                return window.App.signedIn;
            }
        },
        methods:{
            addReply()
            {
                axios.post(this.endpoint , {body : this.body})
                .then(({data})=>{
                    this.body = ' ';
                    flash('Your reply has been posted ');
                    this.$emit('created',data);
                })
            }
        }
    }
</script>

<style scoped>

</style>
