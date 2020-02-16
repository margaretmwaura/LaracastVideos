<template>
    <ul class="pagination" v-if="shouldPaginate">
        <li v-show="prevUrl">
            <a class="page-link" href="#" aria-label="Previous" rel="prev" @click.prevent="page--">
                <span aria-hidden="true">&laquo; Previous</span>
            </a>
        </li>
        <li v-show="nextUrl">
            <a class="page-link" href="#" aria-label="Next" rel="next" @click.prevent="page++">
                <span aria-hidden="true">&raquo; Next</span>
            </a>
        </li>
    </ul>
</template>

<script>
    export default {
        name: "Paginator",
        props:['dataSet'],
        data()
        {
          return{
              page:1,
              prevUrl:false,
              nextUrl:false
          }
        },
        watch:{
            dataSet()
            {
                this.page = this.dataSet.current_page;
                this.prevUrl = this.dataSet.prev_page_url;
                this.nextUrl = this.dataSet.next_page_url;
            },
            page()
            {
                this.broadcast().updateUrl();
            }
        },
        computed:{
            shouldPaginate()
            {
                return !! this.prevUrl || !!this.nextUrl;
            },
            broadcast()
            {
               return this.$emit('changed',this.page);
            },
            updateUrl(){
                history.pushState(null, null, '?page=' + this.page);
            }
        }
    }
</script>

<style scoped>

</style>
