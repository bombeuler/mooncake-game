<template>
  <div>
    <div id="list">
      <div id="title">排行榜</div>
      <van-list
        v-model="loading"
        :finished="finished"
        finished-text="---010---"
        @load="onLoad"
      >
        <li class="list" v-for="(item, index) in list" :key="index">
          <van-row>
            <van-col span="8">
              <span
                class="list-id"
                v-bind:class="[
                  [
                    index + 1 != 1
                      ? index + 1 != 2
                        ? index + 1 != 3
                          ? ''
                          : 'list-rd'
                        : 'list-nd'
                      : 'list-st'
                  ]
                ]"
                >{{ index + 1 }}</span
              >
            </van-col>
            <van-col span="8">
              <span>{{ item.name }}</span>
            </van-col>
            <van-col span="8">
              <span>{{ item.score }}</span>
              <span>
                <van-icon v-if="index + 1 == 1" name="award" color="yellow" />
                <van-icon v-if="index + 1 == 2" name="award" color="blue" />
                <van-icon v-if="index + 1 == 3" name="award" color="red" />
              </span>
            </van-col>
          </van-row>
        </li>
      </van-list>
    </div>
    <div id="my-score">
      <div></div>
      <van-row id="my-van-row">
        <van-col span="8">
          <span id="my-id">{{ 1 }}</span>
        </van-col>
        <van-col span="8">
          <span>{{ "cyc" }}</span>
        </van-col>
        <van-col span="8">
          <span>{{ "9999" }}</span>
        </van-col>
      </van-row>
    </div>
    <div id="arrow-back" v-on:click="backToHome">
      <van-icon name="arrow-left" size="20" />
    </div>
  </div>
</template>

<script>
import Axios from "axios";

export default {
  name: "List",
  data() {
    return {
      list: [],
      loading: false,
      finished: false
    };
  },
  methods: {
    onLoad() {
      //获取数据
      Axios.post("/php/list")
        .then(response => {
          let data = response.data;
          // console.log(data);
          this.list = this.list.concat(data);
        })
        .catch(function(error) {
          console.log(error);
        });
      //渲染列表
      setTimeout(() => {
        this.loading = false;

        if (this.list.length >= 100) {
          this.finished = true;
        }
      }, 3000);
    },
    backToHome() {
      this.$router.push("/home");
    }
  }
};
</script>

<style lang="scss" scoped>
@import url("../style/var.scss");
* {
  list-style: none;
}
#list {
  // background-color: grey;
  width: 80vw;
  margin: 0 auto;
  margin-top: 10vh;
  height: 65vh;
  overflow: scroll;
}
#title {
  background-color: whitesmoke;
  text-align: center;
  padding: 5px 0;
}
.list {
  margin-top: 6px;
  text-align: center;
}
.list-id {
  display: inline-block;
  background-color: grey;
  filter: brightness(1.1);
  min-width: 6vw;
  height: 6vw;
  text-align: center;
  line-height: 7vw;
  border-radius: 1vw;
  box-shadow: -0.5px -1px 3px #888888 inset;
}
.list-st {
  background-color: rgb(255, 255, 0);
  filter: brightness(1.1);
}
.list-nd {
  background-color: rgb(0, 0, 255);
  filter: brightness(1.1);
}
.list-rd {
  background-color: rgb(255, 0, 0);
  filter: brightness(1.1);
}

#my-score {
  background-color: transparent;
  width: 80vw;
  height: 5vh;
  margin: 0 auto;
  z-index: 1;
  text-align: center;
}
#my-score > div:nth-child(1) {
  position: fixed;
  background-color: rgb(218, 214, 214);
  opacity: 0.5;
  width: 80vw;
  height: 5vh;
  z-index: 0;
}

#my-van-row {
  line-height: 5vh;
  align-items: center;
}
#my-id {
  display: inline-block;
  background-color: rgb(255, 255, 0);
  filter: brightness(1.1);
  width: 6vw;
  height: 6vw;
  text-align: center;
  line-height: 7vw;
  border-radius: 1vw;
  box-shadow: -0.5px -1px 3px #888888 inset;
}

#arrow-back {
  background-color: grey;
  width: 11vw;
  height: 11vw;
  text-align: center;
  line-height: 13.3vw;
  border-radius: 50%;
  margin-left: 15vw;
  margin-top: 5vw;
}
</style>
