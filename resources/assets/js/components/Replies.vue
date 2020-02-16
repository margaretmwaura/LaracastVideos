<template>
<div>
    <div v-for="(reply , index )  in items" :key="reply.id">
        <Reply :data="reply" @deleted="remove(index)"> </Reply>
    </div>

    <paginator :dataSet="dataSet" @changed="fetch"> </paginator>
    <new-reply @created="add" :endpoint="endpoint"> </new-reply>
</div>

</template>

<script>
    import Reply from "./Reply";
    import NewReply from "./NewReply";
    import Collection from "../mixins/Collection";
    export default {
        name: "Replies",
        mixins:[Collection],
        components:{
            NewReply,
            Reply,
        },
        data()
        {
            return{
                dataSet:false,
            }
        },
        created()
        {
          this.fetch();
        },
        methods:{
            fetch(page)
            {
                console.log(location.pathname + '/replies');
                axios.get(this.url(page)).then(this.refresh);
            },
            url(page)
            {
                if(!page)
                {
                  let query = location.search.match(/page=(\d+)/);

                  page = query ? query[1] : 1;
                }
               return location.pathname + '/replies?page=' + page;
            },
            refresh({data})
            {
                this.dataSet = data;
                this.items = data.data;

                window.scrollTo(0, 0);
            },

        }
    }
</script>

<style scoped>

</style>
