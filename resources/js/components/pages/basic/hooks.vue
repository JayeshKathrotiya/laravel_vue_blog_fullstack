<template>
    <div>
        <h1 ref="myref">Hook tutorial</h1>
        <p>my name is : {{ name }}</p>
    </div>
</template>

<script>
export default {
    data(){
        return {
            name: 'jayesh'
        }
    },

    beforeCreate(){
        console.log('beforeCreate', this.name);
    },
    created(){
        // call initial data from it...
        console.log('data and other function is available but not html:', this.name)        
    },
    mounted(){
        console.log('data and other function is available and html is available:', this.name, this.$refs.myref)
    },
}
</script>