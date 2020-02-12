<template>
                  <button type="submit" class="btn btn-default" @click="toggle" :class="classes">
                      <span class="glyphicon glyphicon-heart"> </span>
                      <span v-text="favoritesCount"> </span>
                  </button>
</template>

<script>
    export default {
        name: "Favorite",
        props:['reply'],
        data()
        {
            return{
                favoritesCount:this.reply.favoritesCount,
                isFavorited:this.reply.isFavorited,
            }
        },
        computed: {
            classes()
            {
               return ['btn' , this.isFavorited ? 'btn-primary' : 'btn-default'];
            },
            endpoint()
            {
                return '/replies/' + this.reply.id + '/favorites';
            }
        },
        methods: {
          toggle()
            {
                if(this.isFavorited)
                {
                    this.destroy();

                }
                else
                {
                    this.create();

                }
            },
            create()
            {
                axios.post(this.endpoint);
                this.favoritesCount++;
                this.isFavorited = true;
            },
            destroy()
            {
                axios.delete(this.endpoint);
                this.favoritesCount--;
                this.isFavorited = false;
            }
        }

    }
</script>

<style scoped>

</style>
