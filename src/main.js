import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import VueRx from "vue-rx";

//此处引入组件
import { Button } from "vant";
import { Form } from "vant";
import { Field } from "vant";
import { Tab, Tabs } from "vant";
import { Icon } from "vant";
import { Dialog } from "vant";
import { List } from "vant";
import { Cell, CellGroup } from "vant";
import { Col, Row } from "vant";

Vue.use(Col);
Vue.use(Row);
Vue.use(Cell);
Vue.use(CellGroup);
Vue.use(List);
Vue.use(Dialog);
Vue.use(Icon);
Vue.use(Tab);
Vue.use(Tabs);
Vue.use(Form);
Vue.use(Button);
Vue.use(Field);
//引入完记得use

Vue.use(VueRx);

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  render: (h) => h(App),
}).$mount("#app");
