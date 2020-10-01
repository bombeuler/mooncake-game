const MAX = 20;
const MIN = 5;
const RADNUM = 3;
const Just0to1 = x => 1 - 1 / (1 + Math.exp(-(x / 7 - 10)));
const subLists = new Map([
  ["m0", "数学分析"],
  ["m1", "高等代数"],
  ["m2", "实变函数"],
  ["m3", "复变函数"],
  ["m4", "近世代数"],
  ["m5", "概率论"]
]);

const badPage = 0.02;
const subjectMax = 5;
const scoreStep = 10;
const scoreExtend = 16;
const sugarMaxN = 30;
const doughMaxN = 30;

export {
  MAX,
  MIN,
  RADNUM,
  Just0to1,
  subLists,
  badPage,
  subjectMax,
  scoreStep,
  scoreExtend,
  sugarMaxN,
  doughMaxN
};
