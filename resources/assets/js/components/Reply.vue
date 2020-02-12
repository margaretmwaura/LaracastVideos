<template>
    
</template>

<script>
    import favorite from "./Favorite";
    export default {
        name: "Reply",
        components:{favorite},
        props:['attributes'],
        data()
        {
            return{
                editting:false,
                body:this.attributes.body
            }
        },
        methods:{
            update(){

                axios.patch('/replies/' + this.attributes.id,{
                    body:this.body
                });

                this.editting = false;

                flash('Updated');
            },

            destroy()
            {
                axios.delete('/replies/' + this.attributes.id);
                $(this.$el).fadeOut(300, ()=>
                {
                    flash('Your reply has been deleted');
                });

            }
        }

    }
</script>

<style scoped>

</style>
