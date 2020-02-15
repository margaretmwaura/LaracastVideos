<template>
<div>
    <div v-for="(reply , index )  in items">
        <Reply :data="reply" @deleted="remove(index)"> </Reply>
    </div>

    <new-reply @created="add" :endpoint="endpoint"> </new-reply>
</div>

</template>

<script>
    import Reply from "./Reply";
    import NewReply from "./NewReply";
    export default {
        name: "Replies",
        props:['data'],
        components:{
            NewReply,
            Reply,
        },
        data()
        {
            return{
                items:this.data,
                endpoint:location.pathname + '/replies',
            }
        },
        methods:{
            add(reply)
            {
              this.items.push(reply);
                this.$emit('added');
            },
            remove(index)
            {
                this.items.splice(index, 1);

                this.$emit('removed');
                flash('Reply was deleted');
            }
        }
    }
</script>

<style scoped>

</style>
