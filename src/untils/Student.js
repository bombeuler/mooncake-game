import { shuffle } from "lodash";
import { MAX, MIN, RADNUM, Just0to1, subLists } from "./game.config";

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

export default Student;
