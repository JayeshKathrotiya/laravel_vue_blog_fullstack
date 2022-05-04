<template>
  <div>
    <div class="content">
      <div class="container-fluid">
        <h1>this is use comp</h1>
        <p>current state is : {{ counter }}</p>
        <comp-a />
        <comp-b />
        <comp-c />
        <button type="info" @click="changeCounter">change counter</button>
      </div>
    </div>
  </div>
</template>

<script>
import compA from "./compA.vue";
import compB from "./compB.vue";
import compC from "./compC.vue";
import { mapGetters, mapActions } from "vuex";

export default {
  //   data() {},
  created() {
    // console.log(this.$store);
  },
  computed: {
    ...mapGetters({
      counter: "getCounter",
    }),
  },
  methods: {
    ...mapActions({
      changeCounter1: "changeCounterAction",
    }),
    changeCounter() {
        this.changeCounter1(1)
    //   this.$store.dispatch("changeCounterAction", 1);
      //   this.$store.commit("changeCounter", 1);
    },
  },
  components: {
    compA,
    compB,
    compC,
  },
};
</script>