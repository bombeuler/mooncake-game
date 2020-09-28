import axios from "axios";
import Qs from "qs";

const request = config => {
  const instance = axios.create({
    baseURL: "/php/",
    timeout: 10000,
    transformRequest: [
      function(data) {
        return Qs.stringify(data);
      }
    ],
    withCredentials: true
  });
  return instance(config);
};

export { request };
