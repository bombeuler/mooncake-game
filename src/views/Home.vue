<template>
  <div id="container">
    <div id="infor" v-if="!signStatus" v-bind:class="[active==0 ?'signIn':'signUp']">
      <van-tabs
        v-model="active"
        background="grey"
        swipeable
        animated
        color="white"
        title-active-color="white"
        @change="onChange"
      >
        <van-tab title="登录">
          <div id="signin">
            <van-form @submit="onSignIn" label-width="30px" :show-error="false">
              <van-field
                v-model="username"
                name="name"
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
                name="stuNum"
                label="学号"
                placeholder="U202001010"
                :rules="[{ required: true,pattern , message: '请输入正确学号' }]"
              />
              <van-field
                v-model="realName"
                name="real-name"
                label="真名"
                placeholder="阿尔托莉雅"
                :rules="[{ required: true, message: '请填写真名' }]"
              />
              <van-field
                v-model="tel"
                name="tel"
                type="tel"
                label="电话"
                placeholder
                :rules="[{ required: true, message: '请填写电话' }]"
              />
              <van-field
                v-model="username"
                name="name"
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
      <div style="text-align:center;font-size:12px;font-weight:400;margin-top:12px;">
        <a href="http://hustmaths.top">前往科协首页</a>
      </div>
    </div>
    <div v-if="signStatus" id="game-start">
      <van-button icon="play" type="default" text="开始游戏" round block></van-button>
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
</template>
<script>
import { Dialog } from "vant";
export default {
  name: "Home",
  data() {
    return {
      username: "",
      password: "",
      stuNum: "",
      realName: "",
      tel: "",
      pattern: /U\d{9,}/,
      active: 0,
      signStatus: 1,
      showError: false,
    };
  },
  methods: {
    onSignIn(values) {
      console.log("submit", values);
      this.signStatus = 1;
    },
    onSignUp(values) {
      console.log("submit", values);
    },
    onChange(values) {
      console.log(values, this.active);
    },
    showIntro() {
      Dialog.alert({
        title: "游戏简介",
        message: "弹窗内容",
        theme: "round-button",
        className: "",
      });
    },
    showInfor() {
      this.$router.push("/infor");
    },
    showList() {
      this.$router.push("/list");
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
};
</script>
<style lang="scss" scoped>
a{
  color: #000000;
}
#container {
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
  background-color: grey;
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
}
.van-cell {
  color: #ffffff !important;
  background-color: grey;
}

#others {
  position: absolute;
  width: 100%;
  height: 20vh;
  background-color: grey;
  opacity: 1;
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