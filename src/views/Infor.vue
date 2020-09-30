<template>
  <div id="bg">
    <div id="container">
      <div id="infor">
        <div id="avatar">
          <div id="avatar-img"></div>
          <div id="avatar-name">{{ name }}</div>
        </div>
        <div id="my-infor">
          <div id="my-rank" class="infor">
            <div>名次</div>
            {{ rank }}
          </div>
          <hr />
          <div id="my-score-st" class="infor">
            <div>最高分数</div>
            9999
          </div>
          <hr />
          <div id="my-credit" class="infor">
            <div>当前所得积分</div>
            {{ intergral }}
          </div>
        </div>
      </div>
      <div id="rule">积分计算规则</div>

      <van-field
        v-model="stuNum"
        name="uid"
        label="学号"
        placeholder="U202001010"
        maxlength="10"
        :rules="[
          {
            required: true,
            pattern: uidPattern,
            message: '请输入正确学号'
          }
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
        :rules="[
          {
            required: true,
            pattern: phonePattern,
            message: '电话格式不正确'
          }
        ]"
      />
      <div id="logout">
        <van-button color="skyblue" round size="large" @click="logout"
          >退出登录</van-button
        >
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
  name: "Infor",
  data() {
    return {
      name: JSON.parse(localStorage.getItem("user")).nick,
      rank: "",
      intergral: ""
    };
  },
  methods: {
    backToHome() {
      this.$router.push("/home");
    },
    logout() {
      localStorage.clear();
      sessionStorage.signStatus = 0;
      this.$router.push("/home");
    }
  },
  created() {
    console.log(localStorage.nick, this.name);
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

<style lang="css" scoped>
#bg {
  position: absolute;
  height: 100vh;
  height: calc(var(--vh, 1vh) * 100);
  width: 100vw;
  background: #ffffff url("../../public/bg.png") no-repeat fixed right;
  background-size: cover;
  display: flex;
  flex-direction: column;
}
#container {
  width: 80vw;
  background-color: rgba(71, 96, 102, 0.5);
  margin: 0 auto;
  margin-top: 15vh;
  display: flex;
  flex-wrap: wrap;
  padding: 2vh 4vw;
  border-radius: 4vw;
}

#infor {
  display: flex;
  border-bottom: 1px solid #000;
  height: 48vw;
  border-radius: 2vw 2vw 0 0;
  background-color: white;
}
#avatar {
  display: inline-block;
  width: 48vw;
  height: 48vw;
}
#avatar-img {
  width: 60%;
  height: 60%;
  background-color: gainsboro;
  border-radius: 50%;
  margin: 0 auto;
  margin-top: 3vh;
}
#avatar-name {
  width: 60%;
  margin: 0 auto;
  margin-top: 1vh;
  text-align: center;
}
#my-infor {
  display: inline-block;
  width: 32vw;
  height: 48vw;
}
.infor {
  text-align: center;
  margin-top: 1.4vh;
  border-left: 1px solid #000;
}

#rule {
  background-color: white;
  width: 100%;
  height: 40vw;
  margin: 0;
  border-radius: 0 0 2vw 2vw;
}

#logout {
  width: 100%;
  margin-top: 1vw;
  text-align: center;
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
