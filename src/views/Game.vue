/* eslint-disable for-direction */
<template>
  <div>
    <main class="game">
      <van-row type="flex" class="top" justify="space-around" align="center">
        <van-col class="user-box">
          <van-image
            width="5.4vw"
            :src="require('./../assets/user_icon.png')"
            class="icon-box"
          />
          <span class="user-name"> {{ name }}</span></van-col
        >
        <van-col span="4"
          ><div class="my-score">{{ score }}</div></van-col
        >
        <van-col span="8" class="life-box"
          ><van-rate
            icon="like"
            size="8vw"
            readonly
            count="3"
            v-model="life"
            void-icon="like"
            :touchable="false"
            void-color="#dfe6e9"
            color="#ef4971"
        /></van-col>
      </van-row>
      <van-row class="middle">
        <div class="main-food">
          <van-image
            @click="plusItem('sugar', sugarMax)"
            fit="contain"
            width="13.8vw"
            :src="require('../assets/sugar.png')"
          />
          <van-image
            @click="plusItem('dough', doughMax)"
            fit="contain"
            width="15.7vw"
            :src="require('../assets/dough.png')"
          />
          <van-image
            @click="deleteSubject"
            fit="contain"
            width="12.3vw"
            :src="require('../assets/trash.png')"
          />
        </div>
        <div class="student" @click="makeCake">
          <div class="notice">{{ notice }}</div>
          <img
            class="people"
            src="https://dummyimage.com/300x300.png?text=student"
          />
          <div class="stuList">
            <div
              v-for="sub in nowStudent.deadSubjects"
              :key="sub.subject"
              class="grade-group"
              :style="`background-image:${progressNum(sub)}`"
            >
              <span>{{ subValue(sub.subject) }}</span>
              <span>{{ sub.grade }}</span>
            </div>
          </div>
        </div>
      </van-row>
      <van-row class="bottom">
        <div class="subject-group">
          <van-button
            v-for="subject in subjectOut1"
            :key="subject.key"
            @click="plusSubject(subject.key)"
            >{{ subject.name }}</van-button
          >
        </div>
        <div class="subject-group">
          <van-button
            v-for="subject in subjectOut2"
            :key="subject.key"
            @click="plusSubject(subject.key)"
            >{{ subject.name }}</van-button
          >
        </div>
      </van-row>
    </main>
  </div>
</template>

<script>
import { Student, MIN } from "../untils/Student";

const badPage = 0.02;
const subjectMax = 5;
const scoreStep = 10;
const scoreExtend = 6;

export default {
  name: "Game",
  data() {
    return {
      notice: "",
      progressColor: "#ecc02c",
      firstTime: null,
      firstPeopleTime: null,
      endTime: null,
      endPeopleTime: null,
      cleanTimeout: false,
      name: "我是谁，8个字符",
      life: 3,
      isAlive: true,
      score: 0,
      sugar: 0,
      sugarMax: 25,
      dough: 0,
      doughMax: 25,
      nowStudent: new Student(0, true),
      hasSubject: [],
      subjects: [
        { name: "数学分析", key: "m0" },
        { name: "高等代数", key: "m1" },
        { name: "实变函数", key: "m2" },
        { name: "复变函数", key: "m3" },
        { name: "近世代数", key: "m4" },
        { name: "概率论", key: "m5" }
      ]
    };
  },
  computed: {
    progressNum() {
      return sub =>
        `linear-gradient(to right,${this.progressColor} ${parseInt(
          (sub.eatAdd / sub.grade) * 100
        )}%, #ef4971 0px)`;
    },
    subValue() {
      return subjectKey => Student.subLists.get(subjectKey);
    },
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
    }
  },
  watch: {
    "nowStudent.finnishedSubjects": {
      // eslint-disable-next-line no-unused-vars
      handler(newVal, oldVal) {
        if (newVal === this.nowStudent.subjectsNum) {
          this.plusScore(scoreStep);
          this.makeStudent(this.score);
        }
      }
    }
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
      if (this.hasSubject.length < subjectMax) {
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
    plusScore(step) {
      const justTime = new Date().getTime();
      this.endPeopleTime = justTime;
      if (
        justTime - this.firstPeopleTime <=
        MIN * this.nowStudent.subjectsNum * 1000
      ) {
        this.score += scoreExtend;
      }
      this.score += step;
    },
    deleteSubject() {
      this.hasSubject.splice(0, this.hasSubject.length);
    },
    async timeInterval(todo, time = 1, interval = 1000) {
      for (let j = 0; j < time; j++) {
        await this.timeout(todo, j, interval);
        if (this.cleanTimeout) {
          this.cleanTimeout = false;
          break;
        }
      }
    },
    timeout(doSome, argue, time = 1000) {
      return new Promise((resolve, reject) => {
        // eslint-disable-next-line no-constant-condition
        if (true) {
          let interId = null;
          interId = setInterval(function() {
            doSome(argue);
            resolve(argue);
            clearInterval(interId);
          }, time);
        } else {
          reject();
        }
      });
    },
    makeStudent(score = 0, init = false) {
      let made = {};
      if (init) {
        made = this.nowStudent;
      } else {
        this.cleanTimeout = true;
        made = new Student(score);
        this.firstPeopleTime = this.endPeopleTime;
        this.nowStudent = made;
      }
      this.deadTime = made.deadTime;
      this.finnishedSub = 0;
      this.timeInterval(t => {
        this.notice = `还剩${(made.deadTime - t).toString()}秒`;
        if (made.deadTime - t <= 0) {
          console.log(t);
          if (this.life > 0) {
            this.life--;
            this.plusScore(0);
            this.makeStudent(this.score);
          } else {
            this.cleanTimeout = true;
            this.endTime = new Date().getTime();
            this.isAlive = false;
          }
        }
      }, this.deadTime + 1);
    },
    diffSubject() {
      let flag = false;
      if (this.hasSubject.indexOf(false) !== -1) {
        return -1;
      } else {
        const pHasSubject = this.hasSubject;
        let pDeadSubjects = this.nowStudent.deadSubjects;
        for (let i = pHasSubject.length - 1; i >= 0; i--) {
          for (let j = 0; j < pDeadSubjects.length; j++) {
            if (pHasSubject[i] === pDeadSubjects[j].subject) {
              flag = [i, j];
              break;
            }
          }
          if (flag === "boolean") {
            break;
          }
        }
        return flag;
      }
    },
    isEnough() {
      if (this.sugar >= this.nowStudent.sugarNeed && this.dough >= 1) {
        return true;
      } else {
        return false;
      }
    },
    makeCake() {
      //TODO 点击学生事件
      if (
        this.diffSubject() !== false &&
        this.diffSubject() !== -1 &&
        this.isEnough()
      ) {
        const flg = this.diffSubject();
        if (!this.nowStudent.deadSubjects[flg[1]].finnished) {
          this.sugar -= this.nowStudent.sugarNeed;
          this.dough--;
          this.nowStudent.deadSubjects[flg[1]].eatAdd += 10;
          this.hasSubject.splice(flg[0], 1);
        }
      }
    }
  },
  created() {},
  mounted() {
    this.firstTime = new Date().getTime();
    this.firstPeopleTime = this.firstTime;
    this.makeStudent(0, true);
  }
};
</script>

