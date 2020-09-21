<template>
  <div id="bg">
    <div id="container">
      <div id="infor">
        <div id="avatar">
          <div id="avatar-img"></div>
          <div id="avatar-name">{{name}}</div>
        </div>
        <div id="my-infor">
          <div id="my-rank" class="infor">
            <div>名次</div>
            {{rank}}
          </div>
          <hr />
          <div id="my-score-st" class="infor">
            <div>最高分数</div>9999
          </div>
          <hr />
          <div id="my-credit" class="infor">
            <div>当前所得积分</div>{{intergral}}
          </div>
        </div>
      </div>
      <div id="rule">积分计算规则</div>
      <div id="logout">
        <van-button color="skyblue" round size="large" @click="logout">退出登录</van-button>
      </div>
      <div id="arrow-back" v-on:click="backToHome">
        <van-icon name="arrow-left" size="20" />
      </div>
    </div>
  </div>
</template>

<script>
import Axios from "axios";
export default {
  name: "Infor",
  data() {
    return {
      name: localStorage.nick,
      rank: '',
      intergral:''
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
    let nick = this.name;
    Axios({
      method: "post",
      url: "http://localhost/mooncake-game/php/infor",
      data: {
        nick
      }
    }).then(response => {
      let data = response.data;
      console.log(data);
      if (data.status == 200) {
        this.rank = data.rank;
        this.intergral=data.intergral;
      }
    });
  }
};
</script>

<style lang="css" scoped>
#bg {
  position: absolute;
  height: 100vh;
  width: 100vw;
  background: #ffffff url("../../public/bg.png") no-repeat fixed right;
  background-size: cover;
}
#container {
  width: 80vw;
  height: 60vh;
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
  margin-left: 5vw;
  margin-top: 12vw;
}
</style>
