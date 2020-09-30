/* eslint-disable for-direction */
<template>
  <div class="bg">
    <van-overlay :show="isEnd" class="over-box">
      <div class="end-box">
        <div class="end-head">
          <div class="score-head">得分</div>
          <div class="score-box">{{ score }}</div>
        </div>
        <div class="end-middle"><who-win /></div>
        <span style="font-size:13px;color:white;"
          >扫描下方二维码填写调查问卷</span
        >
        <van-image
          width="30vw"
          :src="require('./../assets/erweima.jpg')"
          class="icon-box"
        />
        <div class="end-foot">
          <cyc-button content="回到首页" icon-item="wap-home-o" where="/home" />
          <cyc-button
            content="再来一次"
            icon-item="replay"
            where="0"
            :isReplaced="true"
          />
          <cyc-button content="喂食榜" icon-item="bar-chart-o" where="/list" />
        </div>
      </div>
    </van-overlay>
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
          <div class="food-item">
            <van-image
              @click="plusItem('sugar', sugarMax)"
              fit="contain"
              width="13.8vw"
              :src="require('../assets/sugar.png')"
            />
            <span class="food-text">烧糖&nbsp;&nbsp;{{ sugar }}</span>
          </div>
          <div class="food-item">
            <van-image
              @click="plusItem('dough', doughMax)"
              fit="contain"
              width="15.7vw"
              :src="require('../assets/dough.png')"
            />
            <span class="food-text">面团&nbsp;&nbsp;{{ dough }}</span>
          </div>
          <div class="food-item">
            <van-image
              @click="deleteSubject"
              fit="contain"
              width="12.3vw"
              :src="require('../assets/trash.png')"
            />
            <span class="food-text">垃圾桶&nbsp;&nbsp;{{ trashNumber }}</span>
          </div>
        </div>
        <div class="student-out">
          <div class="student" @click="makeCake">
            <div class="notice">{{ notice }}</div>
            <div class="people-box">
              <van-image
                class="people"
                :src="require('@/assets/student.png')"
                width="70%"
              />
            </div>
            <div class="sugar-need-box">
              <span>所需烧糖数量</span>
              <span>{{ this.nowStudent.sugarNeed }}</span>
            </div>
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
        </div>
      </van-row>
      <van-row class="bottom">
        <div class="subject-group">
          <van-button
            v-for="subject in subjectOut1"
            :key="subject.key"
            @click="plusSubject(subject.key)"
            class="subject-button"
            color="#2c2c2c"
            >{{ subject.name }}</van-button
          >
        </div>
        <div class="subject-group">
          <van-button
            v-for="subject in subjectOut2"
            :key="subject.key"
            @click="plusSubject(subject.key)"
            class="subject-button"
            color="#2c2c2c"
            >{{ subject.name }}</van-button
          >
        </div>
      </van-row>
    </main>
  </div>
</template>

<script>
import Student from "../untils/Student";
import {
  MIN,
  badPage,
  subjectMax,
  scoreStep,
  scoreExtend,
  sugarMaxN,
  doughMaxN
} from "../untils/game.config";
import rsa from "../untils/rsa";
import Axios from "axios";

import whoWin from "../components/who-win";
import cycButton from "../components/cyc-button";

