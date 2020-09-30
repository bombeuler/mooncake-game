<template>
  <div>
    <div id="container">
      <div id="infor" v-if="!signStatus">
        <van-tabs
          v-model="active"
          background="rgba(255, 255, 255,.1)"
          swipeable
          animated
          color="grey"
          title-active-color="black"
        >
          <van-tab title="登录">
            <div id="signin">
              <van-form
                @submit="onSignIn"
                label-width="30px"
                :show-error="false"
              >
                <van-field
                  v-model="username"
                  name="nick"
                  label="昵称"
                  placeholder
                  :rules="[{ required: true, message: '请填写昵称' }]"
                />
                <van-field
                  v-model="password"
                  type="password"
                  name="password"
                  label="密码"
                  placeholder
                  :rules="[{ required: true, message: '请填写密码' }]"
                />
                <div style="margin: 16px;">
                  <van-button round block type="default" native-type="submit"
                    >登录</van-button
                  >
                </div>
              </van-form>
            </div>
          </van-tab>
          <van-tab title="注册">
            <div id="signup">
              <van-form
                @submit="onSignUp"
                label-width="30px"
                :show-error="false"
              >
                <van-field
                  v-model="username"
                  name="nick"
                  label="昵称"
                  placeholder="可可爱爱的pp"
                  :rules="[{ required: true, message: '请填写昵称' }]"
                />
                <van-field
                  v-model="password"
                  type="password"
                  name="password"
                  label="密码"
                  placeholder
                  :rules="[{ required: true, message: '请填写密码' }]"
                />
                <div style="margin: 16px;">
                  <van-button round block type="default" native-type="submit"
                    >注册</van-button
                  >
                </div>
              </van-form>
            </div>
          </van-tab>
        </van-tabs>
        <div
          style="text-align:center;font-size:12px;font-weight:400;margin-bottom:12px;"
        >
          <a href="http://hustmaths.top" style="color: #000;">前往科协首页</a>
        </div>
      </div>
      <div class="gg">
        <div v-if="signStatus" id="game-start">
          <van-button
            icon="play"
            type="default"
            text="开始游戏"
            round
            block
            @click="gameStart"
          ></van-button>
          <div
            style="text-align:center;font-size:12px;font-weight:400;margin-top:12px;"
          >
            <a href="http://hustmaths.top">前往科协首页</a>
          </div>
        </div>
        <div v-if="signStatus" id="others">
          <div id="others-box">
            <div id="intro" class="others-content" v-on:click="showIntro">
              <div class="icon-10">
                <van-icon size="5.7vw" name="newspaper-o" />
              </div>
              <span class="others-span">游戏简介</span>
            </div>
            <div id="list" class="others-content" v-on:click="showList">
              <div class="icon-11">
                <van-icon size="7vw" name="bar-chart-o" />
              </div>
              <span class="others-span">喂食榜</span>
            </div>
            <div id="home" class="others-content" v-on:click="showInfor">
              <div class="icon-10">
                <van-icon size="5.7vw" name="home-o" />
              </div>
              <span class="others-span">个人中心</span>
            </div>
          </div>
        </div>
      </div>
      <van-dialog v-model="show" title="游戏简介" confirm-button-color="#000">
        <div class="intro">
          <div class="dd">
            <div class="cc">游戏背景</div>
            <div class="bb">
              万能的pp快要考试了却还没有复习。为了拯救他(实则拿奖品)，你需要制作记忆月饼喂食pp让他拥有一个满意的分数。
            </div>
          </div>
          <div class="dd">
            <div class="cc">基本玩法</div>
            <div class="bb">
              一份记忆月饼需要1份面，若干份烧糖和相应科目书本的一页为原料。
            </div>
            <div class="bb">
              靠点击收集到足够的原料后，点击pp即可以给pp喂食记忆月饼并令pp的相应科目加10分，pp所有科目分数满足期望值时即成功。
            </div>
            <div class="bb">超时会失去1点生命值，生命值为0时游戏结束。</div>
          </div>
          <div class="dd">
            <div class="cc">分数机制</div>
            <div class="bb">
              每帮pp通过一次考试可以获得一定积分，在一定时间内完成会有额外分。
            </div>
          </div>
          <div class="dd">
            <div class="cc">特殊机制</div>
            <div class="bb">
              一次最多可收集30份面，30烧糖和5页书。超过限额则不可收集。有一定几率会收集到发霉的书页导致pp食物中毒。
            </div>
            <div class="bb">
              为了保护pp，如果当前收集了发霉的书页或者收集的书页对应不上pp当前要考的科目，需点击垃圾桶清空之前收集到的书页。
            </div>
          </div>
        </div>
      </van-dialog>
    </div>
  </div>
</template>
<script>
import Axios from "axios";
import md5 from "blueimp-md5";
import { Notify } from "vant";
import rsa from "../untils/rsa";

