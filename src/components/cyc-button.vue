<template>
  <div @click="clicked">
    <div class="others-content">
      <div class="icon">
        <van-icon size="5.7vw" :name="iconItem" />
      </div>
      <span class="others-span">{{ content }}</span>
    </div>
  </div>
</template>

<script>
export default {
  name: "cycButton",
  props: {
    content: {
      type: String,
      required: true
    },
    iconItem: {
      type: String,
      required: true
    },
    where: {
      type: String,
      required: true
    },
    isReplaced: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    clicked() {
      if (!this.isReplaced) {
        this.$router.push(this.where);
      } else {
        this.$router.go(parseInt(this.where));
      }
    }
  }
};
</script>

<style lang="scss" scoped>
$birthRed: #ef4971;
$sadBlue: #40eaff;
$bombYellow: #ecc02c;
$onechoBlue: #0d4f89;
$borderColor: #ffe9d1;

@mixin flex-normal($direct) {
  display: flex;
  flex-direction: $direct;
  justify-content: space-around;
  align-items: center;
}

.others-content {
  width: 15vw;
  height: 15vw;
  @include flex-normal(column);
  justify-content: center;
}

.icon {
  width: 6vw;
  height: 6vw;
  border: #ffffff 1.5px solid;
  background-color: aliceblue;
  box-shadow: 0 1px 3px #000000;
  font-size: 10px;
  padding: 5px;
  border-radius: 50%;
  text-align: center;
}
.others-span {
  font-size: 0.6em;
  margin-top: 5px;
  color: black;
}
</style>