export default {
  name: "Game",
  data() {
    return {
      isEnd: false,
      notice: "",
      progressColor: "#ecc02c",
      trashNumber: 0,
      firstTime: null,
      firstPeopleTime: null,
      endTime: null,
      endPeopleTime: null,
      cleanTimeout: false,
      name: "",
      life: 3,
      isAlive: true,
      score: 0,
      sugar: 0,
      sugarMax: sugarMaxN,
      dough: 0,
      doughMax: doughMaxN,
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
  created() {
    if (localStorage.getItem("user")) {
      this.name = JSON.parse(localStorage.getItem("user")).nick;
    } else {
      this.$router.push("/home");
    }
  },
  mounted() {
    this.firstTime = new Date().getTime();
    this.firstPeopleTime = this.firstTime;
    this.makeStudent(0, true);
  },
  computed: {
    progressNum() {
      return sub =>
        `linear-gradient(to right,${this.progressColor} ${parseInt(
          (sub.eatAdd / sub.grade) * 100
        )}%, #b2bec3 0px)`;
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
    },
    // eslint-disable-next-line no-unused-vars
    trashNumber(newVal, oldVal) {
      if (this.firstTime >= new Date("2020/10/5").getTime() && newVal >= 300) {
        const nick = this.nick;
        Axios({
          method: "post",
          url: "/mooncake/php/record.php",
          data: { nick, record: true }
        });
      }
    },
    // eslint-disable-next-line no-unused-vars
    isAlive(newVal, oldVal) {
      if (!newVal) {
        const nick = this.name;
        const time = this.endTime - this.firstTime;
        const score = this.score;
        let values = rsa(JSON.stringify({ nick, time, score }));
        this.$toast.loading({
          message: "上传分数中...",
          forbidClick: true
        });
        Axios({
          method: "post",
          url: "/mooncake/php/game.php",
          data: {
            values
          }
        })
          .then(res => {
            console.log(res.data);
            if (res.data === 1) {
              this.$toast.success("分数已上传");
            } else {
              this.$toast.oast.fail("分数上传失败，请检查网络状态");
            }
          })
          // eslint-disable-next-line no-unused-vars
          .catch(err => {
            this.$toast.fail("分数上传失败，请检查网络状态");
          })
          .finally(() => {
            this.isEnd = true;
          });
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
      this.score += step * this.nowStudent.subjectsNum;
    },
    deleteSubject() {
      this.trashNumber += this.hasSubject.length;
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
          if (this.life > 1) {
            this.life--;
            this.plusScore(0);
            this.makeStudent(this.score);
          } else {
            this.life--;
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
  },
  components: {
    whoWin,
    cycButton
  }
};
</script>

<style lang="scss" scoped>
$birthRed: #ef4971;
$sadBlue: #40eaff;
$bombYellow: #ecc02c;
$onechoBlue: #0d4f89;
$borderColor: #ffe9d1;
$studWidth: 90%;

.bg {
  height: 100vh;
  height: calc(var(--vh, 1vh) * 100);
}

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

.over-box {
  @include flex-normal(row);
  .end-box {
    margin: 0 auto;
    width: 90vw;
    height: 150vw;
    background-color: rgba(189, 186, 186, 0.8);
    @include flex-normal(column);
    border-radius: 1em;
    .end-head {
      @include flex-normal(column);
      width: 100%;
      height: 15%;
      .score-head {
        margin-top: 3px;
      }
      .score-box {
        @include flex-normal(row);
        height: 100%;
        width: auto;
        color: black;
        font: {
          size: 2.5em;
        }
      }
    }
    .end-middle {
      @include flex-normal(row);
      background-color: rgba(255, 255, 255, 0.3);
      border-radius: 1em;
      flex-shrink: 1;
    }
    .end-foot {
      @include flex-normal(row);
      width: 100%;
      height: 16%;
    }
  }
}

.game {
  display: flex;
  flex-direction: column;
  width: 100vw;
  caret-color: rgba(0, 0, 0, 0);
  user-select: none;
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
    .main-food {
      width: 40vw;
      min-height: 50vh;
      @include flex-normal(column);
      .food-item {
        @include flex-normal(column);
        .food-text {
          margin-top: 6px;
          color: $bombYellow;
        }
      }
    }
    .student-out {
      background-color: $onechoBlue;
      .student {
        background-color: $onechoBlue;
        margin: 0 auto;
        width: 50vw;
        min-height: 50vh;
        margin: {
          left: 10px;
          right: 10px;
          top: 5px;
          bottom: 5px;
        }
        @include flex-normal(column);
        border: {
          style: solid;
          color: $borderColor;
          size: 4px;
        }
        .notice {
          width: 90%;
          height: 10%;
          background-color: $bombYellow;
          text-align: center;
        }
        .people-box {
          width: 90%;
          background-color: #2c2c2c;
          @include flex-normal(column);
          .people {
            margin: 0 auto;
            width: 75%;
            height: 100%;
          }
        }

        .sugar-need-box {
          @include flex-normal(row);
          background-color: $birthRed;
          width: $studWidth;
        }
        .stuList {
          width: 100%;
          .grade-group {
            width: $studWidth;
            margin: 0 auto;
            margin: {
              top: 5px;
              bottom: 5px;
            }
            @include flex-normal(row);
            user-select: none;
            overflow: hidden;
          }
        }
      }
    }
  }
  .bottom {
    height: 55vw;
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
      .subject-button {
        width: 24vw;
        height: 20vw;
      }
    }
  }
}
</style>
