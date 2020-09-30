<template>
  <div>
    <div id="list">
      <van-list v-model="loading" :finished="true">
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
              >
                <van-icon v-if="index + 1 == 1" name="fire-o" color="white" />
                <van-icon
                  v-else-if="index + 1 == 2"
                  name="fire-o"
                  color="white"
                />
                <van-icon
                  v-else-if="index + 1 == 3"
                  name="fire-o"
                  color="white"
                />
                <span v-else>{{ index + 1 }}</span>
              </span>
            </van-col>
            <van-col span="8">
              <span>{{ item.nick }}</span>
            </van-col>
            <van-col span="8">
              <span>{{ item.credit }}</span>
            </van-col>
          </van-row>
        </div>
      </van-list>
    </div>
    <div id="my-score">
      <van-row id="my-van-row">
        <van-col span="8">
          <span
            id="my-id"
            v-bind:class="[
              [
                rank != 1
                  ? rank != 2
                    ? rank != 3
                      ? ''
                      : 'list-rd'
                    : 'list-nd'
                  : 'list-st'
              ]
            ]"
          >
            <van-icon v-if="rank == 1" name="fire-o" color="white" />
            <van-icon v-else-if="rank == 2" name="fire-o" color="white" />
            <van-icon v-else-if="rank == 3" name="fire-o" color="white" />
            <span v-else>{{ rank }}</span></span
          >
        </van-col>
        <van-col span="8">
          <span>{{ name }}</span>
        </van-col>
        <van-col span="8">
          <span>{{ credit }}</span>
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
        nick: rsa(this.name)
      }
    }).then(response => {
      let data = response.data;
      if (data.status == 200) {
        this.rank = data.rank;
        this.credit = data.credit;
      }
    });
    let values = {};
    values.begin = "0";
    values.num = num;
    values = JSON.stringify(values);
    Axios({
      method: "post",
      url: "/mooncake/php/ranklist.php",
      data: {
        values: rsa(values)
      }
    })
      .then(response => {
        let data = response.data;
        console.log(data);
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
      default: 10,
      type: Number
    }
  },
  data() {
    return {
      list: [],
      rank: "",
      credit: "",
      name: JSON.parse(localStorage.getItem("user")).nick,
      loading: false,
      finished: false
    };
  }
};
</script>

<style lang="scss" scoped>
#list {
  background-color: rgb(0, 0, 0, 0.5);
  padding-top: 10px;
  padding-bottom: 10px;
  border-radius: 1em 1em 0 0;
  width: 100%;
  margin: 0 auto;
  height: 22vh;
  max-height: calc(var(--vh, 1vh) * 22);
  overflow: scroll;
  color: white;
}

.list {
  margin-top: 6px;
  text-align: center;
}

.list-id {
  display: inline-block;
  background-color: grey;
  min-width: 6vw;
  height: 6vw;
  text-align: center;
  line-height: 7vw;
  border-radius: 1vw;
  // box-shadow: -0.5px -1px 3px #888888 inset;
}
.list-st {
  background-color: #f5ea4a;
  filter: brightness(1.1);
}
.list-nd {
  background-color: #9fb4b5;
  filter: brightness(1.1);
}
.list-rd {
  background-color: #e88600;
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
  background-color: rgb(0, 0, 0, 0.8);
  width: 80vw;
  height: 5vh;
  z-index: 0;
  border-radius: 0 0 1em 1em;
  color: white;
}

#my-van-row {
  color: #000000;
  line-height: 5vh;
  align-items: center;
  z-index: 3;
}
#my-id {
  display: inline-block;
  width: 6vw;
  height: 6vw;
  text-align: center;
  line-height: 7vw;
  border-radius: 1vw;
}
</style>
