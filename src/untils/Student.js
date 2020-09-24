import { shuffle } from "lodash";
const MAX = 15;
const MIN = 5;
const RADNUM = 3;
const Just0to1 = x => Math.exp(-x);
const subLists = new Map([
  ["m0", "数学分析"],
  ["m1", "高等代数"],
  ["m2", "实变函数"],
  ["m3", "复变函数"],
  ["m4", "近世代数"],
  ["m5", "概率论"]
]);
class Student {
  static subLists = subLists;
  constructor(score = 0, isFirst = false) {
    const step = MAX - MIN;
    const num = Math.ceil(Math.random() * 3);
    const time = parseInt(MIN + step * Just0to1(score)) * num;
    const timePlus = isFirst ? 8 : 0;
    const randomTime =
      Math.floor(Math.random() * RADNUM) - Math.floor(Math.random() * RADNUM);
    let arr = ["m0", "m1", "m2", "m3", "m4", "m5"];
    const preSubjects = shuffle(arr).slice(0, num);
    let preArr = [];
    for (let i = 0; i < preSubjects.length; i++) {
      const _this = this;
      const scored = 60 + Math.floor(Math.random() * 40);
      const handler = {
        get(target, propKey) {
          if (
            propKey === "eatAdd" &&
            target[propKey] >= target.grade &&
            !target.finnished
          ) {
            target.finnished = true;
            _this.finnishedSubjects++;
          }
          return target[propKey];
        }
      };
      preArr.push(
        new Proxy(
          {
            subject: preSubjects[i],
            grade: scored,
            finnished: false,
            eatAdd: 0
          },
          handler
        )
      );
    }
    this.subjectsNum = num;
    this.finnishedSubjects = 0;
    this.deadSubjects = preArr;
    this.deadTime = time + randomTime + timePlus;
    this.sugarNeed = Math.ceil(Math.random() * 3);
  }
}

export { Student, MIN };
