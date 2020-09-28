import { JSEncrypt } from "jsencrypt";

export default function(data) {
  let encrypt2 = new JSEncrypt();
  encrypt2.setPublicKey(
    "-----BEGIN PUBLIC KEY-----MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxbhwzUtEmSqy5dEcRwiB4omPxlSp1ItLUbVgHn+ahZdSwnqLhn0+8J8LNSIs6/PkHAXKUaPSZMEAHh9ekWi27hmRkdjDzcf6uR/dbkCFsO5GVCdUdnGjAZFlPTyDHBGAMDvNjE/fQIGbBdUlD5hUU9dohG5ObLzYDmI07tDxBwIDAQAB-----END PUBLIC KEY-----"
  );

  let encrypted = encrypt2.encrypt(data);

  return encrypted;
}
