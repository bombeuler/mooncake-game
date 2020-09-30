<template>
  <div>
    <div id="list">
      <van-list
        v-model="loading"
        :finished="finished"
        finished-text="---010---"
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
</template>

<script>
import Axios from "axios";
import rsa from "../untils/rsa";

export default {
  name: "whoWin",
  created() {
    const num = this.length;
    Axios({
      method: "post",
      url: "/mooncake/php/infor.php",
      data: {
        nick: rsa(this.name),
        num
      }
    }).then(response => {
      let data = response.data;
      if (data.status == 200) {
        this.rank = data.rank;
        this.intergral = data.intergral;
      }
    });
    Axios({
      method: "post",
      url: "/mooncake/php/ranklist.php",
      data: {
        begin: rsa("0")
      }
    })
      .then(response => {
        let data = response.data;
        if (data.status === 404) {
          this.finished = true;
          return;
        } else if (data.status === 200) {
          this.list = data.res;
        }
      })
      .catch(function(error) {
        console.log(error);
      });
  },
  props: {
    length: {
      default: 5,
      type: Number
    }
  },
  data() {
    return {
      list: [],
      rank: "",
      intergral: "",
      name: JSON.parse(localStorage.getItem("user")).nick,
      loading: false,
      finished: false
    };
  }
};
</script>

<style lang="scss" scoped>
#list {
  background-color: rgb(255, 255, 255, 0.5);
  width: 80vw;
  margin: 0 auto;
  height: auto;
  overflow: scroll;
}

#title {
  border-radius: 20px 20px 0 0;
  background-color: whitesmoke;
  width: 80vw;
  margin: 0 auto;
  margin-top: 10vh;
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
  position: fixed;
  background-color: rgb(255, 255, 255, 0.8);
  width: 80vw;
  height: 5vh;
  z-index: 0;
}

#my-van-row {
  color: #000000;
  line-height: 5vh;
  align-items: center;
  z-index: 3;
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
  margin-left: 15vw;
  margin-top: 5vw;
}
</style>
