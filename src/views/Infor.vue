<template>
  <div id="bg">
    <div id="container">
      <div id="infor">
        <div id="avatar">
          <div id="avatar-img">
            <img
              width="100%"
              src="../../public/assets/avatar.png"
              class="icon-box"
            />
            <div id="avatar-name">{{ name }}</div>
          </div>
        </div>
        <div id="my-infor">
          <div id="my-rank" class="infor">
            <div>名次</div>
            {{ rank }}
          </div>
          <hr style="width:100%" />
          <div id="my-score-st" class="infor">
            <div>游戏总分数</div>
            {{ credit }}
          </div>
          <hr style="width:100%" />
          <div id="my-credit" class="infor">
            <div>当前所得积分</div>
            {{ intergral }}
          </div>
        </div>
      </div>
      <div id="rule">
        积分计算规则

        <div class="bb">第一,二,三名分别获得440,420,400积分</div>
        <div class="bb">后续名次按积分=400-排名*4公式计算</div>
        <div class="bb">积分每日0点更新，此积分与科协其他活动共用</div>

        <div class="tips">需实名注册并填写调查问卷才能领奖哦</div>
      </div>

      <div class="authen">
        <van-button
          color="rgba(255, 255, 255, 0.5)"
          round
          size="large"
          @click="authen"
          >实名注册</van-button
        >
      </div>
      <div id="logout">
        <van-button
          color="rgba(255, 255, 255, 0.5)"
          round
          size="large"
          @click="logout"
          >退出登录</van-button
        >
      </div>
    </div>
    <div id="arrow-back" v-on:click="backToHome">
      <van-icon name="arrow-left" size="20" />
    </div>
    <van-dialog
      v-model="show"
      title="实名注册"
      :show-confirm-button="false"
      close-on-click-overlay
    >
      <van-form @submit="onAuth" label-width="30px" :show-error="false">
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
        <div style="margin: 16px;">
          <van-button round block type="info" native-type="submit">
            提交
          </van-button>
        </div>
      </van-form>
    </van-dialog>
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
      credit: "",
      show: false,
      tel: "",
      realName: "",
      stuNum: "",
      intergral: "",
      phonePattern: /^[1](([3][0-9])|([4][5-9])|([5][0-3,5-9])|([6][5,6])|([7][0-8])|([8][0-9])|([9][1,8,9]))[0-9]{8}$/,
      uidPattern: /[Uu]20(16|17|18|19|20)\d{5,}/
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
    },
    authen() {
      this.show = true;
    },
    onAuth(values) {
      values.nick = this.name;
      values = JSON.stringify(values);
      console.log(rsa(values));
      Axios({
        method: "post",
        url: "/mooncake/php/auth.php",
        data: {
          values: rsa(values)
        }
      }).then(response => {
        // console.log(response.data);
        let data = response.data;
        if (data.status == 200) {
          this.show = false;
        }
      });
    }
  },
  created() {
    if (sessionStorage.signStatus != 1) {
      this.$router.push("/home");
    }
    console.log(this.name);
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
        this.credit = data.credit;
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
  background: #ffffff url("../../public/assets/bg.png") no-repeat fixed right;
  background-size: cover;
  display: flex;
  flex-direction: column;
}
#container {
  width: 80vw;
  background-color: rgba(0, 0, 0, 0.5);
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
  height: fit-content;
  border-radius: 2vw 2vw 0 0;
  background-color: rgba(255, 255, 255, 0.5);
}
#avatar {
  display: inline-block;
  width: 48vw;
  /* height: 48vw; */
}
#avatar-img {
  width: 60%;
  height: 60%;
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
  display: flex;
  /* flex-direction: column; */
  flex-wrap: wrap;
  justify-content: center;
  width: 32vw;
  /* height: 48vw; */
}
.infor {
  width: 100%;
  text-align: center;
  margin-top: 0.5vw;
  margin-bottom: 0.5vw;
  border-left: 1px solid #000;
}

#rule {
  font-weight: bold;
  background-color: rgba(255, 255, 255, 0.5);
  width: 100%;
  height: 40vw;
  margin: 0;
  border-radius: 0 0 2vw 2vw;
  padding: 1vw 0;
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
  flex-direction: column;
}
.tips {
  font-size: 10px;
  font-weight: lighter;
  color: white;
  text-align: center;
}
.bb {
  font-weight: normal;
  font-size: 14px;
}

.authen {
  width: 49%;
  margin-top: 1vw;
  margin-right: 1vw;
}

#logout {
  width: 49%;
  margin-top: 1vw;
  text-align: center;
}

#arrow-back {
  background-color: rgba(0, 0, 0, 0.5);
  width: 11vw;
  height: 11vw;
  text-align: center;
  line-height: 13.3vw;
  border-radius: 50%;
  margin-left: 15vw;
  margin-top: 6vw;
}
</style>
