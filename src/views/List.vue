<template>
  <div id="bg">
    <div class="whole-list">
      <div id="title">排行榜</div>
      <div id="list">
        <van-list
          v-model="loading"
          :finished="finished"
          finished-text="---010---"
          @load="onLoad"
        >
          <div class="list" v-for="(item, index) in list" :key="index">
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
                <span>{{ item.nick }}</span>
              </van-col>
              <van-col span="8">
                <span>{{ item.intergral }}</span>
                <span>
                  <van-icon v-if="index + 1 == 1" name="award" color="yellow" />
                  <van-icon v-if="index + 1 == 2" name="award" color="blue" />
                  <van-icon v-if="index + 1 == 3" name="award" color="red" />
                </span>
              </van-col>
            </van-row>
          </div>
        </van-list>
      </div>
      <div id="my-score">
        <van-row id="my-van-row">
          <van-col span="8">
            <span id="my-id">{{ rank }}</span>
          </van-col>
          <van-col span="8">
            <span>{{ name }}</span>
          </van-col>
          <van-col span="8">
            <span>{{ intergral }}</span>
          </van-col>
        </van-row>
      </div>
    </div>
    <div id="arrow-back" v-on:click="backToHome">
      <van-icon name="arrow-left" size="20" />
    </div>
  </div>
</template>

<script>
import Axios from "axios";
import rsa from "../untils/rsa";

export default {
  name: "List",
  data() {
    return {
      list: [],
      rank: "",
      intergral: "",
      name: JSON.parse(localStorage.getItem("user")).nick,
      begin: "0",
      loading: false,
      finished: false
    };
  },
  methods: {
    onLoad() {
      //获取数据
      console.log(this.begin);
      Axios({
        method: "post",
        url: "/mooncake/php/ranklist.php",
        data: {
          begin: rsa(this.begin)
        }
      })
        .then(response => {
          let data = response.data;
          console.log(data);
          if (data.status === 404) {
            this.finished = true;
            return;
          } else if (data.status === 200) {
            this.list = this.list.concat(data.res);
            this.begin = this.list.length.toString();
          }
        })
        .catch(function(error) {
          console.log(error);
        });
      //渲染列表
      setTimeout(() => {
        this.loading = false;
      }, 1000);
    },
    backToHome() {
      this.$router.push("/home");
    }
  },
  created() {
    Axios({
      method: "post",
      url: "/mooncake/php/infor.php",
      data: {
        nick: rsa(this.name)
      }
    }).then(response => {
      let data = response.data;
      console.log(data);
      if (data.status == 200) {
        this.rank = data.rank;
        this.intergral = data.intergral;
      }
    });
  },
  mounted() {
    // First we get the viewport height and we multiple it by 1% to get a value for a vh unit
    let vh = window.innerHeight * 0.01;
    // Then we set the value in the --vh custom property to the root of the document
    document.documentElement.style.setProperty("--vh", `${vh}px`);

    // We listen to the resize event
    window.addEventListener("resize", () => {
      // We execute the same script as before
      let vh = window.innerHeight * 0.01;
      console.log(vh);
      document.documentElement.style.setProperty("--vh", `${vh}px`);
    });
  }
};
</script>

<style lang="scss" scoped>
#bg {
  display: flex;
  flex-direction: column;
  justify-content: center;
  height: 100vh;
  height: calc(var(--vh, 1vh) * 100);
  width: 100vw;
  background: #ffffff url("../../public/bg.png") no-repeat fixed right;
  background-size: cover;
}
.whole-list {
  display: flex;
  flex-direction: column;
}
#list {
  background-color: rgb(255, 255, 255, 0.5);
  width: 80vw;
  margin: 0 auto;
  height: 55vh;
  height: calc(var(--vh, 1vh) * 55);
  overflow: scroll;
}

#title {
  border-radius: 20px 20px 0 0;
  background-color: whitesmoke;
  width: 80vw;
  margin: 0 auto;
  // margin-top: 10vh;
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
  text-align: center;
}
#my-score > div:nth-child(1) {
  background-color: rgb(255, 255, 255, 0.7);
  width: 80vw;
  height: 5vh;
  z-index: 0;
  border-radius: 0 0 20px 20px;
}

#my-van-row {
  color: #000000;
  line-height: 5vh;
  align-items: center;
  z-index: 3;
}
#my-id {
  display: inline-block;
  background-color: wheat;
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
