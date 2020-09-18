<template>
  <div id="app">
    <router-view :key="nowKey" />
  </div>
</template>

<script>
export default {
  name: "App",
  data() {
    return {};
  },
  computed: {
    nowKey() {
      return this.$route.name;
    }
  }
};
</script>

<style lang="scss"></style>
