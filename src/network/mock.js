import Mock from "mockjs";

const data1 = [
  {
    name: "dfg",
    score: "h14"
  },
  {
    name: "dfg",
    score: "h14"
  },
  {
    name: "dfg",
    score: "h14"
  },
  {
    name: "dfg",
    score: "h14"
  },
  {
    name: "dfg",
    score: "h14"
  },
  {
    name: "dfg",
    score: "h14"
  },
  {
    name: "dfg",
    score: "h14"
  },
  {
    name: "dfg",
    score: "h14"
  },
  {
    name: "dfg",
    score: "h14"
  },
  {
    name: "dfg",
    score: "h14"
  },
  {
    name: "dfg",
    score: "h14"
  },
  {
    name: "dfg",
    score: "h14"
  },
  {
    name: "dfg",
    score: "h14"
  },
  {
    name: "dfg",
    score: "h14"
  },
  {
    name: "dfg",
    score: "h14"
  },
  {
    name: "dfg",
    score: "h14"
  }
];
//数据模板'name|min-max':value

//定义模拟接口只能接收post请求，定义返回的数据为data1
Mock.mock("/php/list", "post", () => Mock.mock(data1));
Mock.mock("/php/game.php", "post", () => Mock.mock(1));