<style lang="scss" scoped>
$birthRed: #ef4971;
$sadBlue: #40eaff;
$bombYellow: #ecc02c;

@mixin flex-normal($direct) {
  display: flex;
  flex-direction: $direct;
  justify-content: space-around;
  align-items: center;
}

@mixin border-normal($direct, $colour: black) {
  @if $direct == 0 {
    border: {
      style: solid;
      width: 0.1px;
      color: $colour;
    }
  } @else {
    border-#{$direct}: {
      style: solid;
      width: 0.1px;
      color: $colour;
    }
  }
}

.game {
  display: flex;
  flex-direction: column;
  width: 100vw;
  height: 100vh;
  background: {
    image: url("../../public/bg.png");
    repeat: no-repeat;
    size: 100%;
  }
  .top {
    height: 20.4vw;
    background: {
      image: url("../../public/head.png");
      repeat: no-repeat;
      size: 100%;
    }
    flex: {
      grow: 0;
      shrink: 0;
    }
    .user-box {
      @include flex-normal(row);
      // max-width: 20vw;
      padding: {
        left: 5px;
        right: 7px;
      }
      height: 9.7vw;
      border-radius: 5.3vw;
      background: $sadBlue;
      text-align: center;
      .icon-box {
        flex-grow: 0;
        flex-shrink: 0;
      }
      .user-name {
        color: $birthRed;
        white-space: nowrap;
        word-break: keep-all;
        padding-left: 7px;
        font: {
          size: 4vw;
        }
      }
    }
    .my-score {
      color: $sadBlue;
      margin: 0 auto;
      width: fit-content;
      font: {
        size: 8.5vw;
        family: arual;
      }
    }
    .life-box {
      @include flex-normal(row);
      flex-grow: 0;
      flex-shrink: 0;
      height: 9.7vw;
      border-radius: 5.3vw;
      background: $sadBlue;
      text-align: center;
    }
  }
  .middle {
    @include flex-normal(row);
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
      @include flex-normal(column);
      @include border-normal(0);
      .notice {
        width: 100%;
        height: 10%;
        @include border-normal(top);
        text-align: center;
      }
      .people {
        width: 100%;
      }
      .stuList {
        width: 100%;
        .grade-group {
          width: 100%;
          @include flex-normal(row);
          user-select: none;
          overflow: hidden;
          padding: {
            top: 2px;
            bottom: 2px;
          }
          @include border-normal(top);
        }
      }
    }
  }
  .bottom {
    height: 25vh;
    width: 100vw;
    display: flex;
    justify-content: space-around;
    flex: {
      direction: column;
      grow: 0;
      shrink: 0;
    }
    .subject-group {
      @include flex-normal(row);
    }
  }
}
</style>
