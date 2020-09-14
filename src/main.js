import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import VueRx from "vue-rx";

//此处引入组件
import { Button } from "vant";
import { Form } from "vant";
import { Field } from "vant";
import { Tab, Tabs } from 'vant';

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