export default {
  name: "Home",
  data() {
    return {
      username: "",
      password: "",
      stuNum: "",
      realName: "",
      tel: "",
      uidPattern: /U\d{9,}/,
      phonePattern: /^(13[0-9]|14[5|7]|15[0|1|2|3|4|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\d{8}$/,
      active: 0,
      signStatus: 0,
      showError: false,
      show: false,
    };
  },
  methods: {
    onSignIn(values) {
      values.password = md5(values.password);
      Axios({
        method: "post",
        url: "/mooncake/php/signin.php",
        data: {
          values: rsa(JSON.stringify(values)),
        },
      }).then((response) => {
        let data = response.data;
        console.log(response, data);
        if (data.status !== 200) {
          Notify(data.msg);
          if (data.msg === "该用户不存在") {
            this.active = 1;
          }
        } else {
          Notify({
            type: "success",
            message: "登录成功",
          });
          sessionStorage.signStatus = 1;
          localStorage.setItem(
            "user",
            JSON.stringify({
              nick: values.nick,
              password: values.password,
            })
          );

          this.signStatus = sessionStorage.signStatus;
        }
      });

      // console.log("submit", values);
    },

    onSignUp(values) {
      values.password = md5(values.password);
      values = JSON.stringify(values);
      console.log(values);
      values = rsa(values);
      Axios({
        method: "post",
        url: "/mooncake/php/signup.php",
        data: {
          values,
        },
      })
        .then((response) => {
          let data = response.data;
          console.log(data);
          if (data.status !== 200) {
            Notify(data.msg);
            return;
          } else {
            Notify({ type: "success", message: "注册成功" });
            this.active = 0;
          }
        })
        .catch(function(error) {
          console.log(error);
        });
      // console.log("submit", values);
    },
    showIntro() {
      this.show = true;
    },
    showInfor() {
      this.$router.push("/infor");
    },
    showList() {
      this.$router.push("/list");
    },
    gameStart() {
      this.$router.push("/game");
    },
  },
  computed: {
    signAnimate: () => {
      return {
        signIn: this.active == 2,
        signUp: this.active == 1,
      };
    },
  },
  created() {
    if (sessionStorage.signStatus == 1) {
      this.signStatus = sessionStorage.signStatus;
      return;
    }
    if (localStorage.getItem("user")) {
      const user = JSON.parse(localStorage.getItem("user"));
      const nick = user.nick;
      const password = user.password;
      const values = JSON.stringify({
        nick,
        password,
      });
      Axios({
        method: "post",
        url: "/mooncake/php/signin.php",
        data: {
          values: rsa(values),
        },
      }).then((response) => {
        let data = response.data;
        // console.log(response, data);
        if (data.status !== 200) {
          Notify(data.msg);
          if (data.msg === "该用户不存在") {
            this.active = 1;
          }
        } else {
          Notify({
            type: "success",
            message: "登录成功",
          });
          sessionStorage.signStatus = 1;
          this.signStatus = sessionStorage.signStatus;
        }
      });
    }
    // console.log(localStorage);
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
  },
};
</script>
<style lang="scss" scoped>
a {
  color: #fff;
}
.van-field__label {
  color: white !important;
}

#container {
  background: #00ff00 url("../../public/bg.png") no-repeat fixed right;
  background-size: cover;
  height: 100vh;
  height: calc(var(--vh, 1vh) * 100);
  background-color: rgb(168, 168, 168);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-end;
}
#infor {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  width: 90%;
  top: 30%;
  padding-top: 30px;
  border-radius: 30px;
  background-color: rgba(255, 255, 255, 0.8);
}
#signin,
#signup {
  margin: 0 auto;
  margin-top: 5px;
  width: 90%;
  color: white !important;
}
.van-cell {
  color: #ffffff !important;
  background-color: rgba(255, 255, 255, 0.1);
  // background-color: grey;
}

.gg {
  width: 100vw;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: 40vh;
  align-items: center;
}
#others {
  // position: absolute;
  width: 100%;
  height: 20vh;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 2;
  bottom: 0;
  border-radius: 20px 20px 0 0;
}
#others-box {
  width: 80vw;
  margin: 0 auto;
  display: flex;
  flex-direction: row;
  justify-content: space-around;
}
.others-content {
  width: 15vw;
  margin-top: 30px;
  font-size: 10px;
  text-align: center;
}
.others-span {
  display: inline-block;
  margin-top: 5px;
  color: #ffffff;
}
.icon-10,
.icon-11 {
  margin: 0 auto;
  box-sizing: border-box;
  border-radius: 50%;
  border: #ffffff 1.5px solid;
  text-align: center;
  vertical-align: middle;
  background-color: aliceblue;
  box-shadow: 0 1px 3px #000000;
}
.icon-10 {
  width: 10vw;
  padding-top: 2vw;
  height: 10vw;
}
.icon-11 {
  width: 11vw;
  padding-top: 1.5vw;
  height: 11vw;
}
.icon-10:active,
.icon-11:active {
  background-color: gray;
  border: gray 1.5px solid;
}
#game-start {
  width: 40vw;
  font-weight: 700;
}

.intro {
  width: 90%;
  margin: 0 auto;
  font-size: 14px;
  display: flex;
  flex-direction: column;
}
.aa {
  font-size: 12px;
  text-indent: 24px;
}
.bb {
  font-size: 12px;
  width: 95%;
  align-self: center;
  text-indent: 24px;
}
.cc {
  font-size: 13px;
  font-weight: bold;
  margin-bottom: 2px;
}
.dd {
  margin-top: 4px;
  margin-bottom: 6px;
}
@keyframes signIn {
  from {
    top: 15%;
  }
  to {
    top: 30%;
  }
}
@keyframes signUp {
  from {
    top: 30%;
  }
  to {
    top: 15%;
  }
}
</style>
