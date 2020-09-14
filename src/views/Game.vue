<template>
  <main class="game">
    <van-row type="flex" class="top" justify="start" align="center">
      <van-col span="8" offset="2">{{name}}</van-col>
      <van-col span="4" offset="1" class="my-score">{{sugar}}+{{dough}}</van-col>
      <van-col span="7">剩余机会：{{score}}</van-col>
    </van-row>
    <van-row class="middle">
      <div class="main-food">
        <van-button @click="plusItem('sugar',15)">烧糖</van-button>
        <van-button @click="plusItem('dough',15)">面</van-button>
        <van-button @click="deleteSubject">垃圾桶</van-button>
      </div>
      <div class="student" @click="makeCake"></div>
    </van-row>
    <van-row class="bottom">
      <div class="subject-group">
        <van-button
          v-for="subject in subjectOut1"
          :key="subject.key"
          @click="plusSubject(subject.key)"
        >{{subject.name}}</van-button>
      </div>
      <div class="subject-group">
        <van-button
          v-for="subject in subjectOut2"
          :key="subject.key"
          @click="plusSubject(subject.key)"
        >{{subject.name}}</van-button>
      </div>
    </van-row>
  </main>
</template>

<script>
import Student from "../untils/Student";

const badPage = 0.05;

export default {
  name: "Game",
  data() {
    return {
      name: "BOMB",
      score: 34,
      sugar: 0,
      dough: 0,
      nowstudent: new Student(0, true),
      hasSubject: [],
      subjects: [
        { name: "数学分析", key: "m0" },
        { name: "高等代数", key: "m1" },
        { name: "常微分方程", key: "m2" },
        { name: "实变函数", key: "m3" },
        { name: "复变函数", key: "m4" },
        { name: "近世代数", key: "m5" },
        { name: "概率论", key: "m6" },
      ],
    };
  },
  computed: {
    subjectOut1() {
      let arr = this.subjects;
      let out = [];
      out = arr.slice(0, 3);
      return out;
    },
    subjectOut2() {
      let arr = this.subjects;
      let out = [];
      out = arr.slice(3);
      return out;
    },
  },
  methods: {
    plusItem(item, limit) {
      if (this[item] < limit) {
        this[item]++;
      } else {
        return false;
      }
      return true;
    },
    plusSubject(subkey) {
      if (this.hasSubject.length < 3) {
        const random = Math.random();
        if (random <= badPage) {
          this.hasSubject.push(false);
        } else {
          this.hasSubject.push(subkey);
        }
      } else {
        return false;
      }
      return true;
    },
    deleteSubject() {
      this.hasSubject.splice(0, this.hasSubject.length);
    },
    makeCake() {
      console.log(this.nowStudent);
    },
  },
};
</script>

<style lang="scss" scoped>
@import url("../style/var.scss");

@mixin flex-normal($direct) {
  display: flex;
  flex-direction: $direct;
  justify-content: space-around;
  align-items: center;
}

.game {
  display: flex;
  flex-direction: column;
  width: 100vw;
  height: 100vh;
  .top {
    height: 10vh;
    background: burlywood;
    flex: {
      grow: 0;
      shrink: 0;
    }
    .my-score {
      font: {
        size: 1.5em;
        family: arual;
      }
    }
  }
  .middle {
    @include flex-normal(row);
    background: rgb(140, 189, 140);
    flex: {
      grow: 1;
      shrink: 0;
    }
    margin: {
      top: 4vh;
      bottom: 4vh;
    }
    .main-food {
      width: 40vw;
      min-height: 50vh;
      @include flex-normal(column);
    }
    .student {
      background-color: #fff;
      margin: 0 auto;
      width: 50vw;
      min-height: 50vh;
    }
  }
  .bottom {
    height: 25vh;
    width: 100vw;
    background: darkgrey;
    display: flex;
    justify-content: space-around;
    flex: {
      direction: column;
      grow: 0;
      shrink: 0;
    }
    .subject-group {
      display: flex;
      @include flex-normal(row);
    }
  }
}
</style>