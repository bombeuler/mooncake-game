/* eslint-disable for-direction */
<template>
  <div>
    <main class="game">
      <van-row type="flex" class="top" justify="start" align="center">
        <van-col span="8" offset="2">{{ name }}</van-col>
        <van-col span="4" offset="1" class="my-score"
          >{{ sugar }}+{{ dough }}</van-col
        >
        <van-col span="7">剩余机会：{{ score }}</van-col>
      </van-row>
      <van-row class="middle">
        <div class="main-food">
          <van-button @click="plusItem('sugar', 15)">烧糖</van-button>
          <van-button @click="plusItem('dough', 15)">面</van-button>
          <van-button @click="deleteSubject">垃圾桶</van-button>
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
              :background-image="
                `linear-gradient(to right,${progressColor} ${parseInt(
                  (sub.eatAdd / sub.grade) * 100
                )}%, white 0px)`
              "
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
import Student from "../untils/Student";

const badPage = 0.05;

export default {
  name: "Game",
  data() {
    return {
      notice: "",
      progressColor: "#e67e22",
      firstTime: null,
      endTime: null,
      name: "BOMB",
      score: 34,
      sugar: 0,
      dough: 0,
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
    async timeInterval(todo, time = 1, interval = 1000) {
      for (let j = 0; j < time; j++) {
        console.log(4);
        await this.timeout(todo, j, interval);
      }
    },
    timeout(doSome, argue, time = 1000) {
      return new Promise((resolve, reject) => {
        // eslint-disable-next-line no-constant-condition
        if (true) {
          setTimeout(function() {
            doSome(argue);
            resolve(argue);
          }, time);
        } else {
          reject;
        }
      });
    },
    //FIXME 性能问题
    makeStudent(score = 0, init = false) {
      let made = {};
      if (init) {
        made = this.nowStudent;
      } else {
        made = new Student(score);
        this.nowStudent = made;
        this.deadTime = made.deadTime;
      }
      this.timeInterval(t => {
        this.notice = `还剩${(made.deadTime - t).toString()}秒`;
      }, this.deadTime);
    },
    diffSubject() {
      let flag = false;
      if (this.hasSubject.indexOf(false) !== -1) {
        return -1;
      } else {
        // eslint-disable-next-line for-direction
        for (let i = this.hasSubject.length - 1; i >= 0; i++) {
          for (let j = 0; j < this.nowStudent.deadSubjects.length; j++) {
            if (this.hasSubject[i] === this.nowStudent.deadSubjects[j]) {
              flag = i;
              break;
            }
          }
          if (flag !== false) {
            break;
          }
        }
        return flag;
      }
    },
    makeCake() {
      //TODO 点击学生事件
      console.log(this.diffSubject());
    }
  },
  created() {},
  mounted() {
    this.firstTime = new Date().getTime();
    this.makeStudent(true);
  }
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
          padding: 2px;
          @include border-normal(top);
        }
      }
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
      @include flex-normal(row);
    }
  }
}
</style>
