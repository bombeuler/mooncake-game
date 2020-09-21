<template>
  <div>
    <div id="container">
      <div id="infor" v-if="!signStatus" v-bind:class="[active == 0 ? 'signIn' : 'signUp']">
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
              <van-form @submit="onSignIn" label-width="30px" :show-error="false">
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
                  <van-button round block type="default" native-type="submit">登录</van-button>
                </div>
              </van-form>
            </div>
          </van-tab>
          <van-tab title="注册">
            <div id="signup">
              <van-form @submit="onSignUp" label-width="30px" :show-error="false">
                <van-field
                  v-model="stuNum"
                  name="uid"
                  label="学号"
                  placeholder="U202001010"
                  maxlength="10"
                  :rules="[
                    { required: true, pattern: uidPattern, message: '请输入正确学号' }
                  ]"
                />
                <van-field
                  v-model="realName"
                  name="name"
                  label="真名"
                  placeholder="阿尔托莉雅"
                  :rules="[{ required: true, message: '请填写真名' }]"
                />
                <van-field
                  v-model="tel"
                  name="tel"
                  type="tel"
                  label="电话"
                  maxlength="11"
                  placeholder
                  :rules="[{ required: true, pattern: phonePattern, message: '请填写电话' }]"
                />
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
                  <van-button round block type="default" native-type="submit">注册</van-button>
                </div>
              </van-form>
            </div>
          </van-tab>
        </van-tabs>
        <div style="text-align:center;font-size:12px;font-weight:400;margin-bottom:12px;">
          <a href="http://hustmaths.top" style="color: #000;">前往科协首页</a>
        </div>
      </div>
      <div v-if="signStatus" id="game-start">
        <van-button icon="play" type="default" text="开始游戏" round block @click="gameStart"></van-button>
        <div style="text-align:center;font-size:12px;font-weight:400;margin-top:12px;">
          <a href="http://hustmaths.top">前往科协首页</a>
        </div>
      </div>
      <div v-if="signStatus" id="others">
        <div id="others-box">
          <div id="intro" class="others-content" v-on:click="showIntro">
            <div class="icon-10">
              <van-icon size="5.7vw" name="newspaper-o" />
            </div>
            <span>游戏简介</span>
          </div>
          <div id="list" class="others-content" v-on:click="showList">
            <div class="icon-11">
              <van-icon size="7vw" name="bar-chart-o" />
            </div>
            <span>排行榜</span>
          </div>
          <div id="home" class="others-content" v-on:click="showInfor">
            <div class="icon-10">
              <van-icon size="5.7vw" name="home-o" />
            </div>
            <span>个人中心</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { Dialog } from "vant";
import Axios from "axios";
import md5 from "blueimp-md5";
import { Notify } from "vant";

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
      showError: false
    };
  },
  methods: {
    onSignIn(values) {
      values.password = md5(values.password);
      Axios({
        method: "post",
        url: "http://localhost/mooncake-game/php/signin",
        data: {
          values
        }
      }).then(response => {
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
            message: "登录成功"
          });
          sessionStorage.signStatus = 1;
          localStorage.nick = values.nick;
          localStorage.password = values.password;

          this.signStatus = sessionStorage.signStatus;
        }
      });

      // console.log("submit", values);
    },

    onSignUp(values) {
      values.password = md5(values.password);
      Axios({
        method: "post",
        url: "http://localhost/mooncake-game/php/signup",
        data: {
          values
        }
      })
        .then(response => {
          let data = response.data;
          // console.log(data);
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
      Dialog.alert({
        title: "游戏简介",
        message: "弹窗内容",
        theme: "round-button",
        className: ""
      });
    },
    showInfor() {
      this.$router.push("/infor");
    },
    showList() {
      this.$router.push("/list");
    },
    gameStart() {
      this.$router.push("/game");
    }
  },
  computed: {
    signAnimate: () => {
      return {
        signIn: this.active == 2,
        signUp: this.active == 1
      };
    }
  },
  created() {
    if (sessionStorage.signStatus == 1) {
      this.signStatus = sessionStorage.signStatus;
      return ;
    }
    if (localStorage.nick !== undefined) {
      let nick = localStorage.nick;
      let password = localStorage.password;
      let values = {
        nick,
        password
      };
      Axios({
        method: "post",
        url: "http://localhost/mooncake-game/php/signin",
        data: {
          values
        }
      }).then(response => {
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
            message: "登录成功"
          });
          sessionStorage.signStatus = 1;
          this.signStatus=sessionStorage.signStatus;
        }
      });
    }
    // console.log(localStorage);
  }
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
  background-color: rgb(168, 168, 168);
}
#infor {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  width: 90%;
  padding-top: 30px;
  border-radius: 30px;
  background-color: rgba(255, 255, 255, 0.8);
}
.signIn {
  top: 30%;
  animation: signIn 0.2s linear;
}
.signUp {
  top: 15%;
  animation: signUp 0.1s linear;
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

#others {
  position: absolute;
  width: 100%;
  height: 20vh;
  background-color: rgba(128, 128, 128, 0.9);
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
.others-content > span {
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
  position: absolute;
  top: 60%;
  left: 50%;
  transform: translateX(-50%);
  width: 40vw;
  font-weight: 700;
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
