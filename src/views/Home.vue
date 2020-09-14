<template>
  <div id="infor">
    <van-tabs v-model="active" background="grey" swipeable animated>
      <van-tab title="标签 1">
        <div id="signin">
          <van-form @submit="onSignIn">
            <van-field
              v-model="username"
              name="name"
              label="昵称"
              placeholder=""
              :rules="[{ required: true, message: '请填写用户名' }]"
            />
            <van-field
              v-model="password"
              type="password"
              name="password"
              label="密码"
              placeholder=""
              :rules="[{ required: true, message: '请填写密码' }]"
            />
            <div style="margin: 16px;">
              <van-button round block type="info" native-type="submit">提交</van-button>
            </div>
          </van-form>
        </div>
      </van-tab>
      <van-tab title="标签 2">
        <div id="signup">
          <van-form @submit="onSignUp">
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
              placeholder=""
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
              placeholder="密码"
              :rules="[{ required: true, message: '请填写密码' }]"
            />
            <div style="margin: 16px;">
              <van-button round block type="info" native-type="submit">提交</van-button>
            </div>
          </van-form>
        </div>
      </van-tab>
    </van-tabs>
  </div>
</template>

<script>
export default {
  name: "Home",
  data() {
    return {
      username: "",
      password: "",
      stuNum:"",
      realName:"",
      tel:"",
      pattern:/U\d{9,}/,
      active: 2,
    };
  },
  methods: {
    onSignIn(values) {
      console.log("submit", values);
    },
    onSignUp(values) {
      console.log("submit", values);
    },
  },
};
</script>
<style lang="scss" scoped>
#infor {
  position: absolute;
  top: 30%;
  left: 50%;
  transform: translateX(-50%);
  width: 90%;
  padding-top: 30px;
  border-radius: 30px;
  background-color: grey;
}
#signin,
#signup {
  margin: 0 auto;
  margin-top: 20px;
  width: 90%;
}
</style>