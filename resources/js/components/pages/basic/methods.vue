<template>
  <div>
    <h1>the views is : {{ views }}</h1>
    <button @click="updateViews(1)">Increase</button>
    <button @click="updateViews(-1)">Decrease</button>
    
    <br>
    <br>
    
    <div v-for="(blog, i) in blogs" :key="i" v-if="blogs.length">
        <h1>{{ blog.title }}</h1>
        <p>{{ blog.post }}</p>
    </div>

    <br>
    <br>
    <br>

    <div >
        <h1 v-if="showItem">show this item if "showItem" is true</h1>
        <h1 v-else>show this item if "showItem" is false</h1>
    </div>
    <button @click="showItem=!showItem">Change "showItem"</button>

  </div>
</template>

<script>
export default {
  data() {
    return {
      views: 0,
      blogs: [],
      showItem: false,
    };
  },
  methods: {
    updateViews(number) {
      this.views += number;
    },
  },
  created() {
    // call the server to get the views of the blog post...
    this.views = 100;
    //get the blog posts
    let posts = [
      {
        title: "blog 1",
        post: "this is the first blog post",
        id: 1,
      },
      {
        title: "blog 2",
        post: "this is the second blog post",
        id: 2,
      },
      {
        title: "blog 3",
        post: "this is the third blog post",
        id: 3,
      },
      {
        title: "blog 4",
        post: "this is the forth blog post",
        id: 4,
      },
    ];
    this.blogs = posts;
  },
};
</script>